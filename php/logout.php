<?php
session_start();
$response = array('status' => '', 'message' => '');

// Destruir sesión
session_unset();
session_destroy();

// Redirigir a index.html
header("Location: /index.html");
exit();
