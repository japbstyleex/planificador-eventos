<?php
session_start();
$response = array('status' => '', 'message' => '');

// Destruir sesión
session_unset();
session_destroy();

$response['status'] = 'success';
$response['message'] = 'Sesión cerrada correctamente.';

// Devolver respuesta como JSON
echo json_encode($response);
exit;
?>
