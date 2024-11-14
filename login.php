<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($contrasena == $row['contrasena']) {
            if ($row['estado'] == 1) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['dni'] = $row['dni'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['apellidos'] = $row['apellidos'];

                if ($row['rol'] == 'camarero') {
                    header("Location: camarero/index.php");
                } elseif ($row['rol'] == 'encargado') {
                    header("Location: encargado/index.php");
                }
            } else {
                // Mostrar modal de cuenta suspendida
                ?>
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <title>Cuenta Suspendida</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                </head>
                <body>
                    <div class="modal fade" id="suspendidoModal" tabindex="-1" role="dialog" aria-labelledby="suspendidoModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="suspendidoModalLabel">Cuenta Suspendida</h5>
                                </div>
                                <div class="modal-body">
                                    Su cuenta está suspendida.
                                </div>
                                <div class="modal-footer">
                                    <a href="index.php" class="btn btn-primary">Volver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                    <script>
                        $(document).ready(function(){
                            $('#suspendidoModal').modal('show');
                        });
                    </script>
                </body>
                </html>
                <?php
            }
        } else {
            // Mostrar modal de usuario o contraseña incorrectos
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Error de inicio de sesión</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            </head>
            <body>
                <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="errorModalLabel">Error de inicio de sesión</h5>
                            </div>
                            <div class="modal-body">
                                Usuario o contraseña incorrectos.
                            </div>
                            <div class="modal-footer">
                                <a href="index.php" class="btn btn-primary">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                <script>
                    $(document).ready(function(){
                        $('#errorModal').modal('show');
                    });
                </script>
            </body>
            </html>
            <?php
        }
    } else {
        // Mostrar modal de usuario o contraseña incorrectos
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Error de inicio de sesión</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalLabel">Error de inicio de sesión</h5>
                        </div>
                        <div class="modal-body">
                            Usuario o contraseña incorrectos.
                        </div>
                        <div class="modal-footer">
                            <a href="index.php" class="btn btn-primary">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function(){
                    $('#errorModal').modal('show');
                });
            </script>
        </body>
        </html>
        <?php
    }

} else {
    echo "Error al procesar la solicitud.";
    echo "<br>";
    echo "<a href='index.php'>Volver</a>";
}

