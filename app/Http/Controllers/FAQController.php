<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    /**
     * Muestra el listado de preguntas frecuentes desde la tabla de conocimiento.
     */
    public function index()
    {
        try {
            // Ahora apuntamos a chatbot_faqs
            $faqs = DB::table('chatbot_faqs')
                ->orderBy('categoria', 'asc')
                ->get();

            return view('admin.faq', compact('faqs'));
        } catch (\Exception $e) {
            Log::error("Error al obtener chatbot_faqs: " . $e->getMessage());
            return back()->with('error', 'No se pudieron cargar las preguntas frecuentes.');
        }
    }

    /**
     * Almacena una nueva FAQ en la base de conocimientos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:50',
            'pregunta'  => 'required|string|max:255',
            'respuesta' => 'required|string',
        ]);

        try {
            DB::table('chatbot_faqs')->insert([
                'categoria' => $request->categoria,
                'pregunta'  => $request->pregunta,
                'respuesta' => $request->respuesta,
                'creado_en' => now(), // Usamos el nombre de columna del nuevo SQL
                'contador_uso' => 0   // Inicializamos el contador solicitado en ADM-05
            ]);

            return redirect()->route('admin.faq.index')
                ->with('success', '¡Pregunta agregada a la base de conocimientos!');
        } catch (\Exception $e) {
            Log::error("Error al insertar en chatbot_faqs: " . $e->getMessage());
            return back()->with('error', 'Hubo un problema al guardar la pregunta.');
        }
    }

    /**
     * Actualiza una FAQ existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'categoria' => 'required|string',
            'pregunta'  => 'required|string',
            'respuesta' => 'required|string',
        ]);

        try {
            DB::table('chatbot_faqs')
                ->where('id_faq', $id) // Corregido el nombre de la PK a id_faq
                ->update([
                    'categoria' => $request->categoria,
                    'pregunta'  => $request->pregunta,
                    'respuesta' => $request->respuesta,
                ]);

            return redirect()->route('admin.faq.index')
                ->with('success', 'Información actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar chatbot_faqs: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar la pregunta.');
        }
    }

    /**
     * Elimina una FAQ de la base de conocimientos.
     */
    public function destroy($id)
    {
        try {
            DB::table('chatbot_faqs')
                ->where('id_faq', $id) // Corregido el nombre de la PK a id_faq
                ->delete();

            return redirect()->route('admin.faq.index')
                ->with('success', 'Pregunta eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar de chatbot_faqs: " . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar la pregunta.');
        }
    }
}