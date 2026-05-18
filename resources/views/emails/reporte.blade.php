<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #1a3d06; padding: 32px;">
    <h2 style="color: #3b6d11;">Reporte General — Rayo Verde</h2>
    <p>Hola <strong>{{ $usuario->nombre }}</strong>,</p>
    <p>Adjunto encontrarás el reporte general generado el <strong>{{ now()->format('d/m/Y H:i') }}</strong>.</p>
    <br>
    <p style="color: #7a8f6e; font-size: 12px;">Sistema Rayo Verde · Panel Administrativo</p>
</body>
</html>