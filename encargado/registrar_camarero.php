<?php 
include '../sesion.php';
include '../conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Encargado</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido, Encargado </h1>
    </header>
    <nav>
        <ul>
            <li><a href="registrar_camarero.php">Registrar Camarero</a></li>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <section>
        <h2>Panel de Encargado</h2>
        <p>Contenido exclusivo para encargados...</p>
    </section>
    <section>
        <h2>Registrar Camarero</h2>
        <form action="registrar_camarero.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Registrar">
        </form>
    </section>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO camarero (nombre, apellido, email, password) VALUES ('$nombre', '$apellido', '$email', '$password_hash')";
        if ($conexion->query($sql) === TRUE) {
            echo '<p>Camarero registrado correctamente.</p>';
        } else {
            echo '<p>Error al registrar el camarero.</p>';
        }
    }
    ?>
</body>
</html>