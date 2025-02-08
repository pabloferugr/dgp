<?php
// Detectar el entorno según el servidor
if ($_SERVER['SERVER_NAME'] === 'localhost') { // Entorno local (XAMPP)
    $host = 'localhost';
    $db = 'ProyectoEducativo';
    $user = 'root';
    $pass = '';
} else { // Servidor en producción
    $host = 'localhost'; // Cambia si es necesario
    $db = 'proyectoeducativo';
    $user = 'myappdgp4b';
    $pass = 'zblRbcMp';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>
