<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde - Historial de Cotizaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- HEADER -->
    <div class="flex justify-between items-center border-b border-gray-300 pb-4 mb-4">
        <h1 class="text-3xl font-bold text-green-700">RAYO VERDE</h1>
        <span class="text-gray-500 text-sm">Historial de Cotizaciones</span>
    </div>

    <!-- NAVBAR -->
    <div class="flex space-x-6 mb-6 border-b pb-2">
        <a href="/" class="text-gray-500">Inicio</a>
        <a href="#" class="text-green-700 font-semibold border-b-2 border-green-700 pb-2">Mis Cotizaciones</a>
        <a href="#" class="text-gray-500 ml-auto">Cerrar Sesión</a>
    </div>

    <!-- TÍTULO -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Cotizaciones</h2>

    <!-- TABLA DE COTIZACIONES -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Subtotal</th>
                    <th class="px-4 py-3">Descuento</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-cotizaciones" class="divide-y">
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">Cargando cotizaciones...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    fetch('/cotizaciones/historial')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('tabla-cotizaciones');
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No tienes cotizaciones registradas</td></tr>';
                return;
            }
            
            tbody.innerHTML = data.data.map(cotizacion => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-mono">${cotizacion.codigo}</td>
                    <td class="px-4 py-3 text-sm">${new Date(cotizacion.generado_en).toLocaleDateString()}</td>
                    <td class="px-4 py-3 text-sm">$${cotizacion.subtotal}</td>
                    <td class="px-4 py-3 text-sm">$${cotizacion.descuento_aplicado}</td>
                    <td class="px-4 py-3 text-sm font-bold">$${cotizacion.total}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                    </td>
                    <td class="px-4 py-3 text-sm space-x-2">
                        <a href="/cotizaciones/${cotizacion.id_cotizacion}/pdf" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="/cotizaciones/${cotizacion.id_cotizacion}/excel" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            document.getElementById('tabla-cotizaciones').innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Error al cargar cotizaciones</td></tr>';
        });
</script>

</body>
</html>