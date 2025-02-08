<?php
session_start();

// Eliminar datos de la sesión
session_unset();
session_destroy();

// Eliminar cookies
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_name', '', time() - 3600, '/');
setcookie('user_type', '', time() - 3600, '/');

// Redirigir al inicio
header('Location: /dgp/index.php?page=home');
exit;
?>
