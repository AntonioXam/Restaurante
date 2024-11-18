<?php
include 'sesion_encargado.php';
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $accion = $_POST['accion'];
    
    // Verificar si el ID es 1 y prevenir la acción
    if ($id == 1) {
        echo "No se puede modificar el usuario principal.";
        exit;
    }
    
    switch($accion) {
        case 'suspender':
            $sql = "UPDATE usuarios SET estado = 0 WHERE id = ?";
            break;
        case 'activar':
            $sql = "UPDATE usuarios SET estado = 1 WHERE id = ?";
            break;
        case 'eliminar':
            $sql = "DELETE FROM usuarios WHERE id = ?";
            break;
        default:
            header("Location: listar_usuarios.php");
            exit;
    }
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: listar_usuarios.php");
    } else {
        echo "Error al ejecutar la acción: " . $conexion->error;
    }
    
    $stmt->close();
}

$conexion->close();
?>