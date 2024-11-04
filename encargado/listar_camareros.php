<?php

include '../sesion.php';
include '../conexion.php';


// hacer consulta a la base de datos listado de los camareros
$sql = "SELECT * FROM usuarios WHERE rol = 'camarero'";
$resultado = $conexion->query($sql);


// mostrar los camareros
echo '<h2>Listado de Camareros</h2>';
echo '<table>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>Usuario</th>';
echo '</tr>';

while ($camarero = $resultado->fetch_assoc()) {
    echo '<tr>';
    echo '    <td>' . $camarero['nombre'] . '</td>';
    echo '    <td>' . $camarero['apellidos'] . '</td>';
    echo '    <td>' . $camarero['usuario'] . '</td>';
    echo '</tr>';
}

echo '</table>';

// cerrar la conexion
$conexion->close();




//volver al index
echo '<a href="index.php">Volver</a>';


