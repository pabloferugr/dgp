<?php  
include 'auth_check.php';
// Obtener los datos del administrador
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT nombre_usuario, foto FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Configurar valores por defecto
$fotoUsuario = $user['foto'] ?? '/dgp/uploads/default-avatar.png';
$nombreUsuario = $user['nombre_usuario'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet"> <!-- Asegúrate de cargar la fuente -->
    <link rel="stylesheet" type="text/css" href="/dgp/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&display=swap" rel="stylesheet">

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

    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;" aria-live="polite"></div>

    <!-- Contenido principal -->
    <main class="container mt-5" role="main">
        <h1 tabindex="0">Panel de Administración</h1>
        <div class="text-center mt-4">
            <button class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#registerStudentModal" aria-label="Registrar un nuevo estudiante">Registrar Estudiante</button>
            <button class="btn btn-success mx-2" data-bs-toggle="modal" data-bs-target="#registerTeacherModal" aria-label="Registrar un nuevo profesor">Registrar Profesor</button>
        </div>
    </main>

    <!-- Modales -->
    <div id="modales" role="dialog" aria-live="off"></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/dgp/js/admin_dashboard.js"></script>
</body>
</html>
