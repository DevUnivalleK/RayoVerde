<?php
namespace App\Http\Controllers;

use App\Models\PedidoPendiente;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Paso 1: formulario de datos
    public function formulario()
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('cliente.catalogo')
                             ->with('error', 'Tu carrito está vacío.');
        }

    if (session()->has('datos_cotizacion')) {
        $total = session('datos_cotizacion.total_fijo');
        $items = $carrito; // Ya vienen con subtotales
    } else {
        $items = collect($carrito)->map(fn($i) => array_merge($i, [
            'subtotal' => round($i['precio'] * $i['cantidad'], 2)
        ]))->values();
        $total = $items->sum('subtotal');
    }
        
        return view('cliente.checkout', compact('items', 'total'));
    }

    // Paso 2: guardar y mostrar QR
    public function procesarPago(Request $request)
{
    $request->validate([
        'nombre_titular' => [
            'required', 'string', 'min:3', 'max:100',
            'regex:/^[\pL\s]+$/u', // solo letras y espacios
        ],
        'banco' => 'required|string|max:100',
    ], [
        'nombre_titular.required' => 'El nombre del titular es obligatorio.',
        'nombre_titular.min'      => 'Debe tener al menos 3 caracteres.',
        'nombre_titular.regex'    => 'Solo se permiten letras y espacios. ⚠ Un nombre incorrecto puede demorar la confirmación.',
        'banco.required'          => 'Debes indicar el banco desde el que pagarás.',
    ]);

    $carrito = session('carrito', []);
    if (empty($carrito)) {
        return redirect()->route('cliente.catalogo')
                         ->with('error', 'Tu carrito está vacío.');
    }

    $usuario = auth()->user();
    $cliente = Cliente::where('id_usuario', $usuario->id_usuario)->firstOrFail();

    // Verificamos si es una cotización para usar valores fijos
    if (session()->has('datos_cotizacion')) {
    $total = (float) session('datos_cotizacion.total_fijo');
    $codigo = session('datos_cotizacion.codigo_cotizacion'); 
    $items = collect($carrito);
    } else {
        $items = collect($carrito)->map(fn($i) => array_merge($i, [
            'subtotal' => round($i['precio'] * $i['cantidad'], 2)
        ]));
        $total = $items->sum('subtotal');
        $codigo = 'RV-' . date('Y') . '-' . strtoupper(Str::random(6));
    }

    $pedido = PedidoPendiente::create([
        'id_cliente'     => $cliente->id_cliente,
        'codigo'         => $codigo,
        'total'          => $total,
        'nombre_titular' => $request->nombre_titular,
        'banco'          => $request->banco,
        'carrito'        => $items->toArray(),
        'estado'         => 'esperando',
    ]);

    session(['pedido_codigo' => $codigo]);

    return view('cliente.pago-qr', compact('pedido', 'total'));
}

    // Paso 3: cliente confirma que pagó
    public function confirmarPago()
    {
        $codigo = session('pedido_codigo');

        if (!$codigo) {
            return redirect()->route('cliente.catalogo');
        }

        $pedido = PedidoPendiente::where('codigo', $codigo)
                                  ->where('estado', 'esperando')
                                  ->firstOrFail();

        $pedido->update(['confirmado_en' => now()]);


      session()->forget(['carrito', 'pedido_codigo', 'datos_cotizacion']);

        return view('cliente.pago-confirmado', compact('pedido'));
    }
}