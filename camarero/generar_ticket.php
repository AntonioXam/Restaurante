<?php
include '../sesion.php';
include '../conexion.php';

// Por ahora solo redirigimos de vuelta a la cuenta
$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;
header("Location: cuenta.php?mesa_id=$mesa_id");
exit;
?>
