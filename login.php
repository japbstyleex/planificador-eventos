<?php
session_start(); // Inicia la sesión
include('conexion_be.php'); // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos enviados por el formulario de inicio de sesión
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para obtener los datos del usuario
    $query = "SELECT * FROM usuarios WHERE nombre_usuario = '$username'";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        // Usuario encontrado, comprobar la contraseña
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña (en este caso sin encriptar, pero se recomienda usar contraseñas hash)
        if ($user['contrasena'] === $password) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre_usuario'];
            $_SESSION['role'] = $user['rol'];

            // Redirigir a la página principal o al panel de usuario
            header("Location: index.html");
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
