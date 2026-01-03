<?php
header('Content-Type: application/json');
session_start();
require_once 'conexion/db.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'administrador') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

try {
    $sql = "SELECT id, nombre_completo, usuario, email, rol, fecha_creacion as created_at 
            FROM usuarios 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Normalizar el rol a formato con mayúscula inicial
    foreach ($users as &$user) {
        $user['rol'] = ucfirst($user['rol']);
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error al obtener usuarios: ' . $e->getMessage()]);
}
?>