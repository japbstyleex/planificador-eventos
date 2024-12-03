<?php
$servername = "localhost"; // Servidor de base de datos
$username = "myarmaveae"; // El nombre de usuario de tu base de datos
$password = "BvWd9A5K"; // La contraseña de tu base de datos
$dbname = "armave"; // El nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

$response = array('status' => '', 'message' => '');

// Verificar la conexión
if ($conexion->connect_error) {
    $response['status'] = 'error';
    $response['message'] = "Conexión fallida: " . $conexion->connect_error;
} else {
    $response['status'] = 'success';
    $response['message'] = "Conectado exitosamente";
}

// Devolver respuesta como JSON
echo json_encode($response);

$conexion->close();
?>
