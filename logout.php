<?php
// Iniciar la sesión actual para poder destruirla
session_start();

// Destruir todas las variables de sesión y la sesión misma
session_destroy();

// Configurar cabeceras para evitar el almacenamiento en caché
// Esto asegura que el usuario no pueda volver atrás después de cerrar sesión
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redireccionar al usuario a la página de inicio
header("Location: index.php");
exit();
?>
