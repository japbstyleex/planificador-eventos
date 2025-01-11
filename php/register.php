<?php
session_start();
include('conexion_be.php'); // Conexión a la base de datos

$response = array('status' => '', 'message' => '');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'usuario';  // Rol por defecto es 'usuario'

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Todos los campos son requeridos.';
        echo json_encode($response);
        exit;
    }

    // Verificar si el nombre de usuario ya existe
    $check_user_query = "SELECT * FROM usuarios WHERE nombre_usuario = ? OR correo_electronico = ?";
    $stmt = $conexion->prepare($check_user_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'El nombre de usuario o correo electrónico ya está registrado.';
        echo json_encode($response);
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $insert_query = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol) 
                     VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($insert_query);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Usuario registrado correctamente.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Hubo un error al registrar el usuario.';
    }

    // Cerrar la conexión y devolver la respuesta
    $stmt->close();
    echo json_encode($response);
}

$conexion->close();
