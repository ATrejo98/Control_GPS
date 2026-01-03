<?php
header('Content-Type: application/json');
session_start();
require_once 'conexion/db.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'administrador') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rol = strtolower($_POST['rol'] ?? 'usuario'); // Convertir a minúsculas para el ENUM
    
    if (empty($id) || empty($usuario) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }
    
    try {
        // Verificar si el usuario o email ya existen (excepto el actual)
        $sql = "SELECT id FROM usuarios WHERE (usuario = :usuario OR email = :email) AND id != :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':usuario' => $usuario, ':email' => $email, ':id' => $id]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'El usuario o email ya están en uso']);
            exit;
        }
        
        // Actualizar usuario
        $sql = "UPDATE usuarios 
                SET nombre_completo = :nombre_completo, 
                    usuario = :usuario, 
                    email = :email, 
                    rol = :rol 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre_completo' => $nombre_completo,
            ':usuario' => $usuario,
            ':email' => $email,
            ':rol' => $rol,
            ':id' => $id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar usuario: ' . $e->getMessage()]);
    }
}
?>