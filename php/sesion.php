<?php
session_start();
$response = array('username' => null);

if (!empty($_SESSION['username'])) {
    $response['username'] = $_SESSION['username'];
}

echo json_encode($response);
