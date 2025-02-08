<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'] ?? null;
    $password = $_POST['password'] ?? null;
    $foto = $_FILES['foto'] ?? null;

    if (!$nombre_usuario || !$password || !$foto) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Validar que la contraseña sea numérica y de máximo 6 caracteres
    if (!ctype_digit($password) || strlen($password) > 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe ser numérica y de máximo 6 caracteres.']);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Verificar si el nombre de usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->execute(['nombre_usuario' => $nombre_usuario]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El usuario ya se encuentra registrado.']);
            exit;
        }

        // Manejar la foto
        $directorioDestino = '../uploads/';
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio de destino.']);
                exit;
            }
        }

        $nombreArchivo = uniqid() . '-' . basename($foto['name']);
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        if (!move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {
            echo json_encode(['success' => false, 'message' => 'Error al subir la foto.']);
            exit;
        }

        $rutaFoto = '../uploads/' . $nombreArchivo;

        // Insertar datos en la base de datos sin los pictogramas
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, password, tipo, foto) VALUES (:nombre_usuario, :password, 'estudiante', :foto)");
        $stmt->execute([
            'nombre_usuario' => $nombre_usuario,
            'password' => $passwordHash,
            'foto' => $rutaFoto
        ]);

        echo json_encode(['success' => true, 'message' => 'Estudiante registrado exitosamente.']);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // Violación de clave única
            echo json_encode(['success' => false, 'message' => 'El usuario ya se encuentra registrado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el estudiante. Inténtelo nuevamente.']);
        }
    }

    exit;
}

echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
exit;
?>
