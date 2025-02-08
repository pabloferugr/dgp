<?php
session_start();
require '../config/db.php';

// Capturar cualquier salida inesperada
ob_start();

// Configurar el encabezado de contenido JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nombre_usuario'] ?? null;
    $password = $_POST['password'] ?? null;
    $expectedTipo = $_POST['tipo'] ?? null;

    if (!$username || !$password || !$expectedTipo) {
        ob_end_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios.'
        ]);
        exit;
    }

    try {
        // Buscar el usuario en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :username AND tipo = :tipo");
        $stmt->execute([
            'username' => $username,
            'tipo' => $expectedTipo
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificar si la contraseña es válida
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                // Si la contraseña no está hasheada, hashearla y actualizarla
                if ($password === $user['password']) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE usuarios SET password = :hashedPassword WHERE id = :id");
                    $updateStmt->execute([
                        'hashedPassword' => $hashedPassword,
                        'id' => $user['id']
                    ]);
                }

                // Guardar datos en la sesión
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nombre_usuario' => $user['nombre_usuario'],
                    'tipo' => $user['tipo']
                ];

                // Forzar escritura de la sesión antes de la respuesta
                session_write_close();

                ob_end_clean(); // Limpia cualquier salida previa
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'redirect' => "/dgp/php/{$expectedTipo}_dashboard.php"
                ]);
                exit;
            } else {
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Nombre de usuario, contraseña o rol incorrectos.'
                ]);
                exit;
            }
        } else {
            ob_end_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Nombre de usuario no encontrado.'
            ]);
            exit;
        }
    } catch (Exception $e) {
        ob_end_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Error del servidor: ' . $e->getMessage()
        ]);
        exit;
    }
} else {
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}
?>
