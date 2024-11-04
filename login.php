<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contrasena'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['rol'] = $row['rol'];

        if ($row['rol'] == 'camarero') {
            header("Location: camarero/index.php");
        } elseif ($row['rol'] == 'encargado') {
            header("Location: encargado/index.php");
        }
    } else {
        echo "Usuario o contraseña incorrectos.";
        echo "<br>";
        echo "<a href='index.php'>Volver</a>";
    }

} else {
    echo "Error al procesar la solicitud.";
    echo "<br>";
    echo "<a href='index.php'>Volver</a>";
}

