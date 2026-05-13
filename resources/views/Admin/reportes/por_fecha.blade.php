@extends('layouts.admin-sidebar')

@section('title', 'Reporte por Fecha')
@section('breadcrumb', 'Reportes / Por Fecha')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Reporte por Fecha</h1>
    
    <div class="bg-white p-4 rounded shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha Fin</label>
                <input type="date" id="fecha_fin" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex gap-2 items-end">
                <button id="btn-consultar" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-search"></i> Consultar
                </button>
                
            </div>
        </div>
    </div>
    
   <div class="bg-white rounded shadow p-4" style="overflow-x: auto; overflow-y: visible;">
    <h3 class="text-lg font-semibold mb-3 text-center">Evolución de Cotizaciones</h3>
    <div style="min-height: 400px; width: 100%;">
        <canvas id="chartEvolucion" style="height: 400px; width: 100%;"></canvas>
    </div>
    <p id="sin-datos" class="text-center text-gray-500 hidden mt-4">No hay datos en el rango seleccionado</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart;

function cargarGrafico() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    let url = '/admin/reportes/filtrado-data';
    const params = new URLSearchParams();
    if (fechaInicio) params.append('fecha_inicio', fechaInicio);
    if (fechaFin) params.append('fecha_fin', fechaFin);
    if (params.toString()) url += '?' + params.toString();
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.evolucion && data.evolucion.length > 0) {
                document.getElementById('sin-datos').classList.add('hidden');
                const fechas = data.evolucion.map(e => e.fecha);
                const totales = data.evolucion.map(e => e.total);
                
                const ctx = document.getElementById('chartEvolucion').getContext('2d');
                if (chart) chart.destroy();
                
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: fechas,
                        datasets: [{
                            label: 'Cotizaciones',
                            data: totales,
                            borderColor: '#4CAF50',
                            backgroundColor: 'rgba(76,175,80,0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#2d7a2d',
                            pointBorderColor: '#fff',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Cantidad de Cotizaciones' } },
                            x: { title: { display: true, text: 'Fecha' } }
                        }
                    }
                });
            } else {
                document.getElementById('sin-datos').classList.remove('hidden');
                if (chart) chart.destroy();
                chart = null;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('sin-datos').classList.remove('hidden');
        });
}

// Exportar Excel
document.getElementById('btn-excel')?.addEventListener('click', () => {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    window.location.href = `/admin/reportes/exportar.excel?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
});

// Exportar PDF
document.getElementById('btn-pdf')?.addEventListener('click', () => {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    window.location.href = `/admin/reportes/exportar.pdf?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
});

// Configurar fechas por defecto (últimos 30 días)
const hoy = new Date();
const hace30Dias = new Date();
hace30Dias.setDate(hoy.getDate() - 30);
document.getElementById('fecha_inicio').valueAsDate = hace30Dias;
document.getElementById('fecha_fin').valueAsDate = hoy;

// Evento del botón consultar
document.getElementById('btn-consultar').addEventListener('click', cargarGrafico);

// Cargar gráfico al inicio
cargarGrafico();
</script>
@endsection