<?php
require __DIR__ . '/../../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

session_start();

// Verificar si hay datos del ticket
if (!isset($_SESSION['ticket_data'])) {
    die("No hay datos para imprimir el ticket");
}

$ticket_data = $_SESSION['ticket_data'];
unset($_SESSION['ticket_data']); // Limpiar los datos de la sesión

try {
    $connector = new NetworkPrintConnector("10.x.x.x", 9100);
    $printer = new Printer($connector);

    // Cabecera del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("RESTAURANTE\n");
    $printer->text("------------------------\n");
    $printer->text("Mesa: " . $ticket_data['mesa_numero'] . "\n");
    $printer->text("Fecha: " . $ticket_data['fecha'] . "\n");
    $printer->text("------------------------\n\n");

    // Detalle de productos
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    foreach ($ticket_data['productos'] as $producto) {
        $printer->text(str_pad($producto['nombre'], 20));
        $printer->text(str_pad($producto['cantidad'] . "x" . number_format($producto['precio'], 2) . "€", 10));
        $printer->text(str_pad(number_format($producto['subtotal'], 2) . "€", 10, " ", STR_PAD_LEFT) . "\n");
    }

    // Total
    $printer->text("\n------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text("TOTAL: " . number_format($ticket_data['total'], 2) . "€\n");
    
    // Pie del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\nGracias por su visita\n\n");
    
    $printer->cut();
    $printer->close();

    // Redirigir de vuelta a la cuenta
    header("Location: ../../../camarero/cuenta.php?mesa_id=" . $ticket_data['mesa_id']);
    exit;

} catch (Exception $e) {
    echo "No se pudo imprimir el ticket: " . $e->getMessage() . "\n";
}
?>
