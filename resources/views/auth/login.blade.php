<h2>Crear Cuenta - Rayo Verde</h2>

<form action="{{ route('login.post') }}" method="POST">
    @csrf 
    <div>
        <label>Correo Electrónico:</label><br>
        <input type="email" name="correo" required value="">
    </div>

    <div>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
    </div>

    <br>
    <button type="submit">Iniciar Sesión</button>
</form>

<p>¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a></p>