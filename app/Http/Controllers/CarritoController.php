<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito  = session('carrito', []);
        $items    = $this->conSubtotales($carrito);
        $total    = collect($items)->sum('subtotal');

        return view('cliente.carrito', compact('items', 'total'));
    }

    public function agregar(Request $request, $id)
    {
        $producto = Producto::where('id_producto', $id)->firstOrFail();

        if ($producto->cantidad <= 0) {
            return back()->with('error', 'Este producto está agotado.');
        }

        $carrito  = session('carrito', []);
        $cantidad = max(1, (int) $request->input('cantidad', 1));

        if (isset($carrito[$id])) {
            $nueva             = $carrito[$id]['cantidad'] + $cantidad;
            $carrito[$id]['cantidad'] = min($nueva, $producto->cantidad);
        } else {
            $carrito[$id] = [
                'id_producto' => $producto->id_producto,
                'nombre'      => $producto->nombre,
                'precio'      => (float) $producto->precio,
                'imagen_url'  => $producto->imagen_url,
                'cantidad'    => min($cantidad, $producto->cantidad),
            ];
        }

        session(['carrito' => $carrito]);
        return back()->with('success', '"' . $producto->nombre . '" añadido al carrito.');
    }

    public function quitar($id)
    {
        $carrito = session('carrito', []);
        unset($carrito[$id]);
        session(['carrito' => $carrito]);
        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return back()->with('success', 'Carrito vaciado.');
    }

    public static function contarItems(): int
    {
        return collect(session('carrito', []))->sum('cantidad');
    }

    private function conSubtotales(array $carrito): array
    {
        return collect($carrito)->map(function ($item) {
            $item['subtotal'] = round($item['precio'] * $item['cantidad'], 2);
            return $item;
        })->values()->toArray();
    }
}