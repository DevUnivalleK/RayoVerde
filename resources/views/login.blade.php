<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde | Iniciar Sesión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #f0f7f2 0%, #d9e8df 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 30px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .form-label {
            color: #4a4a4a;
            font-size: 0.9rem;
            margin-left: 5px;
        }

        .input-group-text {
            border-radius: 15px 0 0 15px !important;
            border-color: #e2e8f0;
            color: var(--verde-primario);
        }

        .form-control {
            border-radius: 0 15px 15px 0 !important;
            border-color: #e2e8f0;
            padding: 12px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            border-color: var(--verde-primario);
        }

        .btn-login {
            background: linear-gradient(90deg, var(--verde-primario), var(--verde-acento));
            border: none;
            color: white;
            border-radius: 15px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.2);
            filter: brightness(1.1);
            color: white;
        }

        .brand-logo {
            font-size: 2rem;
            color: var(--verde-primario);
            letter-spacing: -1px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                <div class="card login-card shadow-lg">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-5">
                            <h1 class="brand-logo fw-bold mb-0">RAYO VERDE</h1>
                            <div style="width: 40px; height: 3px; background: var(--verde-acento); margin: 8px auto;"></div>
                            <p class="text-muted small mt-3">Accede a tu cuenta de aceites orgánicos</p>
                        </div>
                        
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-envelope-open opacity-50"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0" placeholder="tu@correo.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label fw-semibold">Contraseña</label>
                                    <a href="#" class="small text-decoration-none" style="color: var(--verde-primario);">¿Olvidaste tu contraseña?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-key opacity-50"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100 mb-4">
                                INICIAR SESIÓN
                            </button>
                            
                            <div class="text-center">
                                <span class="text-muted small">¿Eres nuevo en Rayo Verde?</span> <br>
                                <a href="{{ url('/registro') }}" class="fw-bold small" style="color: var(--verde-acento); text-decoration: none;">Crea una cuenta aquí</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-secondary small text-decoration-none">
                        <i class="fas fa-chevron-left me-1" style="font-size: 0.7rem;"></i> Volver a la página principal
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>