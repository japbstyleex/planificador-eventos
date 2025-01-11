<?php
include('conexion_be.php'); // Conexión a la base de datos
header('Content-Type: application/json');
session_start();

$response = array('status' => 'error', 'message' => 'Error desconocido');

if (empty($_SESSION['user_id'])) {
    $response['message'] = 'No has iniciado sesión.';
    echo json_encode($response);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET": // Obtener carrito de user_id
        $stmt = $conexion->prepare("SELECT id, articulo, servicio, presupuesto FROM carrito WHERE id_usuario = ?");
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta: " . $conexion->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_assoc()) {
            $productos[] = $producto;
        }

        $response['status'] = 'success';
        $response['message'] = 'Carrito obtenido correctamente';
        $response['carrito'] = $productos;

        break;
    case "POST": // Añadir item al carrito de user_id
        $articulo = $_POST['articulo'];
        $servicio = $_POST['servicio'];
        $presupuesto = $_POST['presupuesto'];

        if (empty($articulo) || empty($servicio) || filter_var($presupuesto, FILTER_VALIDATE_FLOAT) === false) {
            $response['message'] = "Formulario no válido";
            echo json_encode($response);
            exit;
        }
        $presupuesto = (float) $presupuesto;

        $stmt = $conexion->prepare("INSERT INTO carrito (id_usuario, articulo, servicio, presupuesto) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta: " . $conexion->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("issd", $_SESSION['user_id'], $articulo, $servicio, $presupuesto);
        $stmt->execute();
        $response['status'] = "success";
        $response['message'] = "Item añadido al carrito";
        break;
    case "PUT": // Editar item del carrito de user_id
        $data = json_decode(file_get_contents('php://input'), true);
        $id_articulo = $data['id_articulo'];
        $articulo = $data['articulo'];
        $servicio = $data['servicio'];
        $presupuesto = $data['presupuesto'];

        if (!is_numeric($id_articulo) || empty($articulo) || empty($servicio) || filter_var($presupuesto, FILTER_VALIDATE_FLOAT) === false) {
            $response['message'] = "Formulario no válido";
            $response['debug'] = $data;
            echo json_encode($response);
            exit;
        }
        $id_articulo = (int) $id_articulo;
        $presupuesto = (float) $presupuesto;

        $stmt = $conexion->prepare("UPDATE carrito SET articulo = ?, servicio = ?, presupuesto = ? WHERE id = ? AND id_usuario = ?");
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta: " . $conexion->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("ssdii", $articulo, $servicio, $presupuesto, $id_articulo, $_SESSION['user_id']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            $response['message'] = "Artículo no encontrado";
        } else {
            $response['status'] = "success";
            $response['message'] = "Artículo modificado";
        }
        break;
    case "DELETE": // Eliminar item del carrito de user_id
        $id_articulo = $_GET['id_articulo'];

        if (!is_numeric($id_articulo)) {
            $response['message'] = "Formulario no válido";
            echo json_encode($response);
            exit;
        }
        $id_articulo = (int) $id_articulo;

        $stmt = $conexion->prepare("DELETE FROM carrito WHERE id = ? AND id_usuario = ?");
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta: " . $conexion->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("ii", $id_articulo, $_SESSION['user_id']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            $response['message'] = "Artículo no encontrado";
        } else {
            $response['status'] = "success";
            $response['message'] = "Artículo eliminado";
        }
        break;
        break;
}

echo json_encode($response);
