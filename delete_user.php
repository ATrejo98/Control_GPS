<?php
session_start();
require_once 'conexion/db.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'administrador') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    
    if (empty($user_id)) {
        echo json_encode(['success' => false, 'error' => 'ID de usuario no proporcionado']);
        exit;
    }
    
    // No permitir que un usuario se elimine a sí mismo
    if ($user_id == $_SESSION['usuario_id']) {
        echo json_encode(['success' => false, 'error' => 'No puedes eliminar tu propia cuenta']);
        exit;
    }
    
    try {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar usuario: ' . $e->getMessage()]);
    }
}
?>