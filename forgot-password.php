<?php
session_start();
require 'conexion/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'forgot_password') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        header("Location: login.php?error=" . urlencode("Por favor ingrese su email"));
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=" . urlencode("Email inválido"));
        exit;
    }
    
    try {
        // Verificar si el email existe
        $sql = "SELECT id, nombre_completo, usuario FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Generar token único
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Guardar token en la base de datos
            $sql = "INSERT INTO password_resets (usuario_id, email, token, expira) 
                    VALUES (:user_id, :email, :token, :expiry)
                    ON DUPLICATE KEY UPDATE token = :token, expira = :expiry, usado = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':user_id' => $user['id'],
                ':email' => $email,
                ':token' => $token,
                ':expiry' => $expiry
            ]);
            
            // Crear enlace de recuperación
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $resetLink = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/reset-password.php?token=' . $token;
            
            // ========== ENVIAR EMAIL (OPCIÓN 1: PHPMailer) ==========
            // Si tienes PHPMailer instalado, descomenta esto:
            /*
            require 'vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Tu servidor SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'tu-email@gmail.com';
                $mail->Password = 'tu-contraseña-de-aplicación';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('noreply@fajardoshop.com', 'FajardoShop');
                $mail->addAddress($email, $user['nombre_completo']);
                
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de Contraseña - FajardoShop';
                $mail->Body = "
                    <h2>Recuperación de Contraseña</h2>
                    <p>Hola {$user['nombre_completo']},</p>
                    <p>Recibimos una solicitud para restablecer tu contraseña.</p>
                    <p>Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                    <p><a href='$resetLink' style='background: #ff6b9d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>Restablecer Contraseña</a></p>
                    <p>O copia este enlace en tu navegador:</p>
                    <p style='word-break: break-all;'>$resetLink</p>
                    <p><strong>Este enlace expira en 1 hora.</strong></p>
                    <p>Si no solicitaste este cambio, ignora este mensaje.</p>
                    <hr>
                    <p style='color: #666; font-size: 12px;'>FajardoShop © 2025</p>
                ";
                
                $mail->send();
                header("Location: login.php?success=" . urlencode("Se ha enviado un enlace de recuperación a tu email"));
                exit;
                
            } catch (Exception $e) {
                header("Location: login.php?error=" . urlencode("Error al enviar el email: " . $mail->ErrorInfo));
                exit;
            }
            */
            
            // ========== OPCIÓN 2: Sin servidor de email (para pruebas locales) ==========
            // Mostrar el enlace directamente (solo para desarrollo)
            $_SESSION['reset_link'] = $resetLink;
            header("Location: login.php?success=" . urlencode("Enlace de recuperación generado (modo desarrollo)"));
            exit;
            
        } else {
            // Por seguridad, no revelar si el email existe o no
            header("Location: login.php?success=" . urlencode("Si el email existe, recibirás un enlace de recuperación"));
            exit;
        }
        
    } catch (PDOException $e) {
        error_log("Error en recuperación de contraseña: " . $e->getMessage());
        header("Location: login.php?error=" . urlencode("Error del sistema. Intente más tarde"));
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>