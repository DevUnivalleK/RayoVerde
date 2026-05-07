<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\HistorialPrecio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('Admin.Productos.index', compact('productos'));
    }

    public function create()
    {
        return view('Admin.Productos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'cantidad'    => 'required|integer|min:0',
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'imagen_url'  => 'nullable|string',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen_url'] = $request->file('imagen')->store('productos', 'public');
        } elseif ($request->filled('imagen_url')) {
            $data['imagen_url'] = $request->input('imagen_url');
        }

        unset($data['imagen']);
        Producto::create($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $producto  = Producto::where('id_producto', $id)->firstOrFail();
        $historial = HistorialPrecio::where('id_producto', $id)
                                    ->orderByDesc('cambiado_en')
                                    ->get();

        return view('Admin.Productos.form', compact('producto', 'historial'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::where('id_producto', $id)->firstOrFail();

        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'cantidad'    => 'required|integer|min:0',
            'imagen'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'imagen_url'  => 'nullable|string',
        ]);

        if ((float) $producto->precio !== (float) $data['precio']) {
            HistorialPrecio::create([
                'id_producto'     => $producto->id_producto,
                'precio_anterior' => $producto->precio,
                'precio_nuevo'    => $data['precio'],
                'motivo'          => $request->input('motivo_cambio'),
            ]);
        }

        if ($request->hasFile('imagen')) {
            if ($producto->imagen_url && !str_starts_with($producto->imagen_url, 'http')) {
                Storage::disk('public')->delete($producto->imagen_url);
            }
            $data['imagen_url'] = $request->file('imagen')->store('productos', 'public');
        } elseif ($request->filled('imagen_url')) {
            $data['imagen_url'] = $request->input('imagen_url');
        } else {
            $data['imagen_url'] = $producto->imagen_url;
        }

        unset($data['imagen']);
        $producto->update($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::where('id_producto', $id)->firstOrFail();

        if ($producto->imagen_url && !str_starts_with($producto->imagen_url, 'http')) {
            Storage::disk('public')->delete($producto->imagen_url);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto eliminado correctamente.');
    }
}