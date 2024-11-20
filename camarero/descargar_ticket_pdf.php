<?php
// Asegurarse de que no haya salida anterior
ob_clean();

// Incluir archivos necesarios
require_once '../sesion.php';
require_once '../conexion.php';
require_once '../vendor/autoload.php';

// Definir la clase MYPDF antes de usarla
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 15, 'RESTAURANTE CHAMPIÑÓN', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
}

// Verificar parámetros
if (!isset($_GET['mesa_id']) || !isset($_GET['fecha']) || !isset($_GET['hora'])) {
    die('Faltan parámetros necesarios');
}

$mesa_id = (int)$_GET['mesa_id'];
$fecha = $_GET['fecha'];
$hora = $_GET['hora'];

// Verificar si hay datos
$query = "SELECT COUNT(*) as count FROM cuentas_pagadas 
          WHERE mesa_id = ? 
          AND DATE(fecha_hora) = ? 
          AND TIME(fecha_hora) = ?";
          
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "iss", $mesa_id, $fecha, $hora);
mysqli_stmt_execute($stmt);
$count_result = mysqli_stmt_get_result($stmt);
$count_row = mysqli_fetch_assoc($count_result);

if ($count_row['count'] == 0) {
    die('No se encontraron datos para el ticket solicitado');
}

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

// Antes de generar el PDF
ob_end_clean();

// Configurar headers
header('Content-Type: application/pdf');
header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

// Generar y enviar el PDF
$pdf->Output('ticket_mesa_' . $mesa_id . '.pdf', 'D');
exit();
?>