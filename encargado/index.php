
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
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <header>
        <h1>Bienvenido, 
            <?php $nombre= $_SESSION['usuario'] ?>
            <?php echo $nombre; ?>
        </h1>
      
    </header>
    <nav>
        <ul>
            <li><a href="registrar_camarero.php">Registrar Camarero</a></li>
            <li><a href="listar_camareros.php">Listar Camareros</a></li>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <section>
        <h2>Panel de Encargado</h2>
        <p>Contenido exclusivo para encargados...</p>
    </section>
</body>
</html>
