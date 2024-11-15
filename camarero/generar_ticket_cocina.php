<?php
include '../sesion.php';
include '../conexion.php';
require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

function generar_ticket_cocina($conexion, $mesa_id, $productos) {
    try {
        // Configurar impresora - Usar conexión de red
        $ipImpresora = "192.168.0.169";  // Cambiar a la IP de tu impresora
        $puertoImpresora = 9100;         // Puerto por defecto para impresoras ESC/POS
        $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
        $printer = new Printer($connector);
        
        // Configuración inicial de la impresora
        $printer->setPrintLeftMargin(0);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        
        // Cabecera del ticket
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text("TICKET DE COCINA\n");
        $printer->selectPrintMode();
        $printer->text("Mesa: " . $mesa_id . "\n");
        $printer->text("Fecha: " . date('d/m/Y H:i') . "\n");
        $printer->text(str_repeat("-", 32) . "\n");

        // Detalles de productos
        foreach ($productos as $item) {
            $nombre = substr($item['nombre_producto'], 0, 16);
            $cantidad = str_pad($item['cantidad'], 3, ' ', STR_PAD_LEFT);
            
            $printer->text(sprintf("%-16s %3s\n", $nombre, $cantidad));
        }

        // Pie del ticket
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("\n");
        $printer->text("FIN DEL TICKET\n");
        $printer->text("\n\n");

        // Cortar ticket
        $printer->cut();
        $printer->close();

        return true;
    } catch (Exception $e) {
        error_log("Error al imprimir ticket de cocina: " . $e->getMessage());
        return false;
    }
}

if (isset($_GET['mesa_id'])) {
    $mesa_id = (int)$_GET['mesa_id'];
    $query = "SELECT dp.*, p.nombre as nombre_producto 
             FROM detalle_pedidos dp 
             INNER JOIN productos p ON dp.producto_id = p.id 
             INNER JOIN pedidos ped ON dp.pedido_id = ped.id 
             WHERE ped.mesa_id = ? AND ped.estado = 'pendiente'";
    
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $mesa_id);
    mysqli_stmt_execute($stmt);
    $productos = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

    if (generar_ticket_cocina($conexion, $mesa_id, $productos)) {
        header("Location: cuenta.php?mesa_id=$mesa_id&print=success");
    } else {
        header("Location: cuenta.php?mesa_id=$mesa_id&print=error");
    }
    exit;
}
?>