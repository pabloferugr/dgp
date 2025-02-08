<?php
require '../config/db.php';
session_start();

// Redirigir al inicio si no hay usuario en la sesión
if (!isset($_SESSION['user'])) {
    header('Location: /dgp/index.php?page=home');
    exit;
}

// Verificar el tipo de usuario y la página actual
$currentPage = basename($_SERVER['PHP_SELF']);
$userType = $_SESSION['user']['tipo'];

$allowedPages = [
    'admin' => ['admin_dashboard.php'],
    'estudiante' => ['estudiante_dashboard.php'],
    'profesor' => ['profesor_dashboard.php']
];

if (!isset($allowedPages[$userType]) || !in_array($currentPage, $allowedPages[$userType])) {
    header('Location: /dgp/index.php?page=home');
    exit;
}

?>
