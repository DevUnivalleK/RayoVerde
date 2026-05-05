<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde | Aceites Orgánicos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <style>
        /* --- ESTILOS VISUALES PREMIUM --- */
        body { background-color: #fcfdfc; }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Hero Section con enfoque Orgánico */
        .hero-container {
            background: linear-gradient(135deg, #f0f7f2 0%, #ffffff 100%); /* Tonos más naturales/verdes */
            border: 1px solid rgba(39, 174, 96, 0.1) !important;
            position: relative;
            overflow: hidden;
        }

        .product-card-destacado {
            border: none !important;
            border-radius: 20px !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #ffffff;
        }

        .product-card-destacado:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }

        .footer-main {
            background: #1a1a1a;
            color: #ffffff;
            padding: 60px 0 30px;
            margin-top: 80px;
        }
        .footer-main a { color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s; }
        .footer-main a:hover { color: var(--verde-acento); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="color: var(--verde-primario); font-size: 1.5rem;">RAYO VERDE</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex mx-auto w-50 my-2 my-lg-0">
                    <div class="input-group">
                        <input class="form-control rounded-start-pill border-end-0" type="search" placeholder="Buscar aceites orgánicos..." aria-label="Search">
                        <button class="btn btn-outline-success rounded-end-pill border-start-0" type="submit" style="border-color: #dee2e6; color: var(--verde-primario);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link active fw-semibold" href="{{ url('/') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#">Productos</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#">Sobre Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-shopping-cart text-dark"></i></a></li>
                                                        
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn p-2" href="#" id="profileMenu" role="button" data-bs-toggle="dropdown" style="background-color: var(--verde-fondo-claro); border-radius: 50px;">
                            <i class="fas fa-user-circle fs-4" style="color: var(--verde-primario);"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2"></i> Mis Pedidos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="#" id="btnAbrirNotificaciones">
                                    <span><i class="fas fa-bell me-2"></i> Notificaciones</span>
                                    <span id="notif-count-index" class="badge rounded-pill bg-danger">3</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="{{ url('/login') }}"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="hero-container p-5 mb-4 rounded-5 shadow-sm border">
            <div class="row align-items-center py-5">
                <div class="col-md-7">
                    <h1 class="display-5 fw-bold" style="color: var(--verde-primario);">Bienvenido a Rayo Verde</h1>
                    <p class="fs-4 text-secondary">La esencia de lo natural en tu mesa. Descubre nuestra selección de aceites orgánicos premium y gestiona tus pedidos fácilmente.</p>
                    <button class="btn btn-lg text-white px-5 rounded-pill shadow-sm" type="button" style="background-color: var(--verde-acento);">Ver Productos</button>
                </div>
                <div class="col-md-5 text-center mt-4 mt-md-0">
                    <img src="https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?q=80&w=400&h=300&auto=format&fit=crop" alt="Aceites Orgánicos Rayo Verde" class="img-fluid rounded-4 shadow-sm">
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0" style="color: var(--gris-oscuro);">Nuestros Aceites Destacados</h3>
                <a href="#" class="text-success text-decoration-none fw-bold">Ver catálogo completo <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="card h-100 shadow-sm product-card-destacado p-3 text-center">
                        <div class="bg-light rounded-4 p-3 mb-3">
                            <img src="https://via.placeholder.com/150" class="img-fluid" style="height: 120px; object-fit: contain;" alt="Aceite Orgánico 1">
                        </div>
                        <h6 class="fw-bold mb-1">Aceite Orgánico 1</h6>
                        <p class="small text-muted mb-2">Prensado en Frío</p>
                        <span class="fs-5 fw-bold text-success">45.00 BOB</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100 shadow-sm product-card-destacado p-3 text-center">
                        <div class="bg-light rounded-4 p-3 mb-3">
                            <img src="https://via.placeholder.com/150" class="img-fluid" style="height: 120px; object-fit: contain;" alt="Aceite Orgánico 2">
                        </div>
                        <h6 class="fw-bold mb-1">Aceite Orgánico 2</h6>
                        <p class="small text-muted mb-2">100% Natural</p>
                        <span class="fs-5 fw-bold text-success">38.00 BOB</span>
                    </div>
                </div>
                </div>
        </div>
    </main>

    <footer class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-success">RAYO VERDE</h5>
                    <p class="small opacity-75 mt-3">Comprometidos con la salud y el medio ambiente a través de aceites orgánicos de la mejor calidad.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold">Navegación</h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="#" class="small">Inicio</a></li>
                        <li><a href="#" class="small">Productos</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold">Contacto</h6>
                    <ul class="list-unstyled mt-3">
                        <li class="small opacity-75 mb-2"><i class="fas fa-map-marker-alt me-2"></i> Bolivia</li>
                    </ul>
                </div>
                <div class="col-md-3 text-md-end">
                    <h6 class="fw-bold">Síguenos</h6>
                    <div class="mt-3 fs-4">
                        <i class="fab fa-facebook me-3"></i>
                        <i class="fab fa-instagram"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="notif-panel-index" class="notif-panel-contextual shadow-lg border-0">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light" style="border-radius: 15px 15px 0 0;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-bell me-2 text-success"></i>Mis Notificaciones</h6>
            <button type="button" class="btn-close" onclick="closeNotifPanelIndex()" aria-label="Close"></button>
        </div>
        <div class="notif-list-index" id="notif-list-index">
             </div>
    </div>

    <div class="chatbot-floating-btn" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
        <span>ChatBot</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notificacion.js') }}"></script>
</body>
</html>