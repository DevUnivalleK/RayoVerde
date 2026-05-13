<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotWebhookController extends Controller
{
    /**
     * Registra el historial de mensajes en la tabla mensajes_chatbot
     */
    private function registrarLog($idConv, $emisor, $texto) {
        DB::table('mensajes_chatbot')->insert([
            'id_conversacion' => $idConv,
            'emisor'          => $emisor,
            'contenido'       => $texto,
            'enviado_en'      => now()
        ]);
    }

    /**
     * Actualiza el estado final de la conversación en conversaciones_chatbot
     */
    private function finalizarConversacion($id, $estado) {
        if (!$id) return;
        
        DB::table('conversaciones_chatbot')
            ->where('id_conversacion', $id)
            ->update([
                'estado'            => $estado,
                'finalizada_en'     => now(),
                'derivada_a_agente' => ($estado == 'DERIVADA')
            ]);
    }

    public function handle(Request $request)
    {
        $rawMessage = trim($request->json('message'));
        $userMessage = str_replace(',', '.', $rawMessage);
        $idConversacion = $request->json('id_conversacion');

        // 1. Inicialización
        if (!$idConversacion || $idConversacion == "null") {
            $idConversacion = DB::table('conversaciones_chatbot')->insertGetId([
                'id_usuario'  => auth()->id(),
                'paso_actual' => 'INICIO',
                'estado'      => 'ACTIVA',
                'iniciada_en' => now()
            ], 'id_conversacion');
            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
        }

        // [LOG] Guardar mensaje del usuario
        $this->registrarLog($idConversacion, 'usuario', $userMessage);

        $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->first();
        $estadoActual = $conversacion->paso_actual;

        // 2. Comandos de Reinicio y Abandono
        if (in_array(strtolower($userMessage), ['hola', 'menu', 'inicio', 'volver'])) {
            // Si abandona un flujo activo para ir al inicio, sellamos la actual como ABANDONADA
            if ($estadoActual != 'INICIO' && $conversacion->estado == 'ACTIVA') {
                $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                
                // Generamos nueva conversación para el nuevo inicio
                $idConversacion = DB::table('conversaciones_chatbot')->insertGetId([
                    'id_usuario'  => auth()->id(),
                    'paso_actual' => 'INICIO',
                    'estado'      => 'ACTIVA',
                    'iniciada_en' => now()
                ], 'id_conversacion');
            }
            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
            $estadoActual = 'INICIO';
        }

        $nuevoEstado = $estadoActual;
        $reply = null;

        // 3. Máquina de Estados
        switch ($estadoActual) {
            case 'INICIO':
            case 'ESPERANDO_MENU':
                if ($userMessage == '1') {
                    $nuevoEstado = 'SOLICITAR_PRODUCTO';
                } elseif ($userMessage == '2') {
                    $nuevoEstado = 'ESPERANDO_FAQ';
                } elseif ($userMessage == '3') {
                    $this->finalizarConversacion($idConversacion, 'DERIVADA');
                    $reply = '⏳ Redirigiendo con un asesor de Rayo Verde... Por favor, espera un momento.';
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    return response()->json(['reply' => $reply, 'redirect' => route('home')]);
                }
                break;

            case 'ESPERANDO_FAQ':
                $preguntas = DB::table('chatbot_faqs')->orderBy('id_faq', 'asc')->get();
                $idx = (int)$userMessage - 1;

                if (isset($preguntas[$idx])) {
                    $faq = $preguntas[$idx];
                    DB::table('chatbot_faqs')->where('id_faq', $faq->id_faq)->increment('contador_uso');
                    $reply = "" . $faq->respuesta . "\n\n1. Ver otras dudas\n2. Hablar con un Asesor\n3. Volver al menu principal";
                    $nuevoEstado = 'FAQ_CONTESTADA';
                } elseif ($userMessage == (count($preguntas) + 1)) {
                    $this->finalizarConversacion($idConversacion, 'DERIVADA');
                    $reply = 'Redirigiendo con un asesor de Rayo Verde... Por favor, espera un momento.';
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    return response()->json(['reply' => $reply, 'redirect' => route('home')]);
                } else {
                    $nuevoEstado = 'ESPERANDO_FAQ';
                }
                break;

            case 'FAQ_CONTESTADA':
                if ($userMessage == '1') {
                    $nuevoEstado = 'ESPERANDO_FAQ';
                } elseif ($userMessage == '2') {
                    $this->finalizarConversacion($idConversacion, 'DERIVADA');
                    $reply = 'Redirigiendo con un asesor...';
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    return response()->json(['reply' => $reply, 'redirect' => route('home')]);
                } else {
                    $nuevoEstado = 'INICIO';
                }
                break;

            case 'SOLICITAR_PRODUCTO':
                $productos = DB::table('productos')->get();
                $idx = (int)$userMessage - 1;
                if (isset($productos[$idx])) {
                    session(['temp_prod' => [
                        'id' => $productos[$idx]->id_producto,
                        'nombre' => $productos[$idx]->nombre,
                        'precio' => $productos[$idx]->precio
                    ]]);
                    $nuevoEstado = 'SOLICITAR_UNIDAD';
                }
                break;

            case 'SOLICITAR_UNIDAD':
                session(['temp_unidad' => ($userMessage == '1' ? 'ml' : 'L')]);
                $nuevoEstado = 'SOLICITAR_CANTIDAD';
                break;

            case 'SOLICITAR_CANTIDAD':
                $prod = session('temp_prod');
                $uniUser = session('temp_unidad');
                $cantUser = (float)$userMessage;
                if ($uniUser == 'ml') $cantUser = round($cantUser);

                preg_match('/(\d+(?:\.\d+)?)\s*(ml|L|l)/i', $prod['nombre'], $matches);
                $valBase = isset($matches[1]) ? (float)$matches[1] : 1;
                $uniBase = isset($matches[2]) ? strtolower($matches[2]) : 'ml';
                $baseEnLitros = ($uniBase == 'ml') ? $valBase / 1000 : $valBase;
                $userEnLitros = ($uniUser == 'ml') ? $cantUser / 1000 : $cantUser;

                if ($userEnLitros < ($baseEnLitros - 0.0001)) {
                    $nuevoEstado = 'ERROR_CANTIDAD_MINIMA';
                    $msgMinimo = ($uniBase == 'ml') ? "{$valBase}ml" : "{$valBase}L";
                    $reply = "Cantidad insuficiente. El mínimo para este producto es de *{$msgMinimo}*.\n\n" .
                             "Tu ingreso: {$cantUser}{$uniUser}\n\n" .
                             "¿Qué deseas hacer?\n1. Intentar con otra cantidad (Ej: 1.5 o 500)\n2. Cancelar y volver al menú";
                    
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->update(['paso_actual' => $nuevoEstado]);
                    return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
                }

                $subtotal = ($prod['precio'] / $baseEnLitros) * $userEnLitros;
                $carrito = session('carrito_chatbot', []);
                $carrito[] = [
                    'nombre' => $prod['nombre'], 'id_prod' => $prod['id'], 
                    'cant' => $cantUser, 'uni' => $uniUser, 'sub' => $subtotal
                ];
                session(['carrito_chatbot' => $carrito]);
                $nuevoEstado = 'PREGUNTAR_BUCLE';
                break;

            case 'ERROR_CANTIDAD_MINIMA':
                if ($userMessage == '1') {
                    $nuevoEstado = 'SOLICITAR_CANTIDAD';
                } else {
                    session()->forget(['temp_prod', 'temp_unidad']);
                    $nuevoEstado = 'INICIO';
                }
                break;

            case 'PREGUNTAR_BUCLE':
                $nuevoEstado = ($userMessage == '1') ? 'SOLICITAR_PRODUCTO' : 'MOSTRAR_RESUMEN';
                break;

            case 'MOSTRAR_RESUMEN':
                if ($userMessage == '1') return $this->finalizarCotizacion($idConversacion);
                $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
                $nuevoEstado = 'INICIO';
                break;
        }

        // 4. Generación de Respuesta Final
        DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->update(['paso_actual' => $nuevoEstado]);
        
        if (!$reply) {
            if ($nuevoEstado == 'INICIO' || $nuevoEstado == 'ESPERANDO_MENU') {
                $reply = "¡Hola! Soy el asistente de Rayo Verde. Selecciona una opción:\n1. Nueva Cotización\n2. Preguntas Frecuentes\n3. Hablar con un Asesor";
            }
            elseif ($nuevoEstado == 'ESPERANDO_FAQ') {
                $faqs = DB::table('chatbot_faqs')->orderBy('id_faq', 'asc')->get();
                $reply = "Preguntas Frecuentes\nSelecciona el numero de tu duda:\n\n";
                foreach ($faqs as $i => $f) { $reply .= ($i+1) . ". " . $f->pregunta . "\n"; }
                $reply .= (count($faqs) + 1) . ". No veo mi duda (Hablar con un asesor)\n\nO escribe Volver.";
            }
            elseif ($nuevoEstado == 'SOLICITAR_PRODUCTO') {
                $prods = DB::table('productos')->get();
                $reply = "Selecciona un producto:\n";
                foreach ($prods as $i => $p) $reply .= ($i + 1) . ". " . $p->nombre . " (Bs. " . $p->precio . ")\n";
            } 
            elseif ($nuevoEstado == 'MOSTRAR_RESUMEN') {
                $carrito = session('carrito_chatbot', []);
                $res = "RESUMEN DE COTIZACION\n\n";
                $total = array_sum(array_column($carrito, 'sub'));
                foreach ($carrito as $c) {
                    $res .= "- {$c['nombre']}\n  {$c['cant']} {$c['uni']} -> Bs. " . number_format($c['sub'], 2) . "\n\n";
                }
                $reply = $res . "----------\nTOTAL: Bs. " . number_format($total, 2) . "\n\n1. Confirmar Cotizacion y Continuar con la Compra\n2. Cancelar todo";
            }
            elseif ($nuevoEstado == 'SOLICITAR_CANTIDAD') {
                $uni = session('temp_unidad');
                $reply = "Indica la cantidad en " . $uni . ":\n(Ej: " . ($uni == 'L' ? "1.5" : "250") . ")";
            } else {
                $paso = DB::table('chatbot_pasos')->where('estado_actual', $nuevoEstado)->first();
                $reply = $paso->mensaje_bot ?? "Selecciona una opción:";
            }
        }

        // [LOG] Guardar respuesta del bot
        $this->registrarLog($idConversacion, 'bot', $reply);

        return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
    }

    private function finalizarCotizacion($id) {
        $carrito = session('carrito_chatbot', []);
        $idUsuarioLogueado = auth()->id();
        
        if (empty($carrito)) return response()->json(['reply' => 'El carrito esta vacio.', 'redirect' => route('home')]);
        if (!$idUsuarioLogueado) return response()->json(['reply' => 'Inicia sesion para finalizar.', 'redirect' => route('login')]);

        try {
            DB::transaction(function () use ($carrito, $idUsuarioLogueado, $id) {
                $total = array_sum(array_column($carrito, 'sub'));
                $cotId = DB::table('cotizaciones')->insertGetId([
                    'codigo' => 'COT-' . strtoupper(uniqid()),
                    'id_usuario' => $idUsuarioLogueado,
                    'id_estado' => 1,
                    'total' => $total,
                    'generado_en' => now()
                ], 'id_cotizacion');

                foreach ($carrito as $item) {
                    DB::table('detalle_cotizaciones')->insert([
                        'id_cotizacion' => $cotId,
                        'id_producto'   => $item['id_prod'],
                        'volumen_litros'=> (float)($item['uni'] == 'ml' ? $item['cant'] / 1000 : $item['cant']),
                        'precio_unitario' => (float)($item['sub'] / ($item['cant'] == 0 ? 1 : $item['cant'])),
                        'subtotal'      => (float)$item['sub']
                    ]);
                }

                // ÉXITO: Sellar conversación
                $this->finalizarConversacion($id, 'FINALIZADA');
            });

            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
            $this->registrarLog($id, 'bot', "Cotización guardada exitosamente.");
            
            return response()->json(['reply' => 'Cotizacion guardada exitosamente.', 'redirect' => route('home')]);
            return response()->json([
                   'reply' => " *Cotización guardada correctamente.*\n\nEspera un momento, por favor... te estamos dirigiendo a la sección de compra para finalizar tu pedido. ", 
                   'redirect' => route('home')
            ]);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error técnico, intente más tarde.', 'id_conversacion' => $id]);
        }
    }
}