<?php
include '../sesion.php';
include '../conexion.php';
require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$hora = isset($_GET['hora']) ? $_GET['hora'] : '';

try {
    // Obtener datos de la cuenta histórica
    $query = "SELECT * FROM cuentas_pagadas 
              WHERE mesa_id = ? 
              AND DATE(fecha_hora) = ? 
              AND TIME(fecha_hora) = ?
              ORDER BY id";
              
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "iss", $mesa_id, $fecha, $hora);
    mysqli_stmt_execute($stmt);
    $cuenta_result = mysqli_stmt_get_result($stmt);

    // Configurar impresora
    $ipImpresora = "192.168.36.169";  // Cambiar a la IP de tu impresora
    $puertoImpresora = 9100;         // Puerto por defecto para impresoras ESC/POS
    $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer = new Printer($connector);
    
    // Establecer la página de códigos
    $printer->text("\x1B\x74\x01");

    // Generar número de factura (reimpresión)
    $num_factura = date('YmdHi', strtotime("$fecha $hora")) . sprintf("%03d", $mesa_id);

    // Cabecera del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer->text("RESTAURANTE CHAMPIÑON\n");
    $printer->selectPrintMode();
    $printer->text("C/ Example, 123 - Ciudad\n");
    $printer->text("Tel: 912345678\n");
    $printer->text("CIF: B12345678\n");
    $printer->text(str_repeat("-", 32) . "\n");

    // Información de la factura
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Factura Nº: " . $num_factura . " (COPIA)\n");
    $printer->text("Mesa: " . $mesa_id . "\n");
    $printer->text("Fecha Original: " . date('d/m/Y H:i', strtotime("$fecha $hora")) . "\n");
    $printer->text(str_repeat("-", 32) . "\n\n");

    // Detalles de productos
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text(sprintf("%-16s %3s %10s\n", "PRODUCTO", "UDS", "IMPORTE"));
    $printer->text(str_repeat("-", 32) . "\n");

    $total = 0;
    while ($item = mysqli_fetch_assoc($cuenta_result)) {
        $nombre = substr($item['producto'], 0, 16);
        $cantidad = str_pad($item['cantidad'], 3, ' ', STR_PAD_LEFT);
        $precio = str_pad(number_format($item['subtotal'], 2) . " EUR", 10, ' ', STR_PAD_LEFT);
        
        $printer->text(sprintf("%-16s %3s %10s\n", $nombre, $cantidad, $precio));
        $total += $item['subtotal'];
    }

    // Cálculos y totales
    $iva = 0.21;
    $base_imponible = $total / (1 + $iva);
    $cuota_iva = $total - $base_imponible;

    $printer->text(str_repeat("-", 32) . "\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text(sprintf("Base Imponible: %10.2f EUR\n", $base_imponible));
    $printer->text(sprintf("IVA (21%%): %15.2f EUR\n", $cuota_iva));
    $printer->text(str_repeat("=", 32) . "\n");
    $printer->setEmphasis(true);
    $printer->text(sprintf("TOTAL: %18.2f EUR\n", $total));
    $printer->setEmphasis(false);

    // Pie del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("*** COPIA DE TICKET ***\n");
    $printer->text("¡Gracias por su visita!\n");
    $printer->text("\n\n");

    $printer->cut();
    $printer->close();

    header("Location: cuentas_pagadas.php?print=success");
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_ticket'] = "Error al reimprimir ticket: " . $e->getMessage();
    header("Location: cuentas_pagadas.php?print=error");
    exit;
}