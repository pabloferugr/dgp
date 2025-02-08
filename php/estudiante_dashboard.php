<?php  include 'auth_check.php';

// Obtener los datos del usuario
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT nombre_usuario, foto FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Configurar valores por defecto
$fotoUsuario = $userData['foto'] ?? '/dgp/images/default-avatar.png';
$nombreUsuario = $userData['nombre_usuario'] ?? 'Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante</title>
    <link rel="stylesheet" type="text/css" href="/dgp/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/dgp/css/estudiante_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation" aria-label="Barra de navegación principal">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="<?php echo htmlspecialchars($userData['foto']); ?>" 
                     alt="Foto de perfil de <?php echo htmlspecialchars($nombreUsuario); ?>" 
                     class="img-fluid rounded-circle" 
                     style="width: 100px; height: 100px;">
                <span class="navbar-text" aria-label="Nombre del usuario"><?php echo htmlspecialchars($nombreUsuario); ?></span>
            </div>
            <div class="ms-auto">
                <a class="btn btn-danger" href="/dgp/ajax/logout.php" aria-label="Cerrar sesión">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container mt-5" role="main">
        <h1 class="text-center" tabindex="0">Bienvenido, Estudiante</h1>
        <p class="text-center" tabindex="0">Aquí puedes ver las tareas asignadas a ti.</p>

        <!-- Lista de tareas -->
        <section class="mt-4" aria-labelledby="task-list-heading">
            <h2 id="task-list-heading" tabindex="0">Tus Tareas</h2>
            <ul class="list-group" role="list" aria-label="Lista de tareas">
                <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                    <span>Tarea 1</span>
                    <span class="badge bg-primary rounded-pill" aria-label="Estado: Pendiente">Pendiente</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                    <span>Tarea 2</span>
                    <span class="badge bg-success rounded-pill" aria-label="Estado: Completada">Completada</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                    <span>Tarea 3</span>
                    <span class="badge bg-warning rounded-pill" aria-label="Estado: En progreso">En Progreso</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                    <span>Tarea 4</span>
                    <span class="badge bg-primary rounded-pill" aria-label="Estado: Pendiente">Pendiente</span>
                </li>
            </ul>
        </section>
    </main>

    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;" aria-live="polite"></div>

    <!-- Scripts -->
    <script src="/dgp/js/jquery-3.7.1.min.js"></script>
    <script src="/dgp/js/popper.min.js"></script>
    <script src="/dgp/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

