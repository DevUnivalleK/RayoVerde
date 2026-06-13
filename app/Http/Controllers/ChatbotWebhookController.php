<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotWebhookController extends Controller
{
    private function registrarLog($idConv, $emisor, $texto) {
        DB::table('mensajes_chatbot')->insert([
            'id_conversacion' => $idConv,
            'emisor'          => $emisor,
            'contenido'       => $texto,
            'enviado_en'      => now()
        ]);
    }

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

    /**
     * Helper para obtener el catálogo de productos único, priorizando la menor cantidad/medida.
     */
    private function obtenerProductosCatalogo() {
        $todosLosProductos = DB::table('productos')->get();
        $agrupados = [];

        foreach ($todosLosProductos as $producto) {
            // Expresión regular para capturar el nombre base y el volumen (ej: Aceite Coco 250ml)
            if (preg_match('/^(.*?)\s*(\d+(?:\.\d+)?)\s*(ml|L|l)/i', $producto->nombre, $matches)) {
                $nombreBase = trim($matches[1]);
                $valBase = (float)$matches[2];
                $uniBase = strtolower($matches[3]);
                $enLitros = ($uniBase == 'ml') ? $valBase / 1000 : $valBase;
            } else {
                $nombreBase = trim($producto->nombre);
                $enLitros = 1.0; // Valor por defecto si no tiene formato estándar
            }

            // Si el nombre base no existe en el array o si este registro tiene menor cantidad en litros, lo priorizamos
            if (!isset($agrupados[$nombreBase]) || $enLitros < $agrupados[$nombreBase]['litros_base']) {
                $agrupados[$nombreBase] = [
                    'producto' => $producto,
                    'litros_base' => $enLitros
                ];
            }
        }

        // Devolvemos la lista limpia de objetos de producto ordenados por ID para mantener consistencia
        return collect(array_column($agrupados, 'producto'))->sortBy('id_producto')->values()->all();
    }

    public function handle(Request $request)
    {
        $rawMessage = trim($request->json('message'));
        $userMessage = str_replace(',', '.', $rawMessage);
        $idConversacion = $request->json('id_conversacion');

        $conversacion = null;
        if ($idConversacion && $idConversacion != "null") {
            $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->first();
        }

        if (!$conversacion || $conversacion->estado != 'ACTIVA') {
            $idConversacion = DB::table('conversaciones_chatbot')->insertGetId([
                'id_usuario'  => auth()->id(),
                'paso_actual' => 'INICIO',
                'estado'      => 'ACTIVA',
                'iniciada_en' => now()
            ], 'id_conversacion');
            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
            $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->first();
        }

        $this->registrarLog($idConversacion, 'usuario', $userMessage);
        $estadoActual = $conversacion->paso_actual;

        if (in_array(strtolower($userMessage), ['hola', 'menu', 'inicio', 'volver'])) {
            if ($estadoActual != 'INICIO' && $conversacion->estado == 'ACTIVA') {
                $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                
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

        switch ($estadoActual) {
            case 'INICIO':
            case 'ESPERANDO_MENU':
                if ($userMessage == '1') {
                    session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
                    $nuevoEstado = 'SOLICITAR_PRODUCTO';
                } elseif ($userMessage == '2') {
                    $nuevoEstado = 'ESPERANDO_FAQ';
                } elseif ($userMessage == '3') {
                    $this->finalizarConversacion($idConversacion, 'DERIVADA');
                    $reply = 'Redirigiendo con un asesor de Rayo Verde... Por favor, espera un momento.';
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    return response()->json([
                        'reply' => $reply, 
                        'redirect' => route('chat.espera', ['id' => $idConversacion])
                    ]);                    
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
                    return response()->json([
                        'reply' => $reply, 
                        'redirect' => route('chat.espera', ['id' => $idConversacion])
                    ]);
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
                    return response()->json([
                        'reply' => $reply, 
                        'redirect' => route('chat.espera', ['id' => $idConversacion])
                    ]);
                } else {
                    $nuevoEstado = 'INICIO';
                }
                break;

            case 'SOLICITAR_PRODUCTO':
                $productos = $this->obtenerProductosCatalogo(); // <--- Aquí usamos el catálogo filtrado sin duplicados
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

                // Regla existente: No cotizar cantidades menores al volumen original del precio mostrado
                if ($userEnLitros < ($baseEnLitros - 0.0001)) {
                    $nuevoEstado = 'ERROR_CANTIDAD_MINIMA';
                    $msgMinimo = ($uniBase == 'ml') ? "{$valBase}ml" : "{$valBase}L";
                    
                    $carrito = session('carrito_chatbot', []);
                    if (empty($carrito)) {
                        $reply = "Cantidad insuficiente. El mínimo para este producto es de *{$msgMinimo}*.\n\n" .
                                 "Tu ingreso: {$cantUser}{$uniUser}\n\n" .
                                 "¿Qué deseas hacer?\n1. Intentar con otra cantidad\n2. Cancelar y volver al menú principal";
                    } else {
                        $reply = "Cantidad insuficiente. El mínimo para este producto es de *{$msgMinimo}*.\n\n" .
                                 "Tu ingreso: {$cantUser}{$uniUser}\n\n" .
                                 "¿Qué deseas hacer?\n1. Intentar con otra cantidad\n2. Descartar este producto y ver resumen\n3. Cancelar toda la cotización";
                    }
                    
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->update(['paso_actual' => $nuevoEstado]);
                    return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
                }

                // NUEVA REGLA DE NEGOCIO: Calcular el descuento individual escalonado según volumen en Litros
                $descuentoPct = 0;
                if ($userEnLitros >= 50) {
                    $descuentoPct = 15;
                } elseif ($userEnLitros >= 20) {
                    $descuentoPct = 10;
                } elseif ($userEnLitros >= 5) {
                    $descuentoPct = 5;
                }

                // Cálculo del precio base por litro, escalado por la cantidad solicitada
                $subtotalOriginal = ($prod['precio'] / $baseEnLitros) * $userEnLitros;
                // Aplicamos el porcentaje correspondiente
                $subtotalConDescuento = $subtotalOriginal * (1 - ($descuentoPct / 100));

                $carrito = session('carrito_chatbot', []);
                $carrito[] = [
                    'id_prod'  => $prod['id'], 
                    'nombre'   => $prod['nombre'], 
                    'cant'     => $cantUser, 
                    'uni'      => $uniUser, 
                    'litros'   => $userEnLitros,
                    'precio_u' => ($prod['precio'] / $baseEnLitros), // guardamos costo base por litro
                    'desc_pct' => $descuentoPct,
                    'sub'      => $subtotalConDescuento
                ];
                session(['carrito_chatbot' => $carrito]);
                $nuevoEstado = 'PREGUNTAR_BUCLE';
                break;

            case 'ERROR_CANTIDAD_MINIMA':
                $carrito = session('carrito_chatbot', []);
                
                if ($userMessage == '1') {
                    $nuevoEstado = 'SOLICITAR_CANTIDAD';
                } elseif ($userMessage == '2') {
                    $clearSessionKeys = ['temp_prod', 'temp_unidad'];
                    session()->forget($clearSessionKeys);
                    if (!empty($carrito)) {
                        $nuevoEstado = 'MOSTRAR_RESUMEN';
                    } else {
                        $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                        $reply = "❌ Cotización cancelada correctamente. Volviendo al menú principal...\n\n" .
                                 "¡Hola! Soy el asistente de Rayo Verde. Selecciona una opción:\n1. Nueva Cotización\n2. Preguntas Frecuentes\n3. Hablar con un Asesor";
                        $this->registrarLog($idConversacion, 'bot', $reply);
                        return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
                    }
                } elseif ($userMessage == '3' && !empty($carrito)) {
                    session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
                    $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                    $reply = "❌ Cotización cancelada correctamente. Volviendo al menú principal...\n\n" .
                             "¡Hola! Soy el asistente de Rayo Verde. Selecciona una opción:\n1. Nueva Cotización\n2. Preguntas Frecuentes\n3. Hablar con un Asesor";
                    $this->registrarLog($idConversacion, 'bot', $reply);
                    return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
                } else {
                    $nuevoEstado = 'ERROR_CANTIDAD_MINIMA';
                }
                break;

            case 'PREGUNTAR_BUCLE':
                $nuevoEstado = ($userMessage == '1') ? 'SOLICITAR_PRODUCTO' : 'MOSTRAR_RESUMEN';
                break;

            case 'MOSTRAR_RESUMEN':
                if ($userMessage == '1') {
                    return $this->finalizarCotizacion($idConversacion);
                }
                
                $this->finalizarConversacion($idConversacion, 'ABANDONADA');
                session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
                
                $reply = "Cotización cancelada correctamente. Volviendo al menú principal...\n\n" .
                         "¡Hola! Soy el asistente de Rayo Verde. Selecciona una opción:\n1. Nueva Cotización\n2. Preguntas Frecuentes\n3. Hablar con un Asesor";
                
                $this->registrarLog($idConversacion, 'bot', $reply);
                return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
        }

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
                $prods = $this->obtenerProductosCatalogo(); // <--- Mostramos catálogo único al usuario
                $reply = "Selecciona un producto:\n";
                foreach ($prods as $i => $p) $reply .= ($i + 1) . ". " . $p->nombre . " (Bs. " . $p->precio . ")\n";
            } 
            elseif ($nuevoEstado == 'SOLICITAR_UNIDAD') {
                $reply = "¿En que unidad deseas cotizar?\n1. Mililitros (ml)\n2. Litros (L)";
            }
            elseif ($nuevoEstado == 'SOLICITAR_CANTIDAD') {
                $uni = session('temp_unidad');
                $reply = "Indica la cantidad en " . $uni . ":\n(Ej: " . ($uni == 'L' ? "1.5" : "250") . ")";
            }
            elseif ($nuevoEstado == 'PREGUNTAR_BUCLE') {
                $reply = "¿Deseas añadir otro producto?\n1. Si, añadir otro\n2. No, finalizar y ver resumen";
            }
            elseif ($nuevoEstado == 'ERROR_CANTIDAD_MINIMA') {
                $prodTemp = session('temp_prod');
                preg_match('/(\d+(?:\.\d+)?)\s*(ml|L|l)/i', $prodTemp['nombre'] ?? '', $matches);
                $valBase = isset($matches[1]) ? (float)$matches[1] : 1;
                $uniBase = isset($matches[2]) ? strtolower($matches[2]) : 'ml';
                $msgMinimo = ($uniBase == 'ml') ? "{$valBase}ml" : "{$valBase}L";
                
                if (empty(session('carrito_chatbot', []))) {
                    $reply = "Opción no válida. El mínimo para este producto es de *{$msgMinimo}*.\n\n¿Qué deseas hacer?\n1. Intentar con otra cantidad\n2. Cancelar y volver al menú principal";
                } else {
                    $reply = "Opción no válida. El mínimo para este producto es de *{$msgMinimo}*.\n\n¿Qué deseas hacer?\n1. Intentar con otra cantidad\n2. Descartar este producto y ver resumen\n3. Cancelar toda la cotización";
                }
            }
            elseif ($nuevoEstado == 'MOSTRAR_RESUMEN') {
                $carrito = session('carrito_chatbot', []);
                $res = "RESUMEN DE COTIZACION\n\n";
                $total = array_sum(array_column($carrito, 'sub'));
                foreach ($carrito as $c) {
                    $res .= "- {$c['nombre']}\n   {$c['cant']} {$c['uni']} -> Bs. " . number_format($c['sub'], 2);
                    if ($c['desc_pct'] > 0) {
                        $res .= " (*¡{$c['desc_pct']}% Descuento Vol.*)";
                    }
                    $res .= "\n\n";
                }
                $reply = $res . "----------\nTOTAL: Bs. " . number_format($total, 2) . "\n\n1. Confirmar Cotizacion y Elevar a Gerencia para confirmar adquisicion \n2. Cancelar todo";
            }
            else {
                $reply = "Selecciona una opción:";
            }
        }

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
                    'descuento_aplicado' => 0, // Como es individual por producto, el global queda en 0 o vacío
                    'subtotal' => $total,
                    'total' => $total,
                    'generado_en' => now()
                ], 'id_cotizacion');

                foreach ($carrito as $item) {
                    DB::table('detalle_cotizaciones')->insert([
                        'id_cotizacion'   => $cotId,
                        'id_producto'     => $item['id_prod'],
                        'volumen_litros'  => (float)$item['litros'],
                        'precio_unitario' => (float)$item['precio_u'],
                        'descuento_pct'   => (float)$item['desc_pct'], // <--- Guardamos el descuento correspondiente al tramo
                        'subtotal'        => (float)$item['sub']
                    ]);
                }

                $this->finalizarConversacion($id, 'FINALIZADA');
            });

            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
            $this->registrarLog($id, 'bot', "Cotización guardada exitosamente.");
            
            return response()->json([
                'reply' => "Cotización guardada correctamente.\n\nEsta propuesta ha sido elevada a nuestra Gerencia para su validación técnica y comercial."
            ]);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error técnico, intente más tarde.', 'id_conversacion' => $id]);
        }
    }
}