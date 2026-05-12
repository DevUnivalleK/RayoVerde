<style>
    :root {
        --verde-primario: #27ae60;
        --verde-acento: #2ecc71;
    }

    body {
        background: linear-gradient(135deg, #f0f7f2 0%, #d9e8df 100%);
        min-height: 100vh; /* Cambiado a min-height por si el contenido crece */
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 20px; /* Espacio para móviles */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
        width: 100%;
        max-width: 500px; /* Un poco más ancho para el registro */
        padding: 40px;
        border: none;
        border-radius: 30px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        transition: transform 0.3s ease;
        text-align: center;
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    .brand-logo {
        font-size: 2rem;
        color: var(--verde-primario);
        font-weight: 800;
        margin-bottom: 10px;
        display: block;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 0;
    }

    .form-group {
        text-align: left;
        margin-bottom: 15px;
        flex: 1;
    }

    .form-label {
        color: #4a4a4a;
        font-size: 0.85rem;
        margin-left: 5px;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        border-radius: 15px !important;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.3s;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        border-color: var(--verde-primario);
    }

    .btn-login {
        width: 100%;
        background: linear-gradient(90deg, var(--verde-primario), var(--verde-acento));
        border: none;
        color: white;
        border-radius: 15px;
        padding: 12px;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        margin-top: 15px;
        text-transform: uppercase;
    }

    .btn-login:hover {
        box-shadow: 0 10px 20px rgba(39, 174, 96, 0.2);
        filter: brightness(1.1);
    }

    .footer-text {
        margin-top: 20px;
        font-size: 0.9rem;
        color: #718096;
    }

    .footer {
        margin-top: 20px;
        font-size: 0.65rem;
        color: #718096;
    }

    .footer-text a {
        color: var(--verde-primario);
        text-decoration: none;
        font-weight: 600;
    }
</style>

<div class="login-card">
    <span class="brand-logo">Rayo Verde</span>
    <h2 style="font-size: 1.2rem; color: #2d3748; margin-bottom: 25px;">Crear Cuenta</h2>

    <form action="{{ route('registrar.post') }}" method="POST">
        @csrf 

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Tu nombre">
            </div>
            <div class="form-group">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" required placeholder="Tu apellido">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" required placeholder="correo@ejemplo.com">
        </div>

        <div class="form-group">
            <label class="form-label">Empresa (Opcional)</label>
            <input type="text" name="empresa" class="form-control" required placeholder="">
        </div>

        <div class="form-group">
            <label class="form-label">Teléfono (Opcional)</label>
            <input type="text" name="telefono" class="form-control" required placeholder="">
        </div>

        <div class="form-group">
            <label class="form-label">Dirección (Opcional)</label>
            <input type="text" name="direccion" class="form-control" required placeholder="">
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required placeholder="Mínimo 8 caracteres">
        </div>

        <div class="form-group">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required placeholder="Repite tu contraseña">
        </div>

        <div class="form-group">
            <label class="form-label">Respuesta Secreta</label>
            <input type="text" name="respuesta_secreta" class="form-control" required placeholder="Escribe tu respuesta secreta">
        </div>

        <p class="footer">
            (Necesitarás la respuesta secreta en caso de que olvides tu contraseña, puede ser el nombre de tu mascota, tu equipo deportivo favorito o algo que sea fácil de recordar)
        </p>

        <button type="submit" class="btn-login">REGISTRARSE</button>
    </form>

    <p class="footer-text">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
    </p>
</div>