<h2>Crear Cuenta - Rayo Verde</h2>

<form action="{{ route('registrar.post') }}" method="POST">
    @csrf 

    <div>
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required value="">
    </div>

    <div>
        <label>Apellido:</label><br>
        <input type="text" name="apellido" required value="">
    </div>

    <div>
        <label>Correo Electrónico:</label><br>
        <input type="email" name="correo" required value="">
    </div>

    <div>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Confirmar Contraseña:</label><br>
        <input type="password" name="password_confirmation" required>
    </div>

    <br>
    <button type="submit">Registrarse</button>
</form>

<p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>