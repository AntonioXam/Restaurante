<?php
include '../sesion.php';
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    // Procesar la imagen
    $foto = null;
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $foto = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Imagen subida correctamente
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, usuario, contrasena, rol, foto, estado) 
            VALUES (?, ?, ?, ?, ?, 'camarero', ?, 1)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $dni, $usuario, $contrasena, $foto);
    
    if ($stmt->execute()) {
        header("Location: listar_camareros.php");
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
?>
