<?php
// Incluir la conexión a la base de datos
include 'conexion_be.php';

header('Content-Type: application/json');

// Verificar que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $data = json_decode(file_get_contents("php://input"));

    // Validar los campos
    if (!empty($data->nombre) && !empty($data->descripcion)) {
        // Preparar la consulta SQL para insertar el lugar
        $nombre = mysqli_real_escape_string($conexion, $data->nombre);
        $descripcion = mysqli_real_escape_string($conexion, $data->descripcion);

        $query = "INSERT INTO lugares (nombre, descripcion) VALUES ('$nombre', '$descripcion')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $query)) {
            echo json_encode(["status" => "success", "message" => "Lugar añadido correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al añadir el lugar."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Por favor, completa todos los campos."]);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
