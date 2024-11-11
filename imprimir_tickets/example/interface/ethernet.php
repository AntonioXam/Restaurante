<?php
require __DIR__ . '/../../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

session_start();

// Verificar si hay datos del ticket
if (!isset($_SESSION['ticket_data'])) {
    $_SESSION['error_ticket'] = "No hay datos para imprimir el ticket";
    header("Location: ../../../camarero/cuenta.php");
    exit;
}

$ticket_data = $_SESSION['ticket_data'];
$return_url = isset($ticket_data['return_url']) ? 
              "../../../camarero/" . $ticket_data['return_url'] : 
              "../../../camarero/cuenta.php";
$mesa_id = $ticket_data['mesa_id'];
$error_mensaje = null;

try {
    // Intentar conectar con la impresora con un timeout de 5 segundos
    $connector = @fsockopen("192.168.36.169", 9100, $errno, $errstr, 5);
    
    if (!$connector) {
        throw new Exception("No se pudo conectar con la impresora: $errstr ($errno)");
    }
    fclose($connector);

    // Si la conexión fue exitosa, proceder con la impresión
    $connector = new NetworkPrintConnector("192.168.36.169", 9100);
    $printer = new Printer($connector);

    // Cabecera del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("RESTAURANTE CHAMPIÑON\n");
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
    
    unset($_SESSION['ticket_data']);
    $_SESSION['success_message'] = "Ticket generado correctamente";

} catch (Exception $e) {
    $error_mensaje = "Error al imprimir el ticket: " . $e->getMessage();
    $_SESSION['error_ticket'] = $error_mensaje;
}

// Siempre redirigir, ya sea con éxito o con error
$status = $error_mensaje ? 'error' : 'success';
header("Location: $return_url?mesa_id=$mesa_id&status=$status");
exit;
?>
