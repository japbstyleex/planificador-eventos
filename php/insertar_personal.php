<?php
// Incluir la conexión a la base de datos
include 'conexion_be.php';

header('Content-Type: application/json');

// Verificar que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $data = json_decode(file_get_contents("php://input"));

    // Validar los campos
    if (!empty($data->nombre) && !empty($data->cargo)) {
        // Preparar la consulta SQL para insertar el personal
        $nombre = mysqli_real_escape_string($conexion, $data->nombre);
        $cargo = mysqli_real_escape_string($conexion, $data->cargo);

        $query = "INSERT INTO personal (nombre, cargo) VALUES ('$nombre', '$cargo')";

        // Ejecutar la consulta
        if (mysqli_query($conexion, $query)) {
            echo json_encode(["status" => "success", "message" => "Personal añadido correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al añadir el personal."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Por favor, completa todos los campos."]);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
