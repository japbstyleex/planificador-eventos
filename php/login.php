<?php
session_start();
include('conexion_be.php'); // Conexión a la base de datos

$response = array('status' => '', 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para obtener datos del usuario
    $query = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($query);

    if (!$stmt) {
        $response['status'] = 'error';
        $response['message'] = "Error al preparar la consulta: " . $conexion->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar contraseña directamente
        if ($password === $user['contrasena']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre_usuario'];
            $_SESSION['role'] = $user['rol'];

            // Redirigir según el rol del usuario
            if ($user['rol'] === 'admin') {
                $response['status'] = 'success';
                $response['message'] = 'admin.html';
            } else {
                $response['status'] = 'success';
                $response['message'] = 'index.html';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Usuario no encontrado.';
    }

    $stmt->close();
}

$conexion->close();

// Enviar respuesta como JSON
echo json_encode($response);
?>
