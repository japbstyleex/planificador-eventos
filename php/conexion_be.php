<?php
$servername = "armave.es"; // Servidor de base de datos
$username = "myarmaveae"; // El nombre de usuario de tu base de datos
$password = "BvWd9A5K"; // La contraseña de tu base de datos
$dbname = "armave"; // El nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conectado exitosamente";
}
?>
