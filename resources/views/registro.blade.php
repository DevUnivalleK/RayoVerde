<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde | Crear Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body style="background-color: var(--verde-fondo-claro); min-height: 100vh; display: flex; align-items: center; padding: 40px 0;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold" style="color: var(--verde-primario);">Crea tu Cuenta</h2>
                            <p class="text-muted">Únete a Rayo Verde y gestiona tus pedidos</p>
                        </div>
                        
                        <form>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Nombre Completo</label>
                                    <input type="text" class="form-control" placeholder="Juan Pérez" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Teléfono</label>
                                    <input type="tel" class="form-control" placeholder="70000000" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Correo Electrónico</label>
                                    <input type="email" class="form-control" placeholder="juan@correo.com" required>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-bold">Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 fw-bold py-2 mb-3" style="background-color: var(--verde-primario); color: white; border-radius: 10px;">
                                Registrarse
                            </button>
                            
                            <div class="text-center">
                                <span class="text-muted">¿Ya tienes una cuenta?</span> 
                                <a href="{{ url('/login') }}" class="fw-bold" style="color: var(--verde-acento); text-decoration: none;">Inicia Sesión</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>