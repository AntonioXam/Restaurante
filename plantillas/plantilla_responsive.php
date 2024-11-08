<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla Responsiva - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Ejemplo de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container"></div>
            <a class="navbar-brand" href="#"><i class="fas fa-utensils"></i> Restaurante</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"></div>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"></li>
                        <a class="nav-link" href="#mesas">Mesas</a>
                    </li>
                    <li class="nav-item"></li>
                        <a class="nav-link" href="#pedidos">Pedidos</a>
                    </li>
                    <li class="nav-item"></li>
                        <a class="nav-link" href="#productos">Productos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Ejemplo de Gestión de Mesas -->
        <section id="mesas" class="mb-5"></section>
            <h2 class="mb-4">Gestión de Mesas</h2>
            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
                <!-- Ejemplo Mesa Activa -->
                <div class="col"></div>
                    <div class="card h-100 mesa-card active">
                        <div class="card-body text-center"></div>
                            <i class="fas fa-chair fa-2x mb-2 text-primary"></i>
                            <h5 class="card-title">Mesa 1</h5>
                            <p class="card-text"><small class="text-muted">4 comensales</small></p>
                            <button class="btn btn-primary btn-sm w-100">Gestionar</button>
                        </div>
                    </div>
                </div>
                <!-- Ejemplo Mesa Inactiva -->
                <div class="col"></div>
                    <div class="card h-100 mesa-card inactive">
                        <div class="card-body text-center"></div>
                            <i class="fas fa-chair fa-2x mb-2 text-secondary"></i>
                            <h5 class="card-title">Mesa 2</h5>
                            <p class="card-text"><small class="text-muted">Disponible</small></p>
                            <button class="btn btn-outline-primary btn-sm w-100">Activar Mesa</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ejemplo de Gestión de Pedidos -->
        <section id="pedidos" class="mb-5"></section>
            <h2 class="mb-4">Gestión de Pedidos</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive"></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mesa</th>
                                    <th>Productos</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mesa 1</td>
                                    <td>2x Paella, 1x Ensalada</td>
                                    <td>45.90€</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                    <td></td>
                                        <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ejemplo de Gestión de Productos -->
        <section id="productos" class="mb-5"></section>
            <h2 class="mb-4">Gestión de Productos</h2>
            
            <!-- Pestañas de Categorías -->
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item"></li>
                    <a class="nav-link active" data-bs-toggle="pill" href="#entrantes">Entrantes</a>
                </li>
                <li class="nav-item"></li>
                    <a class="nav-link" data-bs-toggle="pill" href="#principales">Principales</a>
                </li>
                <li class="nav-item"></li>
                    <a class="nav-link" data-bs-toggle="pill" href="#postres">Postres</a>
                </li>
            </ul>

            <!-- Contenido de las Pestañas -->
            <div class="tab-content"></div>
                <div id="entrantes" class="tab-pane fade show active"></div>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <!-- Ejemplo de Producto -->
                        <div class="col">
                            <div class="card h-100"></div>
                                <div class="card-body">
                                    <h5 class="card-title">Ensalada César</h5>
                                    <p class="card-text">8.50€</p>
                                    <form class="mt-3"></form>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Cantidad</span>
                                            <input type="number" class="form-control" value="1" min="1">
                                            <button class="btn btn-primary" type="button">Añadir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ejemplo de Modal -->
        <div class="modal fade" id="ejemploModal" tabindex="-1"></div>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"></div>
                        <h5 class="modal-title">Modificar Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3"></div>
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control" min="1" value="1">
                            </div>
                            <div class="mb-3"></div>
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Estilos para las tarjetas de mesa */
    .mesa-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    
    .mesa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .mesa-card.active .fa-chair {
        color: #0d6efd;
    }
    
    .mesa-card.inactive .fa-chair {
        color: #6c757d;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .mesa-card .card-body {
            padding: 1rem;
        }
        
        .mesa-card .fa-2x {
            font-size: 1.5em;
        }
        
        .mesa-card .card-title {
            font-size: 1rem;
        }
        
        .mesa-card .card-text {
            font-size: 0.8rem;
        }
        
        .nav-pills .nav-link {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script></script>
    // Ejemplo de función para mostrar modal
    function mostrarModal() {
        new bootstrap.Modal(document.getElementById('ejemploModal')).show();
    }
    
    // Ejemplo de función para gestionar cantidades
    function ajustarCantidad(elemento, cambio) {
        const input = elemento.parentNode.querySelector('input');
        const nuevoValor = parseInt(input.value) + cambio;
        if (nuevoValor >= 1) {
            input.value = nuevoValor;
        }
    }
    </script>
</body>
</html>
