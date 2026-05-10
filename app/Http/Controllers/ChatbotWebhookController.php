<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Reemplazamos coma por punto para aceptar ingresos como 1,5
        $rawMessage = trim($request->json('message'));
        $userMessage = str_replace(',', '.', $rawMessage);
        $idConversacion = $request->json('id_conversacion');

        // 1. Inicialización
        if (!$idConversacion || $idConversacion == "null") {
            $idConversacion = DB::table('conversaciones_chatbot')->insertGetId([
                'paso_actual' => 'INICIO',
                'estado'      => 'ACTIVA',
                'iniciada_en' => now()
            ], 'id_conversacion');
            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
        }

        $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->first();
        $estadoActual = $conversacion->paso_actual;

        // 2. Comandos de Reinicio
        if (in_array(strtolower($userMessage), ['hola', 'menu', 'inicio', 'volver'])) {
            session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
            $estadoActual = 'INICIO';
        }

        $nuevoEstado = $estadoActual;

        // 3. Máquina de Estados
        switch ($estadoActual) {
            case 'INICIO':
            case 'ESPERANDO_MENU':
                if ($userMessage == '1') {
                    $nuevoEstado = 'SOLICITAR_PRODUCTO';
                } elseif ($userMessage == '2') {
                    $nuevoEstado = 'ESPERANDO_FAQ';
                } elseif ($userMessage == '3') {
                    // DERIVACIÓN A ASESOR (Persona Real)
                    return response()->json([
                        'reply' => '⏳ Redirigiendo con un asesor de Rayo Verde... Por favor, espera un momento.',
                        'redirect' => route('home') 
                    ]);
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
                if ($uniUser == 'ml') {
                    $cantUser = round($cantUser); 
                }

                preg_match('/(\d+(?:\.\d+)?)\s*(ml|L|l)/i', $prod['nombre'], $matches);
                $valBase = isset($matches[1]) ? (float)$matches[1] : 1;
                $uniBase = isset($matches[2]) ? strtolower($matches[2]) : 'ml';

                $baseEnLitros = ($uniBase == 'ml') ? $valBase / 1000 : $valBase;
                $userEnLitros = ($uniUser == 'ml') ? $cantUser / 1000 : $cantUser;

                if ($userEnLitros < ($baseEnLitros - 0.0001)) {
                    $nuevoEstado = 'ERROR_CANTIDAD_MINIMA';
                    $msgMinimo = ($uniBase == 'ml') ? "{$valBase}ml" : "{$valBase}L";
                    
                    $reply = "⚠️ Cantidad insuficiente. El mínimo para este producto es de *{$msgMinimo}*.\n\n" .
                             "Tu ingreso: {$cantUser}{$uniUser}\n\n" .
                             "¿Qué deseas hacer?\n1. Intentar con otra cantidad (Ej: 1.5 o 500)\n2. Cancelar y volver al menú";
                    
                    DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->update(['paso_actual' => $nuevoEstado]);
                    return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
                }

                $subtotal = ($prod['precio'] / $baseEnLitros) * $userEnLitros;

                $carrito = session('carrito_chatbot', []);
                $carrito[] = [
                    'nombre' => $prod['nombre'],
                    'id_prod' => $prod['id'],
                    'cant'   => $cantUser,
                    'uni'    => $uniUser,
                    'sub'    => $subtotal
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
                session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
                $nuevoEstado = 'INICIO';
                break;
        }

        // 4. Generación de Respuesta Final
        DB::table('conversaciones_chatbot')->where('id_conversacion', $idConversacion)->update(['paso_actual' => $nuevoEstado]);
        $paso = DB::table('chatbot_pasos')->where('estado_actual', $nuevoEstado)->first();
        $reply = $paso->mensaje_bot ?? "Selecciona una opción:";

        // Actualización de mensaje para el Menú de Inicio
        if ($nuevoEstado == 'INICIO' || $nuevoEstado == 'ESPERANDO_MENU') {
            $reply = "¡Hola! Soy el asistente de Rayo Verde. Selecciona una opción:\n1. Nueva Cotización\n2. Preguntas Frecuentes\n3. Hablar con un Asesor";
        }
        elseif ($nuevoEstado == 'SOLICITAR_PRODUCTO') {
            $prods = DB::table('productos')->get();
            $reply = "🌿 *Selecciona un producto:*\n";
            foreach ($prods as $i => $p) $reply .= ($i + 1) . ". " . $p->nombre . " (Bs. " . $p->precio . ")\n";
        } 
        elseif ($nuevoEstado == 'MOSTRAR_RESUMEN') {
            $carrito = session('carrito_chatbot', []);
            $res = "📋 *RESUMEN DE COTIZACIÓN*\n\n";
            $total = 0;
            foreach ($carrito as $c) {
                $res .= "• {$c['nombre']}\n  {$c['cant']} {$c['uni']} → *Bs. " . number_format($c['sub'], 2) . "*\n\n";
                $total += $c['sub'];
            }
            $reply = $res . "━━━━━━━━━━━━━━\n*TOTAL: Bs. " . number_format($total, 2) . "*\n\n1. Confirmar y Guardar\n2. Cancelar todo";
        }
        elseif ($nuevoEstado == 'SOLICITAR_CANTIDAD') {
            $uni = session('temp_unidad');
            $reply = "Indica la cantidad que deseas en *{$uni}*:\n(Ej: " . ($uni == 'L' ? "1.5 o 1,2" : "250") . ")";
        }

        return response()->json(['reply' => $reply, 'id_conversacion' => $idConversacion]);
    }

    private function finalizarCotizacion($id) {
        $carrito = session('carrito_chatbot', []);
        if (empty($carrito)) return response()->json(['reply' => 'El carrito está vacío.', 'redirect' => route('home')]);

        $total = array_sum(array_column($carrito, 'sub'));

        DB::transaction(function () use ($total, $carrito) {
            $cotId = DB::table('cotizaciones')->insertGetId([
                'codigo' => 'COT-' . strtoupper(uniqid()),
                'id_cliente' => session('usuario_id') ?? 1,
                'id_estado' => 1,
                'total' => $total,
                'generado_en' => now()
            ], 'id_cotizacion');

            foreach ($carrito as $item) {
                DB::table('detalles_cotizaciones')->insert([
                    'id_cotizacion' => $cotId,
                    'id_producto' => $item['id_prod'],
                    'volumen_litros' => ($item['uni'] == 'ml' ? $item['cant'] / 1000 : $item['cant']),
                    'precio_unitario' => $item['sub'] / ($item['cant'] == 0 ? 1 : $item['cant']),
                    'subtotal' => $item['sub']
                ]);
            }
        });

        session()->forget(['carrito_chatbot', 'temp_prod', 'temp_unidad']);
        return response()->json(['reply' => '✅ ¡Cotización guardada exitosamente!', 'redirect' => route('home')]);
    }
}