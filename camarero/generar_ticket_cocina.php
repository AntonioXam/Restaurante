<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

/**
 * Función para generar e imprimir ticket de cocina
 * 
 * @param mysqli $conexion Conexión activa a la base de datos
 * @param int $mesa_id ID de la mesa del pedido
 * @param array $productos Array con los productos del pedido
 * @return bool True si la impresión fue exitosa, False en caso contrario
 * 
 * Proceso:
 * 1. Configuración de la impresora de red
 * 2. Formateo del encabezado del ticket
 * 3. Listado de productos con cantidades y notas
 * 4. Corte del ticket y cierre de conexión
 * 5. Manejo de errores de impresión
 */
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