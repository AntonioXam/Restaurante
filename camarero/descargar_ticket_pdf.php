<?php
ob_start(); // Iniciar buffer de salida
require_once '../sesion.php';
require_once '../conexion.php';
require_once '../vendor/autoload.php';

// Cambiar el use statement
use TCPDF as TCPDF;

// Resto del código del archivo permanece igual, solo cambiamos la clase MYPDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 15, 'RESTAURANTE CHAMPIÑÓN', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';

try {
    /**
     * Consulta para obtener los datos de una cuenta pagada en una mesa específica
     * en una fecha y hora determinadas.
     *
     * La consulta selecciona todas las columnas de la tabla `cuentas_pagadas` 
     * donde el `mesa_id` coincide con el proporcionado, y la fecha y hora de 
     * `fecha_hora` coinciden con los valores proporcionados.
     *
     * Parámetros:
     * - `mesa_id` (int): El ID de la mesa.
     * - `fecha` (string): La fecha en formato 'YYYY-MM-DD'.
     * - `hora` (string): La hora en formato 'HH:MM:SS'.
     *
     * @param mysqli $conexion La conexión a la base de datos.
     * @param int $mesa_id El ID de la mesa.
     * @param string $fecha La fecha en formato 'YYYY-MM-DD'.
     * @param string $hora La hora en formato 'HH:MM:SS'.
     * @return mysqli_result El resultado de la consulta.
     */
    // Obtener datos de la cuenta
    $query = "SELECT * FROM cuentas_pagadas 
              WHERE mesa_id = ? 
              AND DATE(fecha_hora) = ? 
              AND TIME(fecha_hora) = ?";
              
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "iss", $mesa_id, $fecha, $hora);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Crear nuevo documento PDF
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Configurar documento
    $pdf->SetCreator('Restaurante Sistema');
    $pdf->SetAuthor('Restaurante Champiñón');
    $pdf->SetTitle('Ticket Mesa ' . $mesa_id);

    // Establecer márgenes
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);

    // Añadir página
    $pdf->AddPage();

    // Información del restaurante
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'C/ Example, 123 - Ciudad', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Tel: 912345678 - CIF: B12345678', 0, 1, 'C');
    $pdf->Ln(5);

    // Información del ticket
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 10, 'TICKET DE VENTA', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Mesa: ' . $mesa_id . ' - Fecha: ' . date('d/m/Y H:i', strtotime($fecha . ' ' . $hora)), 0, 1, 'C');
    $pdf->Ln(5);

    // Cabecera de la tabla
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(80, 7, 'Producto', 1);
    $pdf->Cell(20, 7, 'Cant.', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Precio', 1, 0, 'R');
    $pdf->Cell(40, 7, 'Subtotal', 1, 0, 'R');
    $pdf->Ln();

    // Detalles de productos
    $pdf->SetFont('helvetica', '', 10);
    $total = 0;
    while ($item = mysqli_fetch_assoc($result)) {
        $pdf->Cell(80, 6, $item['producto'], 1);
        $pdf->Cell(20, 6, $item['cantidad'], 1, 0, 'C');
        $pdf->Cell(30, 6, number_format($item['precio_unitario'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(40, 6, number_format($item['subtotal'], 2) . ' €', 1, 0, 'R');
        $pdf->Ln();
        $total += $item['subtotal'];
    }

    // Total
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(130, 7, 'TOTAL:', 1, 0, 'R');
    $pdf->Cell(40, 7, number_format($total, 2) . ' €', 1, 0, 'R');

    // Generar el PDF
    ob_end_clean(); // Limpiar cualquier salida antes de generar el PDF
    $pdf->Output('Ticket_Mesa_' . $mesa_id . '.pdf', 'D');
    
} catch (Exception $e) {
    error_log("Error al generar PDF: " . $e->getMessage());
    header("Location: cuentas_pagadas.php?error=pdf");
    exit;
}