<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cotizaciones</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; border-bottom: 2px solid #006c0f; padding-bottom: 20px; }
        .header h1 { color: #006c0f; }
        .info { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #006c0f; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAYO VERDE</h1>
        <p>Reporte de Cotizaciones</p>
    </div>
    
    <div class="info">
        <p><strong>Período:</strong> {{ $fechaInicio }} al {{ $fechaFin }}</p>
        <p><strong>Total Cotizaciones:</strong> {{ $cotizaciones->count() }}</p>
        <p><strong>Total Ventas:</strong> ${{ $cotizaciones->sum('subtotal') }}</p>
    </div>
    
    <table>
        <thead>
            <tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Subtotal</th><th>Total</th><th>Estado</th></tr>
        </thead>
        <tbody>
            @foreach($cotizaciones as $c)
            <tr>
                <td>{{ $c->codigo }}</td>
                <td>{{ $c->generado_en }}</td>
                <td>{{ $c->cliente->empresa ?? 'N/A' }}</td>
                <td>${{ $c->subtotal }}</td>
                <td>${{ $c->total }}</td>
                <td>{{ $c->estado->nombre_estado ?? 'Pendiente' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Reporte generado el {{ now() }}</p>
    </div>
</body>
</html>
