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
            <li><a href="index.php">volver</a></li>
        </ul>
    </nav>
    <section>
        <h2>Panel de Encargado</h2>
        <p>Contenido exclusivo para encargados...</p>
    </section>
    <section>
        <h2>Registrar Camarero</h2>
        <form action="registro.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" id="apellido" required>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Registrar">
        </form>
    </section>
</body>
</html>