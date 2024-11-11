<?php
include '../sesion.php';
include '../conexion.php';
require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

function generarTicketCocina($conexion, $mesa_id, $productos) {
    try {
        $ipImpresora = "192.168.0.169";  // Cambiar a la IP de tu impresora
        $puertoImpresora = 9100;         // Puerto por defecto para impresoras ESC/POS
        $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
        $printer = new Printer($connector);

        // Encabezado del ticket
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $printer->text("*** COCINA ***\n");
        $printer->selectPrintMode();
        $printer->text("------------------------\n");

        // Información de la mesa
        $printer->setEmphasis(true);
        $printer->text("MESA: " . $mesa_id . "\n");
        $printer->text("FECHA: " . date('d/m/Y H:i') . "\n");
        $printer->setEmphasis(false);
        $printer->text("------------------------\n\n");

        // Detalles de los productos
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        
        // Iterar directamente sobre el array de productos
        foreach ($productos as $item) {
            $printer->setEmphasis(true);
            $printer->text($item['cantidad'] . "x " . $item['nombre_producto'] . "\n");
            $printer->setEmphasis(false);
            
            if (!empty($item['notas'])) {
                $printer->text("  * " . $item['notas'] . "\n");
            }
            $printer->text("\n");
        }

        // Pie del ticket
        $printer->text("------------------------\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("*** PREPARAR PEDIDO ***\n");
        
        // Cortar el papel y cerrar
        $printer->cut();
        $printer->close();
        
        return true;
    } catch (Exception $e) {
        // Si hay algún error con la impresora, lo registramos pero permitimos continuar
        error_log("Error al imprimir ticket de cocina: " . $e->getMessage());
        
        try {
            // Intentar cerrar la conexión con la impresora si existe
            if (isset($printer)) {
                $printer->close();
            }
        } catch (Exception $closeError) {
            error_log("Error al cerrar la impresora: " . $closeError->getMessage());
        }
        
        return true; // Retornamos true para permitir continuar con el proceso
    }
}
?>