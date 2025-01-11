<?php
// procesar_pago.php

// Configuración de cabeceras para JSON
header('Content-Type: application/json');

// Comprobamos si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario enviados mediante AJAX
    $metodoPago = $_POST['metodoPago'];
    $response = [];

    // Validamos que el método de pago sea válido
    if ($metodoPago == 'tarjeta') {
        // Recoger datos de la tarjeta
        $nombreTitular = $_POST['nombreTitular'];
        $numeroTarjeta = $_POST['numeroTarjeta'];
        $mesExpiracion = $_POST['mesExpiracion'];
        $anioExpiracion = $_POST['anioExpiracion'];
        $cvv = $_POST['cvv'];

        // Aquí iría la integración con un sistema de pago como Stripe, PayPal, etc.
        // Simulamos que la validación de la tarjeta fue exitosa.
        // Deberías reemplazar este bloque con la lógica real del procesamiento del pago.

        if (strlen($numeroTarjeta) == 16 && is_numeric($cvv) && strlen($cvv) == 3) {
            $response['success'] = true;
            $response['message'] = "Pago procesado con éxito con tarjeta.";
        } else {
            $response['success'] = false;
            $response['message'] = "Datos de tarjeta inválidos.";
        }
    } elseif ($metodoPago == 'transferencia') {
        // Simular procesamiento de transferencia
        // Aquí no es necesario validar datos adicionales, solo indicar que el pago está en proceso
        $response['success'] = true;
        $response['message'] = "Por favor, realiza la transferencia a la cuenta proporcionada.";
    } elseif ($metodoPago == 'bizum') {
        // Simular procesamiento de Bizum
        // Similar al caso de transferencia, se indica que el pago está en proceso
        $response['success'] = true;
        $response['message'] = "Por favor, realiza el pago a través de Bizum.";
    } else {
        // Método de pago no válido
        $response['success'] = false;
        $response['message'] = "Método de pago no válido.";
    }

    // Enviar la respuesta como JSON
    echo json_encode($response);
} else {
    // Si la solicitud no es POST, retornamos un error
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud no válida.'
    ]);
}
