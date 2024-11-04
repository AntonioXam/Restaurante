<?php

include '../sesion.php';
include '../conexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $rol = 'camarero';
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        

    // Insertar en la base de datos
    $query = "INSERT INTO usuarios (nombre, apellidos, rol, usuario, contrasena) VALUES ('$nombre', '$apellido', '$rol', '$usuario', '$password')";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        echo "Camarero registrado correctamente.";
        echo "<br>";
        echo "<a href='index.php'>Volver</a>";
    } else {
        echo "Error al registrar el camarero.";
        echo "<br>";
        echo "<a href='index.php'>Volver</a>";
    }   

} else {
    echo "Error al procesar la solicitud.";
    echo "<br>";
    echo "<a href='index.php'>Volver</a>";

}
