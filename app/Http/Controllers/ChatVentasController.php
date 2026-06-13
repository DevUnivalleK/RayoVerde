<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatVentasController extends Controller
{
    public function dashboardPrincipal()
    {
        $chatsEnEspera = DB::table('conversaciones_chatbot')
            ->where('derivada_a_agente', true)
            ->where('estado', 'DERIVADA')
            ->count();

        $cotizacionesActivas = DB::table('cotizaciones')
            ->where('id_estado', 1)
            ->count();

        $atendidosHoy = DB::table('conversaciones_chatbot')
            ->whereDate('finalizada_en', Carbon::today())
            ->count();

        $chatsPendientes = DB::table('conversaciones_chatbot as c')
            ->join('usuarios as u', 'c.id_usuario', '=', 'u.id_usuario')
            ->select(
                'c.id_conversacion',
                'c.iniciada_en',
                'c.paso_actual',
                'u.correo as cliente_correo',
                DB::raw("CONCAT(u.nombre, ' ', u.apellido) as cliente_nombre")
            )
            ->where('c.derivada_a_agente', true)
            ->orderBy('c.iniciada_en', 'desc')
            ->take(5)
            ->get();

        return view('PersonalVentas.dashboard', compact('chatsEnEspera', 'cotizacionesActivas', 'atendidosHoy', 'chatsPendientes'));
    }

    public function bandeja()
    {
        return view('PersonalVentas.bandeja-chats');
    }

    public function obtenerDerivaciones()
    {
        $derivaciones = DB::table('conversaciones_chatbot as c')
            ->join('usuarios as u', 'c.id_usuario', '=', 'u.id_usuario')
            ->select(
                'c.id_conversacion',
                'c.iniciada_en',
                'c.estado',
                DB::raw("CONCAT(u.nombre, ' ', u.apellido) as cliente_nombre"),
                'u.correo as cliente_correo'
            )
            ->where('c.derivada_a_agente', true)
            ->orderBy('c.iniciada_en', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $derivaciones]);
    }

    public function vistaEsperaCliente($id)
    {
        $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $id)->first();
        return view('chatbot.chat-espera-cliente', compact('conversacion'));
    }

    public function obtenerMensajesCliente($id)
    {
        $mensajes = DB::table('mensajes_chatbot')
            ->where('id_conversacion', $id)
            ->whereIn('emisor', ['usuario', 'bot'])
            ->orderBy('enviado_en', 'asc')
            ->get();

        return response()->json($mensajes);
    }

    public function enviarMensajeCliente(Request $request, $id)
    {
        return response()->json(['success' => true]);
    }

    public function vistaAtenderAgente($id)
    {
        $conversacion = DB::table('conversaciones_chatbot as c')
            ->join('usuarios as u', 'c.id_usuario', '=', 'u.id_usuario')
            ->select('c.*', DB::raw("CONCAT(u.nombre, ' ', u.apellido) as cliente_nombre"))
            ->where('c.id_conversacion', $id)
            ->first();

        return view('PersonalVentas.chat-atender', compact('conversacion'));
    }

    public function obtenerMensajesAgente($id)
    {
        $mensajes = DB::table('mensajes_chatbot')
            ->where('id_conversacion', $id)
            ->whereIn('emisor', ['usuario', 'bot'])
            ->orderBy('enviado_en', 'asc')
            ->get();

        return response()->json(['success' => true, 'data' => $mensajes]);
    }

    public function enviarMensajeAgente(Request $request, $id)
    {
        return response()->json(['success' => true]);
    }

    public function finalizarChatAgente($id)
    {
        DB::table('conversaciones_chatbot')
            ->where('id_conversacion', $id)
            ->update([
                'derivada_a_agente' => false,
                'estado'            => 'FINALIZADA', 
                'finalizada_en'     => now()
            ]);
       
        return response()->json(['success' => true]);
    }

   public function aprobarCotizacion(Request $request, $id_cotizacion)
    {
        $cotizacion = DB::table('cotizaciones')->where('id_cotizacion', $id_cotizacion)->first();

        if (!$cotizacion) {
            return response()->json(['success' => false, 'message' => 'Cotización no encontrada en el sistema.'], 404);
        }

        $cliente = DB::table('clientes')->where('id_usuario', $cotizacion->id_usuario)->first();

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'El id_usuario (' . $cotizacion->id_usuario . ') de la cotización no tiene un perfil asociado en la tabla clientes.'], 404);
        }

        try {
            DB::transaction(function () use ($cotizacion, $cliente, $id_cotizacion) {
                DB::table('cotizaciones')
                    ->where('id_cotizacion', $id_cotizacion)
                    ->update(['id_estado' => 3]);

                DB::table('notificaciones')->insert([
                    'id_cliente'     => $cliente->id_cliente,
                    'id_cotizacion'  => $id_cotizacion,
                    'tipo'           => 'APROBADA',
                    'mensaje'        => "¡Buenas noticias! Tu cotización con código " . ($cotizacion->codigo ?? $id_cotizacion) . " por un total de Bs. " . number_format($cotizacion->total, 2) . " ha sido aprobada por el área de ventas. Haz clic aquí para confirmar y generar tu orden de pago.",
                    'leida'          => false,
                    'enviada_en'     => now()
                ]);
            });

            return response()->json(['success' => true, 'message' => 'Cotización aprobada con éxito. Notificación enviada al cliente.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()], 500);
        }
    }

    public function rechazarCotizacion(Request $request, $id_cotizacion)
    {
        $cotizacion = DB::table('cotizaciones')->where('id_cotizacion', $id_cotizacion)->first();

        if (!$cotizacion) {
            return response()->json(['success' => false, 'message' => 'Cotización no encontrada en el sistema.'], 404);
        }

        $cliente = DB::table('clientes')->where('id_usuario', $cotizacion->id_usuario)->first();

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'El id_usuario (' . $cotizacion->id_usuario . ') de la cotización no tiene un perfil asociado en la tabla clientes.'], 404);
        }

        try {
            DB::transaction(function () use ($cotizacion, $cliente, $id_cotizacion) {
                DB::table('cotizaciones')
                    ->where('id_cotizacion', $id_cotizacion)
                    ->update(['id_estado' => 2]);

                DB::table('notificaciones')->insert([
                    'id_cliente'     => $cliente->id_cliente,
                    'id_cotizacion'  => $id_cotizacion,
                    'tipo'           => 'RECHAZADA',
                    'mensaje'        => "Tu cotización con código " . ($cotizacion->codigo ?? $id_cotizacion) . " no pudo ser aprobada en esta ocasión debido a que no cumple con las políticas comerciales vigentes. Si tienes dudas, ponte en contacto con soporte técnico.",
                    'leida'          => false,
                    'enviada_en'     => now()
                ]);
            });

            return response()->json(['success' => true, 'message' => 'Cotización rechazada. El cliente ha sido notificado.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()], 500);
        }
    }

    public function leerNotificacion($id_notificacion)
{
    $notificacion = DB::table('notificaciones')->where('id_notificacion', $id_notificacion)->first();

    if (!$notificacion) {
        return redirect()->back()->with('error', 'Notificación no encontrada.');
    }

    if ($notificacion->leida) {
        return redirect()->route('cliente.carrito')->with('info', 'Esta orden ya fue procesada anteriormente.');
    }

    try {
        DB::transaction(function () use ($notificacion, $id_notificacion) {
            // 1. Marcar notificación como leída
            DB::table('notificaciones')
                ->where('id_notificacion', $id_notificacion)
                ->update(['leida' => true]);

            if ($notificacion->tipo === 'APROBADA' && !is_null($notificacion->id_cotizacion)) {
                
                $cotizacion = DB::table('cotizaciones')->where('id_cotizacion', $notificacion->id_cotizacion)->first();
                $cliente = DB::table('clientes')->where('id_cliente', $notificacion->id_cliente)->first();

                if ($cotizacion && $cliente) {
                    $detalles = DB::table('detalle_cotizaciones')
                        ->join('productos', 'detalle_cotizaciones.id_producto', '=', 'productos.id_producto')
                        ->where('detalle_cotizaciones.id_cotizacion', $notificacion->id_cotizacion)
                        ->select('detalle_cotizaciones.*', 'productos.nombre', 'productos.imagen_url')
                        ->get();
                    
                    // A. PREPARAR DATOS PARA SESIÓN Y TABLA
                    $carritoEstructurado = [];
                    foreach ($detalles as $linea) {
                        $carritoEstructurado[$linea->id_producto] = [
                            'id_producto' => $linea->id_producto,
                            'nombre'      => $linea->nombre,
                            'precio'      => (float)$linea->precio_unitario,
                            'imagen_url'  => $linea->imagen_url,
                            'cantidad'    => (int)$linea->volumen_litros,
                        ];
                    }

                    // B. INYECTAR EN LA SESIÓN (Para que el CarritoController lo vea)
                    session(['carrito' => $carritoEstructurado]);

                    // C. PERSISTIR EN TABLA (Tu respaldo histórico)
                    $existePedido = DB::table('pedidos_pendientes')
                        ->where('id_cliente', $cliente->id_cliente)
                        ->where('codigo', 'PED-' . ($cotizacion->codigo ?? $cotizacion->id_cotizacion))
                        ->exists();

                    if (!$existePedido) {
                        DB::table('pedidos_pendientes')->insert([
                            'id_cliente'     => $cliente->id_cliente,
                            'codigo'         => 'PED-' . ($cotizacion->codigo ?? $cotizacion->id_cotizacion),
                            'total'          => $cotizacion->total,
                            'nombre_titular' => $cliente->empresa ?? 'Cliente Rayo Verde',
                            'banco'          => 'Pendiente Selección',
                            'carrito'        => json_encode($carritoEstructurado),
                            'estado'         => 'esperando',
                            'created_at'     => now(),
                            'updated_at'     => now()
                        ]);
                    }
                }
            }
        });

        return redirect()->route('cliente.carrito')->with('success', 'Pedido cargado correctamente. Procede con tu pago.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al procesar la confirmación: ' . $e->getMessage());
    }
}
}