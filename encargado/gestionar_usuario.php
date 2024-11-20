<?php

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';

/**
 * Procesa las acciones de gestión de usuarios
 * - suspender: Cambia el estado del usuario a inactivo (0)
 * - activar: Cambia el estado del usuario a activo (1)
 * - eliminar: Elimina el usuario de la base de datos
 * 
 * @param int $id ID del usuario a gestionar
 * @param string $accion Tipo de acción a realizar
 * @return void Redirecciona a la lista de usuarios
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $accion = $_POST['accion'];
    
    // Previene modificaciones al usuario administrador principal
    if ($id == 1) {
        echo "No se puede modificar el usuario principal.";
        exit;
    }
    
    // Prepara la consulta SQL según la acción solicitada
    switch($accion) {
        case 'suspender':
            // Actualiza el estado a inactivo (0)
            // Ejemplo: UPDATE usuarios SET estado = 0 WHERE id = 5
            $sql = "UPDATE usuarios SET estado = 0 WHERE id = ?";
            break;
        case 'activar':
            // Actualiza el estado a activo (1)
            // Ejemplo: UPDATE usuarios SET estado = 1 WHERE id = 5
            $sql = "UPDATE usuarios SET estado = 1 WHERE id = ?";
            break;
        case 'eliminar':
            // Elimina el usuario especificado
            // Ejemplo: DELETE FROM usuarios WHERE id = 5
            $sql = "DELETE FROM usuarios WHERE id = ?";
            break;
        default:
            header("Location: listar_usuarios.php");
            exit;
    }
    
    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: listar_usuarios.php");
    } else {
        echo "Error al ejecutar la acción: " . $conexion->error;
    }
    
    $stmt->close();
}

// Cerrar conexión
$conexion->close();
?>