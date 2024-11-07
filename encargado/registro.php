<?php

include '../sesion.php';
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $rol = 'camarero';
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena']; // Asegúrate de que el nombre de la columna sea correcto

    // Insertar en la base de datos
    $query = "INSERT INTO usuarios (nombre, apellidos, dni, rol, usuario, contrasena) VALUES ('$nombre', '$apellido', '$dni', '$rol', '$usuario', '$contrasena')";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        $mensaje = "Camarero registrado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al registrar el camarero.";
        $tipo_mensaje = "danger";
    }
} else {
    $mensaje = "Error al procesar la solicitud.";
    $tipo_mensaje = "danger";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Camarero</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
    <div class="container mt-3 mt-lg-5">
        <div class="alert alert-<?php echo $tipo_mensaje; ?> text-center" role="alert">
            <?php echo $mensaje; ?>
        </div>
        <p class="text-center">Será redirigido al panel de encargado en unos segundos...</p>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
