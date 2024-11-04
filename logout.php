<?php
session_start();

include 'sesion.php';
include 'conexion.php';

$camarero_id = $_SESSION['usuario_id'];

// Verificar si todas las mesas del camarero están pagadas
$query_verificar_pagadas = "SELECT * FROM mesas m
                            JOIN pedidos p ON m.id = p.mesa_id
                            WHERE m.camarero_id = $camarero_id AND p.estado != 'pagado'";
$result_verificar_pagadas = mysqli_query($conexion, $query_verificar_pagadas);

if (mysqli_num_rows($result_verificar_pagadas) == 0) {
    // Eliminar mesas del camarero
    $query_eliminar_mesas = "DELETE FROM mesas WHERE camarero_id = $camarero_id";
    mysqli_query($conexion, $query_eliminar_mesas);
}

// Eliminar todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, también se debe eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Eliminar la caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirigir a la página de inicio de sesión
header("Location: login.php");
exit();
?>
