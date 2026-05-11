<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $cotizacion->codigo }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; border-bottom: 2px solid #006c0f; padding-bottom: 20px; }
        .header h1 { color: #006c0f; margin: 0; }
        .info { margin: 30px 0; }
        .info table { width: 100%; }
        .info td { padding: 5px; }
        .productos { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .productos th, .productos td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .productos th { background: #006c0f; color: white; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAYO VERDE</h1>
        <p>Aceites Naturales - Pureza Garantizada</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Código:</strong></td>
                <td>{{ $cotizacion->codigo }}</td>
                <td><strong>Fecha:</strong></td>
                <td>{{ $cotizacion->generado_en }}</td>
            </tr>
            <tr>
                <td><strong>Cliente:</strong></td>
                <td>{{ $cotizacion->cliente->empresa ?? 'N/A' }}</td>
                <td><strong>Válido hasta:</strong></td>
                <td>{{ $cotizacion->vencimiento ?? '30 días' }}</td>
            </tr>
        </table>
    </div>

    <table class="productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Volumen (Litros)</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre ?? 'Producto' }}</td>
                <td>{{ $detalle->volumen_litros ?? 0 }}</td>
                <td>${{ number_format($detalle->precio_unitario ?? 0, 2) }}</td>
                <td>${{ number_format($detalle->subtotal ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Subtotal: ${{ number_format($cotizacion->subtotal ?? 0, 2) }}</p>
        <p>Descuento: ${{ number_format($cotizacion->descuento_aplicado ?? 0, 2) }}</p>
        <p>Envío: ${{ number_format($cotizacion->costo_envio_snapshot ?? 0, 2) }}</p>
        <p><strong>TOTAL: ${{ number_format($cotizacion->total ?? 0, 2) }}</strong></p>
    </div>

    <div class="footer">
        <p>¡Gracias por confiar en Rayo Verde!</p>
        <p>Este documento es una cotización válida por 30 días.</p>
    </div>
</body>
</html>