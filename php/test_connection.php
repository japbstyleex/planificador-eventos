<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos

$response = array('status' => '', 'message' => '');

if ($conexion) {
    $response['status'] = 'success';
    $response['message'] = 'Conexión exitosa a la base de datos.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error al conectar a la base de datos.';
}

// Devolver respuesta como JSON
echo json_encode($response);

$conexion->close();
?>
