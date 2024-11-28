<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "myarmaveae"; // Cambia según tu configuración
$password = "BvWd9A5K"; // Cambia según tu configuración
$dbname = "armave"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer la codificación UTF-8 para la conexión
$conn->set_charset("utf8");

// Obtener datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['username'];
    $contrasena = $_POST['password'];

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $row = $result->fetch_assoc();

        // Si el rol es admin, redirigir a admin.html
        if ($row['rol'] == 'admin') {
            session_start();
            $_SESSION['username'] = $row['nombre_usuario'];
            $_SESSION['role'] = 'admin';
            header("Location: admin.html");
            exit();
        } else {
            // Si el rol es usuario, redirigir a la página principal y mostrar el nombre en la navbar
            session_start();
            $_SESSION['username'] = $row['nombre_usuario'];
            $_SESSION['role'] = 'usuario';
            header("Location: index.html");
            exit();
        }
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.history.back();</script>";
    }
}

$conn->close();
?>
