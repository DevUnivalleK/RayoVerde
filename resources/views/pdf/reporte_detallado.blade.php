<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Detallado - Rayo Verde</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #006c0f; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #006c0f; margin: 0; font-size: 24px; }
        .header p { color: #666; margin: 5px 0 0; }
        .fecha { text-align: right; font-size: 12px; color: #666; margin-bottom: 20px; }
        .seccion { margin-bottom: 25px; }
        .seccion h2 { background: #006c0f; color: white; padding: 8px; font-size: 16px; margin: 0 0 10px; }
        .metricas { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .card { border: 1px solid #ddd; padding: 10px; width: 23%; text-align: center; border-radius: 5px; }
        .card .valor { font-size: 20px; font-weight: bold; color: #006c0f; }
        .card .label { font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #64b863; color: white; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .grafico-texto { font-family: monospace; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAYO VERDE</h1>
        <p>Aceites Naturales - Reporte Detallado de Cotizaciones</p>
    </div>
    
    <div class="fecha">
        <strong>Período:</strong> {{ $fechaInicio }} al {{ $fechaFin }}<br>
        <strong>Generado:</strong> {{ now() }}
    </div>
    
    <!-- Tarjetas de métricas -->
    <div class="metricas">
        <div class="card">
            <div class="valor">{{ $totales->total_cotizaciones ?? 0 }}</div>
            <div class="label">Total Cotizaciones</div>
        </div>
        <div class="card">
            <div class="valor">${{ number_format($totales->total_ventas ?? 0, 2) }}</div>
            <div class="label">Total Ventas</div>
        </div>
        <div class="card">
            <div class="valor">${{ number_format($totales->promedio ?? 0, 2) }}</div>
            <div class="label">Promedio por Venta</div>
        </div>
        <div class="card">
            <div class="valor">${{ number_format($totales->total_descuentos ?? 0, 2) }}</div>
            <div class="label">Total Descuentos</div>
        </div>
    </div>
    
    <!-- Evolución -->
    <div class="seccion">
        <h2>📈 Evolución de Cotizaciones</h2>
        <table>
            <thead>
                <tr><th>Fecha</th><th>Cantidad</th><th>Ventas</th><th>Promedio</th></tr>
            </thead>
            <tbody>
                @foreach($evolucion as $item)
                <tr>
                    <td>{{ $item->fecha }}</td>
                    <td>{{ $item->total }}</td>
                    <td>${{ number_format($item->ventas ?? 0, 2) }}</td>
                    <td>${{ number_format($item->ventas / max($item->total, 1), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Top Productos -->
    <div class="seccion">
        <h2>🏆 Top 10 Productos más Vendidos</h2>
        <table>
            <thead><tr><th>Producto</th><th>Total Ventas</th></tr></thead>
            <tbody>
                @foreach($topProductos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>${{ number_format($producto->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Top Clientes -->
    <div class="seccion">
        <h2>🏆 Top 10 Clientes</h2>
        <table>
            <thead><tr><th>Cliente</th><th>Cotizaciones</th><th>Total Compras</th></tr></thead>
            <tbody>
                @foreach($topClientes as $cliente)
                <tr>
                    <td>{{ $cliente->empresa }}</td>
                    <td>{{ $cliente->total }}</td>
                    <td>${{ number_format($cliente->compras, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Lista de Cotizaciones -->
    <div class="seccion">
        <h2>📋 Lista de Cotizaciones</h2>
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
                    <td>${{ number_format($c->subtotal, 2) }}</td>
                    <td>${{ number_format($c->total, 2) }}</td>
                    <td>{{ $c->estado->nombre_estado ?? 'Pendiente' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        Rayo Verde - Reporte generado automáticamente
    </div>
</body>
</html>
