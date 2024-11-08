<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : 0;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';

$query = "SELECT * FROM cuentas_pagadas 
          WHERE mesa_id = ? 
          AND DATE(fecha_hora) = ? 
          AND TIME(fecha_hora) = ?";

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