<?php
session_start();
require 'conexion/db.php'; // tu archivo de conexión

// Si ya está logueado, redirige al index
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validación del lado del servidor
    if (empty($username)) {
        $error = "El usuario o correo es obligatorio.";
    } elseif (empty($password)) {
        $error = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Buscar usuario en la base de datos
        $sql = "SELECT id, usuario, email, password, rol FROM usuarios WHERE usuario = :username OR email = :username LIMIT 1";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Login exitoso
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['rol'] = $user['rol'];
                
                // Registrar último acceso (opcional)
                $updateSql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([':id' => $user['id']]);
                
                $success = "Login exitoso. Redirigiendo...";
                header("Refresh: 1; url=index.php");
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos. Intente más tarde.";
            // Log del error (no mostrar detalles al usuario)
            error_log("Error de login: " . $e->getMessage());
        }
    }
}
?>