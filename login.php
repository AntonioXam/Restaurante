<?php
// Incluir conexión a la base de datos
include 'conexion.php';

// Procesar formulario si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener datos del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar el usuario
    $query = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($contrasena == $row['contrasena']) {
            if ($row['estado'] == 1) {
                // Iniciar sesión y almacenar datos en $_SESSION
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['dni'] = $row['dni'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['apellidos'] = $row['apellidos'];

                // Redireccionar según el rol
                if ($row['rol'] == 'camarero') {
                    header("Location: camarero/index.php");
                } elseif ($row['rol'] == 'encargado') {
                    header("Location: encargado/index.php");
                }
            } else {
                // Configurar modal de cuenta suspendida
                $modalTitle = "Cuenta Suspendida";
                $modalBody = "Su cuenta está suspendida.";
                $modalId = "suspendidoModal";
            }
        } else {
            // Configurar modal de error de credenciales
            $modalTitle = "Error de inicio de sesión";
            $modalBody = "Usuario o contraseña incorrectos.";
            $modalId = "errorModal";
        }
    } else {
        // Configurar modal de error de credenciales
        $modalTitle = "Error de inicio de sesión";
        $modalBody = "Usuario o contraseña incorrectos.";
        $modalId = "errorModal";
    }

} else {
    // Mostrar mensaje de error si no se envió el formulario
    echo "Error al procesar la solicitud.";
    echo "<br>";
    echo "<a href='index.php'>Volver</a>";
}

// Mostrar modal si se configuró
if (isset($modalTitle) && isset($modalBody) && isset($modalId)) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <!-- Metadatos y estilos de Bootstrap -->
        <meta charset="UTF-8">
        <title><?php echo $modalTitle; ?></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <!-- Modal -->
        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modalId; ?>Label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $modalId; ?>Label"><?php echo $modalTitle; ?></h5>
                    </div>
                    <div class="modal-body">
                        <?php echo $modalBody; ?>
                    </div>
                    <div class="modal-footer">
                        <a href="index.php" class="btn btn-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts de jQuery y Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // Mostrar el modal al cargar la página
            $(document).ready(function(){
                $('#<?php echo $modalId; ?>').modal('show');
            });
        </script>
    </body>
    </html>
    <?php
}
?>

