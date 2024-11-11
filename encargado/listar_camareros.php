<?php
include '../sesion.php';
include '../conexion.php';

// hacer consulta a la base de datos listado de los camareros
$sql = "SELECT * FROM usuarios WHERE rol = 'camarero'";
$resultado = $conexion->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Camareros</title>
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
                margin-top: 0.5rem;
                display: flex;
                justify-content: flex-end;
                gap: 0.5rem;
            }
            .card-mobile .btn {
                padding: 0.25rem 0.5rem;
                font-size: 1rem;
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
        }
        @media (min-width: 769px) {
            .cards-mobile {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container mt-3 mt-lg-5">
        <div class="row">
            <div class="col-12">
                <a href="index.php" class="btn btn-secondary btn-lg back-button mb-4 btn-back">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
            <div class="col-12">
                <h2 class="h4 mb-4">Listado de Camareros</h2>
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
                            <?php while ($camarero = $resultado->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <?php if ($camarero['foto']): ?>
                                            <img src="../uploads/<?php echo $camarero['foto']; ?>" 
                                                 alt="Foto de <?php echo $camarero['nombre']; ?>"
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="../uploads/default.png" 
                                                 alt="Foto por defecto"
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $camarero['nombre']; ?></td>
                                    <td><?php echo $camarero['apellidos']; ?></td>
                                    <td><?php echo $camarero['dni']; ?></td>
                                    <td><?php echo $camarero['usuario']; ?></td>
                                    <td><?php echo $camarero['contrasena']; ?></td>
                                    <td><?php echo $camarero['estado'] ? 'Activo' : 'Suspendido'; ?></td>
                                    <td class="actions-column">
                                        <div class="btn-group btn-group-sm">
                                            <form action="gestionar_camarero.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $camarero['id']; ?>">
                                                <input type="hidden" name="accion" value="<?php echo $camarero['estado'] ? 'suspender' : 'activar'; ?>">
                                                <button type="submit" class="btn btn-<?php echo $camarero['estado'] ? 'warning' : 'success'; ?> btn-sm">
                                                    <?php echo $camarero['estado'] ? 'Suspender' : 'Activar'; ?>
                                                </button>
                                            </form>
                                            <form action="gestionar_camarero.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este camarero?');">
                                                <input type="hidden" name="id" value="<?php echo $camarero['id']; ?>">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </div>
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
                    while ($camarero = $resultado->fetch_assoc()) { ?>
                        <div class="card card-mobile">
                            <div class="card-body">
                                <!-- Foto y nombre en la primera fila -->
                                <div class="user-info">
                                    <div class="user-image">
                                        <?php if ($camarero['foto']): ?>
                                            <img src="../uploads/<?php echo $camarero['foto']; ?>" 
                                                 alt="Foto" class="img-thumbnail">
                                        <?php else: ?>
                                            <img src="../uploads/default.png" 
                                                 alt="Foto" class="img-thumbnail">
                                        <?php endif; ?>
                                    </div>
                                    <div class="user-details">
                                        <h6 class="user-name"><?php echo $camarero['nombre']; ?></h6>
                                        <span class="status-badge badge badge-<?php echo $camarero['estado'] ? 'success' : 'warning'; ?>">
                                            <?php echo $camarero['estado'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Información resumida -->
                                <div class="info-row">
                                    <i class="fas fa-id-card"></i> <?php echo $camarero['dni']; ?>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-user"></i> <?php echo $camarero['usuario']; ?>
                                </div>

                                <!-- Botones de acción -->
                                <div class="actions">
                                    <form action="gestionar_camarero.php" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $camarero['id']; ?>">
                                        <input type="hidden" name="accion" value="<?php echo $camarero['estado'] ? 'suspender' : 'activar'; ?>">
                                        <button type="submit" class="btn btn-<?php echo $camarero['estado'] ? 'warning' : 'success'; ?>">
                                            <i class="fas fa-<?php echo $camarero['estado'] ? 'pause' : 'play'; ?>"></i>
                                        </button>
                                    </form>
                                    <form action="gestionar_camarero.php" method="POST" class="d-inline" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este camarero?');">
                                        <input type="hidden" name="id" value="<?php echo $camarero['id']; ?>">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
// cerrar la conexion
$conexion->close();
?>
