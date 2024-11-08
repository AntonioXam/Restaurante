
<?php
require_once '../vendor/autoload.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

if (!$mesa_id) {
    die('Mesa no especificada');
}

// Obtener productos del ticket temporal
$query = "SELECT * FROM temp_ticket WHERE mesa_id = ? ORDER BY fecha_hora ASC";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $mesa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Configurar TCPDF
$pdf = new PDF();
$pdf->SetCreator('Restaurante');
$pdf->SetTitle('Ticket Mesa ' . $mesa_id);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Cabecera del ticket
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'RESTAURANTE', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Mesa: ' . $mesa_id, 0, 1, 'C');
$pdf->Cell(0, 5, 'Fecha: ' . date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Ln(5);

// Contenido del ticket
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(80, 7, 'Producto', 1);
$pdf->Cell(20, 7, 'Cant.', 1);
$pdf->Cell(30, 7, 'Precio', 1);
$pdf->Cell(30, 7, 'Subtotal', 1);
$pdf->Ln();

$total = 0;
$pdf->SetFont('helvetica', '', 10);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(80, 6, $row['producto'], 1);
    $pdf->Cell(20, 6, $row['cantidad'], 1);
    $pdf->Cell(30, 6, number_format($row['precio_unitario'], 2) . '€', 1);
    $pdf->Cell(30, 6, number_format($row['subtotal'], 2) . '€', 1);
    $pdf->Ln();
    $total += $row['subtotal'];
}

// Total
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(130, 7, 'Total:', 1, 0, 'R');
$pdf->Cell(30, 7, number_format($total, 2) . '€', 1);

// Generar PDF
$pdf->Output('ticket_mesa_' . $mesa_id . '.pdf', 'I');