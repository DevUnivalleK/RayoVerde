<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('producto')
                       ->orderByDesc('fecha')
                       ->get();

        $totalVentas   = $ventas->sum('monto');
        $totalRegistros = $ventas->count();

        $productos = Producto::orderBy('nombre')->get();

        return view('Admin.Ventas.index', compact(
            'ventas',
            'totalVentas',
            'totalRegistros',
            'productos'
        ));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'monto'       => 'required|numeric|min:0',
            'fecha'       => 'nullable|date',
        ]);

        $data['fecha'] = $data['fecha'] ?? now();

        Venta::create($data);

        return redirect()->route('admin.ventas.index')
                         ->with('success', 'Venta registrada correctamente.');
    }
}