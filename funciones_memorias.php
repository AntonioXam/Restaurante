camarero/
├── index.php                  # Panel principal
├── gestionar_mesas.php        # Gestión de mesas
├── gestionar_pedido.php       # Gestión de pedidos
├── seleccionar_producto.php   # Selección de productos
├── productos_anadidos.php     # Lista de productos en pedido
└── js/
    └── scripts.js            # Funciones JavaScript

<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'camarero') {
    header('Location: ../index.php');
    exit;
}

$mesa_id = filter_input(INPUT_GET, 'mesa_id', FILTER_VALIDATE_INT);
if (!$mesa_id) {
    header('Location: gestionar_mesas.php');
    exit;
}

$categorias = [
    'pizzas' => 'Pizzas',
    'ensalada' => 'Ensaladas',
    'bebida' => 'Bebidas',
    'carne' => 'Carnes',
    'pasta' => 'Pasta',
    'pescado' => 'Pescado',
    'vino' => 'Vinos'
];

function obtener_mesas_activas($conexion) {
    return mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'activa'");
}

function obtener_detalle_pedidos($conexion, $mesa_id) {
    $query = "SELECT dp.*, p.nombre as nombre_producto, p.precio 
              FROM detalle_pedidos dp 
              INNER JOIN productos p ON dp.producto_id = p.id 
              INNER JOIN pedidos ped ON dp.pedido_id = ped.id 
              WHERE ped.mesa_id = $mesa_id AND ped.estado = 'pendiente'";
    return mysqli_query($conexion, $query);
}

// Lógica para activar mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    $comensales = $_POST['comensales'];

    mysqli_query($conexion, "UPDATE mesas SET estado = 'activa', comensales = $comensales WHERE id = $mesa_id");
    mysqli_query($conexion, "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0.00)");
}

@media (max-width: 768px) {
    .mesa-card .card-body {
        padding: 1rem;
    }
    
    .mesa-card .fa-2x {
        font-size: 1.5em;
    }
    
    .mesa-card .card-title {
        font-size: 1rem;
    }
    
    .mesa-card .card-text {
        font-size: 0.8rem;
    }
}

function modificarCantidad(detalle_id, cantidad, notas) {
    document.getElementById('mod_detalle_id').value = detalle_id;
    document.getElementById('mod_cantidad').value = cantidad;
    document.getElementById('mod_notas').value = notas;
    new bootstrap.Modal(document.getElementById('modificarModal')).show();
}

function confirmarEliminacion() {
    return confirm('¿Está seguro de eliminar este producto?');
}

$total = 0;
while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)) {
    $subtotal = $detalle['cantidad'] * $detalle['precio'];
    $total += $subtotal;
}