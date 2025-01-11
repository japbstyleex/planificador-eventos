<?php
include('conexion_be.php'); // Conexión a la base de datos
header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Error desconocido');

$stmt = $conexion->prepare("SELECT articulo, COUNT(*) AS cantidad, SUM(presupuesto) AS presupuesto_total FROM carrito GROUP BY articulo");
if (!$stmt) {
    $response['message'] = "Error al preparar la consulta: " . $conexion->error;
    echo json_encode($response);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$stats = [];
while ($dato = $result->fetch_assoc()) {
    $stats[] = $dato;
}

$response['status'] = 'success';
$response['message'] = 'Datos obtenidos';
$response['stats'] = $stats;

echo json_encode($response);
