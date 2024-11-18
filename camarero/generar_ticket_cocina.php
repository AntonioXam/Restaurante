
<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

function generar_ticket_cocina($conexion, $mesa_id, $productos) {
    require_once '../vendor/autoload.php';

    try {
        // Configurar impresora
        $connector = new NetworkPrintConnector("192.168.0.207", 9100);
        $printer = new Printer($connector);

        // Encabezado del ticket
        $printer->setEmphasis(true);
        $printer->text("COMANDA - COCINA\n");
        $printer->text("Mesa: " . $mesa_id . "\n");
        $printer->text("Fecha: " . date('d/m/Y H:i') . "\n");
        $printer->text("--------------------------------\n");
        $printer->setEmphasis(false);

        // Productos
        foreach ($productos as $producto) {
            $printer->text($producto['cantidad'] . "x " . $producto['nombre_producto'] . "\n");
            if (!empty($producto['notas'])) {
                $printer->text("   * " . $producto['notas'] . "\n");
            }
        }

        // Pie del ticket
        $printer->text("--------------------------------\n");
        $printer->cut();
        $printer->close();

        return true;
    } catch (Exception $e) {
        error_log("Error al imprimir ticket de cocina: " . $e->getMessage());
        return false;
    }
}