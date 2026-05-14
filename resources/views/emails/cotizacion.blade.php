@component('mail::message')
# Cotización {{ $cotizacion->codigo }}

Estimado administrador,

Se ha generado una nueva cotización en el sistema de **Rayo Verde**.

@component('mail::table')
| Campo | Valor |
|:------|:------|
| Código | {{ $cotizacion->codigo }} |
| Total | Bs. {{ number_format($cotizacion->total, 2) }} |
| Generada | {{ \Carbon\Carbon::parse($cotizacion->generado_en)->format('d/m/Y H:i') }} |
@endcomponent

Encontrará el detalle completo en el PDF adjunto.

Gracias,<br>
**Sistema Rayo Verde**
@endcomponent