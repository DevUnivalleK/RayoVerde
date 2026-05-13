<style>
    :root {
        --verde-primario: #27ae60;
        --verde-acento: #2ecc71;
    }

    body {
        background: linear-gradient(135deg, #f0f7f2 0%, #d9e8df 100%);
        height: 100vh;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        align-items: center;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
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
        margin-bottom: 25px;
        display: block;
    }

    .form-group {
        text-align: left;
        margin-bottom: 20px;
    }

    .form-label {
        color: #4a4a4a;
        font-size: 0.9rem;
        margin-left: 5px;
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        border-radius: 15px !important; /* Ajustado para que no dependa de input-group */
        border: 1px solid #e2e8f0;
        padding: 12px;
        font-size: 0.95rem;
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
        margin-top: 10px;
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

    .footer-text a {
        color: var(--verde-primario);
        text-decoration: none;
        font-weight: 600;
    }
</style>

<div class="login-card">
    <span class="brand-logo">Rayo Verde</span>
    <h2 style="font-size: 1.2rem; color: #2d3748; margin-bottom: 30px;">Iniciar Sesión</h2>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf 
        <div class="form-group">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required placeholder="ejemplo@correo.com">
          
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
            @error('correo')
            <span style="color: #e53e3e; font-size: 0.75rem; margin-top: 5px; display: block;">
            {{ $message }}
        </span>
    @enderror
        </div>

        <button type="submit" class="btn-login">INGRESAR</button>
    </form>

    <p class="footer-text">
        ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a>
    </p>
    <p class="footer-text">
        ¿Olvidaste la contraseña? <a href="{{ route('password') }}">Cambiar Contraseña</a>
    </p>
</div>