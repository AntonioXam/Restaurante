<?php
// Iniciar sesión
session_start();

// Destruir sesión
session_destroy();

// Borrar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirigir al usuario a la página de inicio
header("Location: index.php");
exit();
?>
