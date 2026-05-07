<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatbotWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $start = microtime(true);
        $userMessage = trim($request->json('message'));
        $idConversacion = $request->json('id_conversacion');

        try {
            // 1. Gestión de Sesión (Crear si no existe)
            if (!$idConversacion) {
                $idConversacion = DB::table('conversaciones_chatbot')->insertGetId([
                    'paso_actual' => 'INICIO',
                    'estado'      => 'ACTIVA',
                    'iniciada_en' => now()
                ], 'id_conversacion');
            }

            // 2. FORZAR REINICIO (Útil para tus pruebas)
            // Si el usuario saluda, lo mandamos al INICIO siempre.
            $keywordsReinicio = ['hola', 'menu', 'inicio', 'buenos dias'];
            if (in_array(strtolower($userMessage), $keywordsReinicio)) {
                DB::table('conversaciones_chatbot')
                    ->where('id_conversacion', $idConversacion)
                    ->update(['paso_actual' => 'INICIO']);
                $estadoActual = 'INICIO';
            } else {
                $conversacion = DB::table('conversaciones_chatbot')
                    ->where('id_conversacion', $idConversacion)
                    ->first();
                $estadoActual = $conversacion ? $conversacion->paso_actual : 'INICIO';
            }

            // --- LÓGICA DE FAQ ---
            if ($estadoActual == 'ESPERANDO_FAQ') {
                if (strtolower($userMessage) == 'volver') {
                    $estadoActual = 'INICIO'; // Cambiamos localmente para que el código de abajo responda el inicio
                } else {
                    $faq = DB::table('chatbot_faqs')
                        ->where('pregunta', 'ILIKE', "%{$userMessage}%")
                        ->first();

                    if ($faq) {
                        $reply = "🔍 *Resultado:* \n" . $faq->respuesta . "\n\n_¿Tienes otra duda o escribe 'Volver'_";
                        DB::table('chatbot_faqs')->where('id_faq', $faq->id_faq)->increment('contador_uso');
                    } else {
                        $reply = "Lo siento, no encontré información sobre eso. 😕\nIntenta con otras palabras o escribe 'Volver'.";
                    }

                    return response()->json([
                        'reply' => $reply,
                        'id_conversacion' => $idConversacion,
                        'timing' => round(microtime(true) - $start, 3)
                    ]);
                }
            }

            // 3. Obtener configuración del paso actual
            $pasoConfig = DB::table('chatbot_pasos')->where('estado_actual', $estadoActual)->first();

            if (!$pasoConfig) {
                return response()->json(['reply' => 'Estado no configurado: ' . $estadoActual]);
            }

            // 4. Lógica de Transición (Opciones 1, 2, 3)
            $nuevoEstado = $pasoConfig->estado_siguiente_default;

            if ($pasoConfig->opciones) {
                $opcionesMap = json_decode($pasoConfig->opciones, true);
                if (isset($opcionesMap[$userMessage])) {
                    $nuevoEstado = $opcionesMap[$userMessage];
                } else {
                    // Si el usuario envía algo que no es 1, 2 o 3, lo mantenemos en el mismo sitio
                    // y le repetimos el mensaje para que no se pierda.
                    $nuevoEstado = $estadoActual; 
                }
            }

            // 5. Actualizar DB
           // DB::table('conversaciones_chatbot')
             //   ->where('id_conversacion', $idConversacion)
               // ->update(['paso_actual' => $nuevoEstado]);
// 5. Actualizar el progreso en la base de datos
DB::table('conversaciones_chatbot')
    ->where('id_conversacion', $idConversacion)
    ->update(['paso_actual' => $nuevoEstado]);

// --- LÓGICA DE AUTOMATIZACIÓN DE PRUEBA ---
if ($nuevoEstado == 'FINALIZAR') {
    try {
        DB::transaction(function () use ($idConversacion) {
            // A. Crear la Cotización
            $idCotizacion = DB::table('cotizaciones')->insertGetId([
                'codigo' => 'COT-' . strtoupper(uniqid()), // Genera un código único tipo COT-645A1
                'id_cliente' => 2,          // Valor de prueba solicitado
                'id_estado' => 1,           // Suponiendo que 1 es 'Pendiente' o 'Nueva'
                'precio_por_litro' => 50.00, // Valor de prueba
                'generado_en' => now(),
                'vencimiento' => now()->addDays(5),
            ], 'id_cotizacion');

            // B. Crear la Notificación para el Admin
            DB::table('notificaciones')->insert([
                'id_cliente' => 2,          // Valor de prueba solicitado
                'id_cotizacion' => $idCotizacion,
                'tipo' => 'NUEVA_COTIZACION',
                'mensaje' => "El Chatbot generó una nueva cotización automática (#$idCotizacion).",
                'leida' => false,
                'enviada_en' => now(),
            ]);
        });
        Log::info("Cotización y Notificación creadas con éxito para la sesión: $idConversacion");
    } catch (\Exception $e) {
        Log::error("Error al generar cotización de prueba: " . $e->getMessage());
        // No detenemos el flujo del bot para que el usuario reciba su mensaje de despedida
    }
}





            // 6. Preparar Respuesta
            $proximoPaso = DB::table('chatbot_pasos')->where('estado_actual', $nuevoEstado)->first();
            $mensaje = $proximoPaso ? $proximoPaso->mensaje_bot : "Error de flujo.";
            
            // Limpiar saltos de línea para el Widget
            $mensaje = str_replace('\n', "\n", $mensaje);

            return response()->json([
                'reply' => $mensaje,
                'id_conversacion' => $idConversacion,
                'timing' => round(microtime(true) - $start, 3)
            ]);

        } catch (\Exception $e) {
            Log::error("Error Chatbot: " . $e->getMessage());
            return response()->json(['reply' => "⚠️ Error: " . $e->getMessage()], 500);
        }
    }
}