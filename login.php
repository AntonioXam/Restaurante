<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Procesar el formulario de login cuando se recibe una petición POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener y sanitizar los datos del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para verificar las credenciales del usuario
    // Busca un usuario específico en la tabla 'usuarios'
    $query = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = mysqli_query($conexion, $query);

    // Verificar si se encontró el usuario
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verificar si la contraseña coincide
        if ($contrasena == $row['contrasena']) {
            // Verificar si la cuenta está activa (estado = 1)
            if ($row['estado'] == 1) {
                // Iniciar sesión y almacenar datos importantes del usuario
                session_start();
                $_SESSION['usuario'] = $usuario;      // Nombre de usuario
                $_SESSION['usuario_id'] = $row['id']; // ID único del usuario
                $_SESSION['rol'] = $row['rol'];       // Rol del usuario (camarero/encargado)
                $_SESSION['dni'] = $row['dni'];       // DNI del usuario
                $_SESSION['nombre'] = $row['nombre']; // Nombre real del usuario
                $_SESSION['apellidos'] = $row['apellidos']; // Apellidos del usuario

                // Redireccionar según el rol del usuario
                if ($row['rol'] == 'camarero') {
                    header("Location: camarero/index.php");
                } elseif ($row['rol'] == 'encargado') {
                    header("Location: encargado/index.php");
                }
            } else {
                // Configurar modal para cuenta suspendida
                $modalTitle = "Cuenta Suspendida";
                $modalBody = "Su cuenta está suspendida.";
                $modalId = "suspendidoModal";
            }
        } else {
            // Configurar modal para credenciales incorrectas
            $modalTitle = "Error de inicio de sesión";
            $modalBody = "Usuario o contraseña incorrectos.";
            $modalId = "errorModal";
        }
    } else {
        // Configurar modal para usuario no encontrado
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

