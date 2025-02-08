<?php
require '../config/db.php'; // Conexión a la base de datos

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT u.id, u.nombre_usuario, u.foto FROM usuarios u WHERE u.tipo = 'estudiante'");
    $stmt->execute();
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($estudiantes as &$estudiante) {
    if (!empty($estudiante['foto'])) {
        // Añadir el prefijo "/dgp/" a las rutas relativas
        $estudiante['foto'] = '/dgp/uploads/' . basename($estudiante['foto']);
    }

}


    echo json_encode(['success' => true, 'data' => $estudiantes]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener los estudiantes.']);
}

