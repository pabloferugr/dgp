<?php  include 'auth_check.php';
// Obtener los datos del profesor
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT nombre_usuario, foto FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Configurar valores por defecto
$fotoUsuario = $user['foto'] ?? '/dgp/uploads/default-avatar.png';
$nombreUsuario = $user['nombre_usuario'] ?? 'Profesor';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor</title>
    <link rel="stylesheet" type="text/css" href="/dgp/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/dgp/css/profesor_dashboard.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation" aria-label="Barra de navegación principal">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="<?php echo htmlspecialchars($fotoUsuario); ?>" alt="Foto de perfil del usuario <?php echo htmlspecialchars($nombreUsuario); ?>" class="img-fluid rounded-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                <span class="navbar-text" aria-label="Nombre del usuario"><?php echo htmlspecialchars($nombreUsuario); ?></span>
            </div>
            <div class="ms-auto">
                <a class="btn btn-danger mx-2" href="/dgp/ajax/logout.php" aria-label="Cerrar sesión">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container mt-5" role="main">
        <h1 class="text-center" tabindex="0">Asignar Material</h1>
        <ul class="list-group mt-4" role="list" aria-label="Lista de estudiantes">
            <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                <span>Estudiante 1</span>
                <button class="btn btn-primary btn-sm" aria-label="Asignar material a Estudiante 1">Asignar Material</button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                <span>Estudiante 2</span>
                <button class="btn btn-primary btn-sm" aria-label="Asignar material a Estudiante 2">Asignar Material</button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center" role="listitem">
                <span>Estudiante 3</span>
                <button class="btn btn-primary btn-sm" aria-label="Asignar material a Estudiante 3">Asignar Material</button>
            </li>
        </ul>
    </main>

    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;" aria-live="polite"></div>

    <!-- Scripts -->
    <script src="/dgp/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

