
<?php
include '../sesion.php';
include '../conexion.php';

// hacer consulta a la base de datos listado de los usuarios
$sql = "SELECT * FROM usuarios WHERE rol IN ('camarero', 'encargado')";
$resultado = $conexion->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.85rem; }
            .container { padding: 10px; }
            .btn-group-sm > .btn, .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
            .table td, .table th { padding: 0.5rem; }
            .img-thumbnail { width: 40px; height: 40px; }
            .btn-back { width: 100%; margin-bottom: 20px; }
            h2 { font-size: 1.5rem; margin-bottom: 20px; }
            .btn-block {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        .actions-column { min-width: 120px; }
        .back-button { margin: 10px 0; }
        @media (max-width: 768px) {
            .btn-back { width: 100%; padding: 12px; }
        }
        @media (max-width: 768px) {
            .table-desktop {
                display: none;
            }
            .card-mobile {
                margin-bottom: 0.75rem;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .card-mobile .img-thumbnail {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 50%;
            }
            .card-mobile .card-body {
                padding: 0.75rem;
            }
            .card-mobile .info-row {
                font-size: 0.85rem;
                color: #666;
                margin: 5px 0;
            }
            .card-mobile .actions {
                margin-top: 1rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
                width: 100%;
            }
            .card-mobile .actions form {
                width: 100%;
            }
            .card-mobile .actions button {
                width: 100%;
                padding: 0.5rem;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            .card-mobile .info-row {
                padding: 0.5rem 0;
                border-bottom: 1px solid #eee;
            }
            .card-mobile .info-row:last-child {
                border-bottom: none;
                margin-bottom: 0.5rem;
            }
            .user-info {
                display: flex;
                align-items: center;
                margin-bottom: 0.5rem;
            }
            .user-details {
                margin-left: 0.75rem;
            }
            .user-name {
                font-weight: bold;
                font-size: 1rem;
                margin-bottom: 0;
            }
            .status-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            .info-row {
                padding: 0.5rem;
                border-bottom: 1px solid #eee;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .info-label {
                font-weight: 500;
                color: #666;
                min-width: 80px;
            }
            .info-content {
                flex: 1;
            }
        }
        @media (min-width: 769px) {
            .cards-mobile {
                display: none;
            }
        }
        .list-card {
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: white;
            padding: 20px;
        }
        .list-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .table {
            margin-bottom: 0;
        }
        .card-mobile .card {
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .card-mobile .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container mt-3 mt-lg-5">
        <div class="row">
            <div class="col-12">
                <a href="index.php" class="btn btn-secondary btn-block mb-4">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
            <div class="col-12">
                <div class="list-card">
                    <h2 class="h4 mb-4 text-center">Listado de Usuarios</h2>
                    <div class="table-responsive table-desktop">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>DNI</th>
                                    <th>Usuario</th>
                                    <th>Contraseña</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($usuario = $resultado->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($usuario['foto']): ?>
                                                <img src="../uploads/<?php echo $usuario['foto']; ?>" 
                                                     alt="Foto de <?php echo $usuario['nombre']; ?>"
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="../uploads/default.png" 
                                                     alt="Foto por defecto"
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $usuario['nombre']; ?></td>
                                        <td><?php echo $usuario['apellidos']; ?></td>
                                        <td><?php echo $usuario['dni']; ?></td>
                                        <td><?php echo $usuario['usuario']; ?></td>
                                        <td><?php echo $usuario['contrasena']; ?></td>
                                        <td><?php echo $usuario['estado'] ? 'Activo' : 'Suspendido'; ?></td>
                                        <td class="actions-column">
                                            <!-- Botón para suspender/activar -->
                                            <button type="button" class="btn btn-<?php echo $usuario['estado'] ? 'warning' : 'success'; ?> btn-sm"
                                                data-toggle="modal"
                                                data-target="#confirmarSuspenderModal"
                                                data-id="<?php echo $usuario['id']; ?>"
                                                data-accion="<?php echo $usuario['estado'] ? 'suspender' : 'activar'; ?>"
                                                data-nombre="<?php echo $usuario['nombre']; ?>">
                                                <?php echo $usuario['estado'] ? 'Suspender' : 'Activar'; ?>
                                            </button>
                                            <!-- Botón para eliminar -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#confirmarEliminarModal"
                                                data-id="<?php echo $usuario['id']; ?>"
                                                data-nombre="<?php echo $usuario['nombre']; ?>">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="cards-mobile">
                        <?php 
                        // Reset the result pointer
                        $resultado->data_seek(0);
                        while ($usuario = $resultado->fetch_assoc()) { ?>
                            <div class="card card-mobile">
                                <div class="card-body">
                                    <!-- Foto y nombre en la primera fila -->
                                    <div class="user-info">
                                        <div class="user-image">
                                            <?php if ($usuario['foto']): ?>
                                                <img src="../uploads/<?php echo $usuario['foto']; ?>" 
                                                     alt="Foto" class="img-thumbnail">
                                            <?php else: ?>
                                                <img src="../uploads/default.png" 
                                                     alt="Foto" class="img-thumbnail">
                                            <?php endif; ?>
                                        </div>
                                        <div class="user-details">
                                            <h6 class="user-name"><?php echo $usuario['nombre']; ?></h6>
                                            <span class="status-badge badge badge-<?php echo $usuario['estado'] ? 'success' : 'warning'; ?>">
                                                <?php echo $usuario['estado'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Información detallada -->
                                    <div class="info-row">
                                        <span class="info-label">Apellidos:</span>
                                        <span class="info-content"><?php echo $usuario['apellidos']; ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">DNI:</span>
                                        <span class="info-content"><?php echo $usuario['dni']; ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Usuario:</span>
                                        <span class="info-content"><?php echo $usuario['usuario']; ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Contraseña:</span>
                                        <span class="info-content"><?php echo $usuario['contrasena']; ?></span>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="actions">
                                        <form action="gestionar_usuario.php" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                            <input type="hidden" name="accion" value="<?php echo $usuario['estado'] ? 'suspender' : 'activar'; ?>">
                                            <button type="submit" class="btn btn-<?php echo $usuario['estado'] ? 'warning' : 'success'; ?> btn-block">
                                                <i class="fas fa-<?php echo $usuario['estado'] ? 'pause' : 'play'; ?>"></i>
                                                <span><?php echo $usuario['estado'] ? 'Suspender' : 'Activar'; ?></span>
                                            </button>
                                        </form>
                                        <form action="gestionar_usuario.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <button type="submit" class="btn btn-danger btn-block">
                                                <i class="fas fa-trash"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Modales de confirmación -->

    <!-- Modal Confirmar Suspender/Activar -->
    <div class="modal fade" id="confirmarSuspenderModal" tabindex="-1" role="dialog" aria-labelledby="confirmarSuspenderLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="gestionar_usuario.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarSuspenderLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de confirmación -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="accion" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Eliminar -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="gestionar_usuario.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarEliminarLabel">Eliminar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de confirmación -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="accion" value="eliminar">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    // ...código existente...
    $('#confirmarSuspenderModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var usuarioId = button.data('id');
        var accion = button.data('accion');
        var nombre = button.data('nombre');
        var modal = $(this);
        modal.find('.modal-title').text((accion.charAt(0).toUpperCase() + accion.slice(1)) + ' Usuario');
        modal.find('.modal-body').text('¿Está seguro que desea ' + accion + ' al usuario ' + nombre + '?');
        modal.find('input[name="id"]').val(usuarioId);
        modal.find('input[name="accion"]').val(accion);
    });

    $('#confirmarEliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var usuarioId = button.data('id');
        var nombre = button.data('nombre');
        var modal = $(this);
        modal.find('.modal-body').text('¿Está seguro que desea eliminar al usuario ' + nombre + '?');
        modal.find('input[name="id"]').val(usuarioId);
    });
    </script>
</body>
</html>
<?php
// cerrar la conexion
$conexion->close();
?>