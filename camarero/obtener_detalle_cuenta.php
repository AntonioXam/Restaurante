<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';

/**
 * Consulta para obtener los detalles de una cuenta específica
 * Parámetros:
 * - mesa_id: ID de la mesa
 * - fecha: Fecha de la cuenta en formato YYYY-MM-DD
 * - hora: Hora de la cuenta en formato HH:MM:SS
 * 
 * La consulta retorna:
 * - Productos de la cuenta
 * - Cantidades de cada producto
 * - Precios unitarios
 * - Subtotales
 * Filtrados por mesa_id, fecha y hora específicas
 */
$query = "SELECT * FROM cuentas_pagadas 
          WHERE mesa_id = ? 
          AND DATE(fecha_hora) = ? 
          AND TIME(fecha_hora) = ?";

// Uso de prepared statement para prevenir SQL injection
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "iss", $mesa_id, $fecha, $hora);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$detalles = array();
while ($row = mysqli_fetch_assoc($result)) {
    $detalles[] = $row;
}

header('Content-Type: application/json');
echo json_encode($detalles);
?>