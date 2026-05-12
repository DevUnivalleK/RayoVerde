<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PedidoPendiente;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Venta;

class BandejaController extends Controller
{
    public function index()
    {
        // Solo los que el cliente ya confirmó (tienen confirmado_en)
        $pedidos = PedidoPendiente::with('cliente.usuario')
                                   ->where('estado', 'esperando')
                                   ->whereNotNull('confirmado_en')
                                   ->orderByDesc('confirmado_en')
                                   ->get();

        $totalEsperando = $pedidos->count();
        $totalAceptados = PedidoPendiente::where('estado', 'aceptado')->count();
        $totalRechazados= PedidoPendiente::where('estado', 'rechazado')->count();

        return view('Admin.Pedidos.bandeja',
            compact('pedidos', 'totalEsperando', 'totalAceptados', 'totalRechazados'));
    }

    public function aceptar($id)
    {
        $pedido = PedidoPendiente::findOrFail($id);

        if ($pedido->estado !== 'esperando') {
            return back()->with('error', 'Este pedido ya fue procesado.');
        }

        // 1. Crear cotizacion — estado 1 = Pendiente (pendiente de entrega)
        $cotizacion = Cotizacion::create([
            'codigo'               => $pedido->codigo,
            'id_cliente'           => $pedido->id_cliente,
            'id_estado'            => 1,
            'descuento_aplicado'   => 0,
            'costo_envio_snapshot' => 0,
            'precio_por_litro'     => 0,
            'generado_en'          => $pedido->confirmado_en,
            'subtotal'             => $pedido->total,
            'total'                => $pedido->total,
        ]);

        // 2. Crear detalles
        foreach ($pedido->carrito as $item) {
            DetalleCotizacion::create([
                'id_cotizacion'  => $cotizacion->id_cotizacion,
                'id_producto'    => $item['id_producto'],
                'volumen_litros' => $item['cantidad'],
                'precio_unitario'=> $item['precio'],
                'descuento_pct'  => 0,
                'subtotal'       => $item['subtotal'],
            ]);
        }

        // 3. Registrar en ventas
        foreach ($pedido->carrito as $item) {
            Venta::create([
                'id_producto' => $item['id_producto'],
                'monto'       => $item['subtotal'],
                'fecha'       => now(),
            ]);
        }

        // 4. Marcar pedido como aceptado
        $pedido->update([
            'estado'      => 'aceptado',
            'revisado_en' => now(),
        ]);

        return back()->with('success', 'Pedido ' . $pedido->codigo . ' aceptado y registrado.');
    }

    public function rechazar($id)
    {
        $pedido = PedidoPendiente::findOrFail($id);

        if ($pedido->estado !== 'esperando') {
            return back()->with('error', 'Este pedido ya fue procesado.');
        }

        $pedido->update([
            'estado'      => 'rechazado',
            'revisado_en' => now(),
        ]);

        return back()->with('success', 'Pedido ' . $pedido->codigo . ' rechazado.');
    }

    // Marcar como entregado — cambia estado de cotizacion a Completado (3)
    public function completar($id_cotizacion)
    {
        $cotizacion = Cotizacion::where('id_cotizacion', $id_cotizacion)->firstOrFail();
        $cotizacion->update(['id_estado' => 3]);

        return back()->with('success', 'Pedido marcado como entregado.');
    }
}