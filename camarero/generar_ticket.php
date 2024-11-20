<?php
include '../sesion.php';
include '../conexion.php';
require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

try {
    // Obtener datos de la cuenta
    $query = "SELECT c.*, p.nombre as nombre_producto 
             FROM cuenta c 
             INNER JOIN productos p ON c.producto_id = p.id 
             WHERE c.mesa_id = ?";
             
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $mesa_id);
    mysqli_stmt_execute($stmt);
    $cuenta_result = mysqli_stmt_get_result($stmt);

    // Configurar impresora - Usar conexión de red
    $ipImpresora = "192.168.0.207";  // Cambiar a la IP de tu impresora
    $puertoImpresora = 9100;         // Puerto por defecto para impresoras ESC/POS
    $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer = new Printer($connector);
    
    // Configuración inicial de la impresora
    $printer->setPrintLeftMargin(0);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(1, 1);
    
    // Generar número de factura (año + mes + día + hora + minutos + mesa)
    $num_factura = date('YmdHi') . sprintf("%03d", $mesa_id);

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
    $printer->text("Factura Nº: " . $num_factura . "\n");
    $printer->text("Mesa: " . $mesa_id . "\n");
    $printer->text("Fecha: " . date('d/m/Y H:i') . "\n");
    $printer->text(str_repeat("-", 32) . "\n\n");

    // Cabecera de la tabla
    $printer->text(str_repeat("=", 32) . "\n");
    $printer->text(sprintf("%-16s %3s %10s\n", "PRODUCTO", "UDS", "IMPORTE"));
    $printer->text(str_repeat("=", 32) . "\n");

    // Detalles de productos
    $total = 0;
    $iva = 0.21; // 21% IVA
    while ($item = mysqli_fetch_assoc($cuenta_result)) {
        $subtotal = $item['cantidad'] * $item['precio_unitario'];
        $total += $subtotal;
        
        $nombre = substr($item['nombre_producto'], 0, 16);
        $cantidad = str_pad($item['cantidad'], 3, ' ', STR_PAD_LEFT);
        $precio = str_pad(number_format($subtotal, 2) . " EUR", 10, ' ', STR_PAD_LEFT);
        
        $printer->text(sprintf("%-16s %3s %10s\n", $nombre, $cantidad, $precio));
    }

    // Cálculos finales
    $base_imponible = $total / (1 + $iva);
    $cuota_iva = $total - $base_imponible;

    // Totales
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
    $printer->text("¡Gracias por su visita!\n");
    $printer->text("www.restaurantechampinon.com\n");
    $printer->text("\n");
    $printer->text("Conserve esta factura\n");
    $printer->text("para cualquier reclamación\n");
    $printer->text("\n\n\n\n\n");  // Espacio en blanco para corte

    // Cortar ticket
    $printer->cut();
    $printer->close();

    // Si viene de procesar pago, continuamos con el proceso de pago
    if (isset($_GET['action']) && $_GET['action'] === 'pagar') {
        try {
            mysqli_begin_transaction($conexion);
            
            // Obtener los productos de la cuenta actual
            $query = "SELECT c.*, p.nombre as nombre_producto 
                     FROM cuenta c 
                     INNER JOIN productos p ON c.producto_id = p.id 
                     WHERE c.mesa_id = ?";
                     
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $mesa_id);
            mysqli_stmt_execute($stmt);
            $productos_cuenta = mysqli_stmt_get_result($stmt);
            
            // Guardar cada producto en cuentas_pagadas para historial
            while ($item = mysqli_fetch_assoc($productos_cuenta)) {
                $insert_cuenta = "INSERT INTO cuentas_pagadas 
                                (mesa_id, producto, cantidad, precio_unitario, subtotal) 
                                VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexion, $insert_cuenta);
                mysqli_stmt_bind_param($stmt, "isids",
                    $mesa_id,
                    $item['nombre_producto'],
                    $item['cantidad'],
                    $item['precio_unitario'],
                    $item['subtotal']
                );
                mysqli_stmt_execute($stmt);
            }
            
            // Limpiar cuenta actual y liberar mesa
            mysqli_query($conexion, "DELETE FROM cuenta WHERE mesa_id = $mesa_id");
            mysqli_query($conexion, "UPDATE mesas SET estado = 'inactiva', comensales = NULL WHERE id = $mesa_id");
            
            mysqli_commit($conexion);
            header("Location: gestionar_mesas.php?status=success");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            $_SESSION['error_ticket'] = "Error al procesar el pago: " . $e->getMessage();
            header("Location: cuenta.php?mesa_id=$mesa_id&status=error");
            exit;
        }
    }

    // Si no viene de pagar, volvemos a la cuenta
    header("Location: cuenta.php?mesa_id=$mesa_id&print=success");
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_ticket'] = "Error al imprimir ticket: " . $e->getMessage();
    if (isset($_GET['action']) && $_GET['action'] === 'pagar') {
        $_SESSION['error_ticket'] .= " ¿Desea procesar el pago igualmente?";
        header("Location: cuenta.php?mesa_id=$mesa_id&confirmar_pago=true");
    } else {
        header("Location: cuenta.php?mesa_id=$mesa_id");
    }
    exit;
}
?>