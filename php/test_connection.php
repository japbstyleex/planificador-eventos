<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos

if ($conexion) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos.";
}
?>
