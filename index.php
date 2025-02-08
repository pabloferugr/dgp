<?php
session_start();

// Si hay una sesión activa, redirigir al dashboard correspondiente
if (isset($_SESSION['user'])) {
    $tipo = $_SESSION['user']['tipo'];
    header("Location: /dgp/php/{$tipo}_dashboard.php");
    exit;
}

// Verificar la página solicitada
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
    break;
    case 'admin_dashboard':
    if (isset($_SESSION['user']) && $_SESSION['user']['tipo'] === 'admin') {
        require 'php/admin_dashboard.php';
    } else {
        header('Location: /dgp/index.php?page=home');
    }
    break;
    case 'profesor_dashboard':
    if (isset($_SESSION['user']) && $_SESSION['user']['tipo'] === 'profesor') {
        require 'php/profesor_dashboard.php';
    } else {
        header('Location: /dgp/index.php?page=home');
    }
    break;
    case 'estudiante_dashboard':
    if (isset($_SESSION['user']) && $_SESSION['user']['tipo'] === 'estudiante') {
        require 'php/estudiante_dashboard.php';
    } else {
        header('Location: /dgp/index.php?page=home');
    }
    break;
    default:
    header('Location: /dgp/index.php?page=home');
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda PTVAl San Rafael</title>
    <!-- Enlace a Google Fonts para Patrick Hand -->
    <!--<link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">-->
    <link href="https://fonts.googleapis.com/css2?family=Schoolbell&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" type="text/css" href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a Font Awesome para iconos -->
    <link rel="stylesheet" type="text/css" href="fontawesome-free-6.7.2-web/css/all.min.css">

</head>
<body>
    <div class="main-container" id="app-container" role="main" aria-live="polite">


     <!-- Contenedor del título y botones -->
     <div class="header-container">
        <h1 id="main-title">Agenda PTVAl San Rafael</h1>
        <div class="header-buttons">
            <button class="btn btn-primary section admin" data-target="adminLogin">Administrador</button>
            <button class="btn btn-success section profesor" data-target="profesorLogin">Profesor</button>
        </div>
    </div>


    <!-- Secciones que ocupan el resto del espacio -->
    <div class="sections-container">
        <!-- Sección Administrador -->
           <!-- <div class="section admin" data-target="adminLogin" tabindex="0">
                <h2>Administrador</h2>
            </div>-->

            <!-- Sección Profesor -->
            <!--<div class="section profesor" data-target="profesorLogin" tabindex="0">
                <h2>Profesor</h2>
            </div>-->

            <!-- Sección Estudiante -->
            <div class="section estudiante" data-target="listaEstudiantes" tabindex="0">
                <img src="/dgp/images/avatar.webp" alt="Avatar Estudiante" class="estudiante-avatar">

                <h2>Estudiante</h2>
                <button class="btn btn-success" id="startVoiceRecognition">
                    <i class="fas fa-microphone"></i> Iniciar Sesión por Voz
                </button>
                
            </div>
        </div>
    </div>

    <div id="modales" role="region" aria-label="Modales"></div>

    <script src="/dgp/js/jquery-3.7.1.min.js"></script>
    <script src="/dgp/js/popper.min.js"></script>
    <script src="/dgp/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
   
    <script src="/dgp/js/main.js"></script>
</body>
</html>

