<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatVentasController extends Controller
{
    /**
     * Carga el Dashboard Principal con las métricas dinámicas de Rayo Verde
     */
    public function dashboardPrincipal()
    {
        // 1. Contar chats en espera (Derivados a agente activos hoy)
        $chatsEnEspera = DB::table('conversaciones_chatbot')
            ->where('derivada_a_agente', true)
            ->where('estado', 'DERIVADA')
            ->count();

        // 2. Contar cotizaciones activas (En estado Pendiente = 1)
        $cotizacionesActivas = DB::table('cotizaciones')
            ->where('id_estado', 1)
            ->count();

        // 3. Atendidos Hoy (Conversaciones cuya finalización se registró hoy)
        $atendidosHoy = DB::table('conversaciones_chatbot')
            ->whereDate('finalizada_en', Carbon::today())
            ->count();

        // 4. Traer las últimas 5 solicitudes de chat derivadas para la vista rápida
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

        return view('PersonalVentas.dashboard', compact(
            'chatsEnEspera', 
            'cotizacionesActivas', 
            'atendidosHoy', 
            'chatsPendientes'
        ));
    }

    /**
     * Carga la vista Blade de la bandeja de entrada para el personal de ventas
     */
    public function bandeja()
    {
        return view('PersonalVentas.bandeja-chats');
    }

    /**
     * Retorna el JSON de todas las solicitudes derivadas para el fetch asíncrono de la bandeja
     */
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

        return response()->json([
            'success' => true,
            'data' => $derivaciones
        ]);
    }


    // --- FLUJO DEL CLIENTE ---
    public function vistaEsperaCliente($id)
    {
        $conversacion = DB::table('conversaciones_chatbot')->where('id_conversacion', $id)->first();
        return view('chatbot.chat-espera-cliente', compact('conversacion'));
    }

    public function obtenerMensajesCliente($id)
    {
        // El cliente solo lee lo que él escribió o lo que le responde el agente humano (oculta al bot)
        $mensajes = DB::table('mensajes_chatbot')
            ->where('id_conversacion', $id)
            ->whereIn('emisor', ['usuario', 'agente'])
            ->orderBy('enviado_en', 'asc')
            ->get();

        return response()->json($mensajes);
    }

    public function enviarMensajeCliente(Request $request, $id)
    {
        DB::table('mensajes_chatbot')->insert([
            'id_conversacion' => $id,
            'emisor' => 'usuario',
            'contenido' => $request->message,
            'enviado_en' => now()
        ]);
        return response()->json(['success' => true]);
    }

    // --- FLUJO DEL PERSONAL DE VENTAS ---
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
        ->orderBy('enviado_en', 'asc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $mensajes
    ]);
}


    public function enviarMensajeAgente(Request $request, $id)
    {
        DB::table('mensajes_chatbot')->insert([
            'id_conversacion' => $id,
            'emisor' => 'agente',
            'contenido' => $request->message,
            'enviado_en' => now()
        ]);
        return response()->json(['success' => true]);
    }
    

    
}