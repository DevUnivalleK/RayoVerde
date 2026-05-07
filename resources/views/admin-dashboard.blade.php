<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde | Panel Administrativo</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tus Estilos (Asegúrate de que las rutas sean correctas) -->
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-light">

    <div class="d-flex">
        <!-- SIDEBAR BASADO EN TU IMAGEN -->
        <div class="text-white p-3 shadow" style="width: 280px; min-height: 100vh; background-color: var(--verde-primario); position: sticky; top: 0;">
            <div class="text-center mb-4 pt-3">
                <h4 class="fw-bold">RAYO VERDE</h4>
                <small class="opacity-75">Admin System</small>
            </div>
            
            <hr class="opacity-25">
            
            <ul class="nav nav-pills flex-column mb-auto">
                <!-- Opción: Productos -->
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.productos.index') }}" class="nav-link text-white sidebar-link active">
    <i class="fas fa-question-circle me-2"></i> Productos
</a>
                </li>
                <!-- Opción: Envios/Regiones -->
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white sidebar-link">
                        <i class="fas fa-map-marker-alt me-2 text-center" style="width: 20px;"></i> Envios/Regiones
                    </a>
                </li>

                 <li class="nav-item mb-2">
                    <a href="{{ route('admin.ventas.index') }}" class="nav-link text-white sidebar-link active">
                       <i class="fas fa-question-circle me-2"></i> Gestion de Ventas
                    </a>
                </li>
                <!-- Opción: FAQ -->
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.faq.index') }}" class="nav-link text-white sidebar-link active">
    <i class="fas fa-question-circle me-2"></i> FAQ
</a>
                </li>
                <!-- Opción: Dashboards (Activa por defecto) -->
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white sidebar-link active" style="background-color: var(--verde-acento);">
                        <i class="fas fa-th-large me-2 text-center" style="width: 20px;"></i> Dashboards
                    </a>
                </li>
                <!-- Opción: Reportes -->
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white sidebar-link">
                        <i class="fas fa-chart-bar me-2 text-center" style="width: 20px;"></i> Reportes
                    </a>
                </li>
                <!-- Opción: Configuracion Comercial -->
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white sidebar-link">
                        <i class="fas fa-cog me-2 text-center" style="width: 20px;"></i> Config. Comercial
                    </a>
                </li>
            </ul>
            
            <hr class="opacity-25 mt-4">
            
            <div class="mt-auto pb-3">
                <a href="index.html" class="nav-link text-white opacity-75 sidebar-link">
                    <i class="fas fa-arrow-left me-2 text-center" style="width: 20px;"></i> Volver al Sitio
                </a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-grow-1 p-4">
            <!-- Header de Sección -->
            <header class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h2 class="fw-bold mb-0" style="color: var(--verde-primario);">Resumen General</h2>
                    <p class="text-muted mb-0">Bienvenido al control de mando de Rayo Verde.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-white border shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-2 text-success"></i> Administrador
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i> Editar Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ url('/login') }}"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </header>

            <!-- TARJETAS DE MÉTRICAS (Basado en el diseño pulcro) -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card admin-card border-0 shadow-sm p-3" style="border-left: 5px solid var(--verde-primario);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold">VENTAS HOY</small>
                                <h3 class="mb-0 mt-1">1,250</h3>
                            </div>
                            <div class="icon-shape bg-light rounded-circle p-3">
                                <i class="fas fa-dollar-sign text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card admin-card border-0 shadow-sm p-3" style="border-left: 5px solid var(--verde-acento);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold">PEDIDOS</small>
                                <h3 class="mb-0 mt-1">14</h3>
                            </div>
                            <div class="icon-shape bg-light rounded-circle p-3">
                                <i class="fas fa-shopping-basket text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card admin-card border-0 shadow-sm p-3" style="border-left: 5px solid #ffc107;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold">PENDIENTES</small>
                                <h3 class="mb-0 mt-1">5</h3>
                            </div>
                            <div class="icon-shape bg-light rounded-circle p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card admin-card border-0 shadow-sm p-3" style="border-left: 5px solid #0dcaf0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold">USUARIOS</small>
                                <h3 class="mb-0 mt-1">82</h3>
                            </div>
                            <div class="icon-shape bg-light rounded-circle p-3">
                                <i class="fas fa-users text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE GESTIÓN RÁPIDA -->
            <div class="card border-0 shadow-sm rounded-pulcro">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Transacciones Recientes</h5>
                    <button class="btn btn-sm btn-verde">Descargar Reporte</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="border-0">ID</th>
                                    <th class="border-0">CLIENTE</th>
                                    <th class="border-0">PRODUCTO</th>
                                    <th class="border-0">PRECIO</th>
                                    <th class="border-0">ESTADO</th>
                                    <th class="border-0 text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">#RV-552</td>
                                    <td>Maria Lopez</td>
                                    <td>Botellón 20L</td>
                                    <td>120 BOB</td>
                                    <td><span class="badge bg-success-subtle text-success border border-success">Completado</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">#RV-553</td>
                                    <td>Carlos Ruiz</td>
                                    <td>Pack Familiar</td>
                                    <td>85 BOB</td>
                                    <td><span class="badge bg-warning-subtle text-warning border border-warning">En camino</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Finales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>