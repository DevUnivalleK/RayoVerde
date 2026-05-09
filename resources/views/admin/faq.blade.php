<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rayo Verde | Gestión de FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body class="bg-light">

    <div class="d-flex">
        <div class="text-white p-3 shadow" style="width: 280px; min-height: 100vh; background-color: var(--verde-primario); position: sticky; top: 0;">
            <div class="text-center mb-4 pt-3">
                <h4 class="fw-bold">RAYO VERDE</h4>
                <small class="opacity-75">Admin System</small>
            </div>
            <hr class="opacity-25">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.faq.index') }}" class="nav-link text-white sidebar-link active" style="background-color: var(--verde-acento);">
                        <i class="fas fa-question-circle me-2 text-center" style="width: 20px;"></i> FAQ
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4">
            <header class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h2 class="fw-bold mb-0" style="color: var(--verde-primario);">Preguntas Frecuentes</h2>
                    <p class="text-muted mb-0">Gestiona las respuestas automáticas del chatbot.</p>
                </div>
                <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFaq">
                    <i class="fas fa-plus me-2"></i> Nueva Pregunta
                </button>
            </header>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th>CATEGORÍA</th>
                                    <th>PREGUNTA</th>
                                    <th>RESPUESTA</th>
                                    <th class="text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faqs as $faq)
                                <tr>
                                    <td><span class="badge bg-success-subtle text-success">{{ $faq->categoria }}</span></td>
                                    <td class="fw-bold">{{ $faq->pregunta }}</td>
                                    <td class="text-truncate" style="max-width: 300px;">{{ $faq->respuesta }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" data-bs-target="#modalEditFaq"
                                                onclick="prepararEdicion({{ json_encode($faq) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.faq.destroy', $faq->id_faq) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFaq" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.faq.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Agregar FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="General">General</option>
                            <option value="Pedidos">Pedidos</option>
                            <option value="Pagos">Pagos</option>
                            <option value="Envíos">Envíos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pregunta</label>
                        <input type="text" name="pregunta" class="form-control" placeholder="¿Cómo comprar?" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Respuesta</label>
                        <textarea name="respuesta" class="form-control" rows="4" placeholder="Describe la solución..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Pregunta</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditFaq" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditFaq" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Editar FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" id="edit_categoria" class="form-select" required>
                            <option value="General">General</option>
                            <option value="Pedidos">Pedidos</option>
                            <option value="Pagos">Pagos</option>
                            <option value="Envíos">Envíos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pregunta</label>
                        <input type="text" name="pregunta" id="edit_pregunta" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Respuesta</label>
                        <textarea name="respuesta" id="edit_respuesta" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function prepararEdicion(faq) {
            // Actualizar la URL del formulario dinámicamente
            const form = document.getElementById('formEditFaq');
            form.action = `/admin/faq/${faq.id_faq}`;

            // Llenar los campos del modal
            document.getElementById('edit_categoria').value = faq.categoria;
            document.getElementById('edit_pregunta').value = faq.pregunta;
            document.getElementById('edit_respuesta').value = faq.respuesta;
        }
    </script>
</body>
</html>