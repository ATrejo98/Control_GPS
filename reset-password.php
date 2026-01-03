<?php
session_start();
require 'conexion/db.php';

$token = $_GET['token'] ?? '';
$error = '';
$expired = false;

if (empty($token)) {
    header("Location: login.php?error=" . urlencode("Token inválido"));
    exit;
}

// Verificar token
try {
    $sql = "SELECT pr.*, u.nombre_completo, u.usuario 
            FROM password_resets pr 
            JOIN usuarios u ON pr.usuario_id = u.id 
            WHERE pr.token = :token AND pr.usado = 0 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':token' => $token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$reset) {
        header("Location: login.php?error=" . urlencode("Token inválido o ya usado"));
        exit;
    }
    
    // Verificar si expiró
    if (strtotime($reset['expira']) < time()) {
        $expired = true;
    }
    
} catch (PDOException $e) {
    header("Location: login.php?error=" . urlencode("Error del sistema"));
    exit;
}

// Procesar nueva contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($newPassword) || empty($confirmPassword)) {
        $error = "Complete todos los campos";
    } elseif (strlen($newPassword) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Las contraseñas no coinciden";
    } else {
        try {
            // Actualizar contraseña
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $sql = "UPDATE usuarios SET password = :password WHERE id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':password' => $hashedPassword,
                ':user_id' => $reset['usuario_id']
            ]);
            
            // Marcar token como usado
            $sql = "UPDATE password_resets SET usado = 1 WHERE token = :token";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':token' => $token]);
            
            header("Location: login.php?success=" . urlencode("Contraseña actualizada exitosamente"));
            exit;
            
        } catch (PDOException $e) {
            $error = "Error al actualizar la contraseña";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Restablecer Contraseña - FajardoShop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-gradient: linear-gradient(135deg, #ff6b9d 0%, #c44fc1 100%);
            --secondary-gradient: linear-gradient(135deg, #ffd93d 0%, #ff9a3c 100%);
            --success-gradient: linear-gradient(135deg, #6bcf7f 0%, #4fd69c 100%);
            --danger-gradient: linear-gradient(135deg, #ff8a95 0%, #ff6b6b 100%);
            --warning-gradient: linear-gradient(135deg, #ffd93d 0%, #ff9a3c 100%);
            --info-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --icon-color: #000000;
            --shadow-soft: 0 20px 60px rgba(0, 0, 0, 0.15);
            --bg-gradient-1: #ffecd2;
            --bg-gradient-2: #fcb69f;
            --bg-gradient-3: #ff9a9e;
            --bg-gradient-4: #fecfef;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(-45deg, var(--bg-gradient-1), var(--bg-gradient-2), var(--bg-gradient-3), var(--bg-gradient-4), var(--bg-gradient-4), var(#bfdbfe 100%));
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* ==================== MODO OSCURO ==================== */
        body.dark-mode {
            --glass-bg: rgba(30, 30, 45, 0.95);
            --glass-border: rgba(102, 126, 234, 0.3);
            --text-primary: #e0e0e0;
            --text-secondary: #b0b0b0;
            --icon-color: #e0e0e0;
            --shadow-soft: 0 20px 60px rgba(0, 0, 0, 0.5);
            --bg-gradient-1: #1a1a2e;
            --bg-gradient-2: #16213e;
            --bg-gradient-3: #0f3460;
            --bg-gradient-4: #1a1a2e;
            background: linear-gradient(-45deg, var(--bg-gradient-1), var(--bg-gradient-2), var(--bg-gradient-3), var(--bg-gradient-4), var(--bg-gradient-4), var(--bg-gradient-3)) !important;
        }
        
        body.dark-mode .form-input {
            background: rgba(40, 40, 60, 0.95);
            border-color: rgba(102, 126, 234, 0.3);
            color: #e0e0e0;
        }
        
        body.dark-mode .form-input:focus {
            background: rgba(45, 45, 65, 1);
            border-color: #667eea;
        }
        
        body.dark-mode .form-input::placeholder {
            color: #888;
        }
        
        body.dark-mode .password-requirements {
            background: rgba(102, 126, 234, 0.15);
            border-color: rgba(102, 126, 234, 0.4);
            color: #a8c0ff;
        }
        
        body.dark-mode .strength-meter .strength-bar {
            background: rgba(40, 40, 60, 0.8);
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.08)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.12)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }
        
        /* Formas flotantes */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 60px;
            height: 60px;
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 100px;
            height: 100px;
            left: 80%;
            top: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            left: 70%;
            top: 70%;
            animation-delay: 4s;
        }
        
        .shape:nth-child(4) {
            width: 80px;
            height: 80px;
            left: 20%;
            top: 80%;
            animation-delay: 6s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-20px) rotate(90deg) scale(1.1);
            }
            50% {
                transform: translateY(0) rotate(180deg) scale(0.9);
            }
            75% {
                transform: translateY(20px) rotate(270deg) scale(1.1);
            }
        }
        
        /* Botón de modo oscuro */
        .theme-toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .theme-toggle-btn:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.6);
        }
        
        .theme-toggle-btn:active {
            transform: scale(0.95);
        }
        
        .reset-container {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 10;
        }
        
        .reset-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
            transform: translateY(0);
            transition: all 0.3s ease;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .reset-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
        }
        
        .reset-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--info-gradient);
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--info-gradient);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .logo-text {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .logo-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        
        .form-input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 3rem 1rem 3rem;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-primary);
            font-family: inherit;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--icon-color);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus + .form-icon {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--icon-color);
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }
        
        .btn {
            width: 100%;
            padding: 1rem 2rem;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: var(--info-gradient);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn:hover::before {
            left: 0;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }
        
        .btn:active {
            transform: translateY(-1px);
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-error {
            background: linear-gradient(135deg, rgba(255, 65, 108, 0.1) 0%, rgba(255, 75, 43, 0.1) 100%);
            color: #d63031;
            border: 1px solid rgba(255, 65, 108, 0.3);
        }
        
        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%);
            color: #f39c12;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .back-link:hover {
            color: #667eea;
            transform: translateX(-3px);
        }
        
        .back-link i {
            transition: transform 0.3s ease;
        }
        
        .back-link:hover i {
            transform: translateX(-3px);
        }
        
        .password-requirements {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #667eea;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .password-requirements strong {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        
        .password-requirements li {
            margin: 0.5rem 0;
            line-height: 1.6;
        }
        
        .password-requirements li::marker {
            color: #667eea;
        }
        
        /* Medidor de fuerza de contraseña */
        .strength-meter {
            margin-top: 0.5rem;
        }
        
        .strength-meter-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        
        .strength-bar {
            width: 100%;
            height: 6px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        
        .strength-bar-fill {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        
        .strength-weak {
            width: 33%;
            background: var(--danger-gradient);
        }
        
        .strength-medium {
            width: 66%;
            background: var(--warning-gradient);
        }
        
        .strength-strong {
            width: 100%;
            background: var(--success-gradient);
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-4 {
            margin-bottom: 2rem;
        }
        
        .mt-4 {
            margin-top: 2rem;
        }
        
        /* ==================== RESPONSIVE ==================== */
        
        @media (max-width: 768px) {
            .reset-card {
                padding: 2.5rem 2rem;
            }
            
            .theme-toggle-btn {
                width: 48px;
                height: 48px;
                font-size: 1.15rem;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
            
            .logo-text {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }
            
            .reset-container {
                max-width: 100%;
            }
            
            .theme-toggle-btn {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
                top: 12px;
                right: 12px;
            }
            
            .reset-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }
            
            .logo-section {
                margin-bottom: 2rem;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                border-radius: 15px;
            }
            
            .logo-text {
                font-size: 1.5rem;
            }
            
            .logo-subtitle {
                font-size: 0.9rem;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-label {
                font-size: 0.85rem;
            }
            
            .form-input {
                padding: 0.875rem 2.75rem 0.875rem 2.5rem;
                font-size: 16px;
                border-radius: 12px;
            }
            
            .form-icon {
                left: 0.875rem;
                font-size: 1rem;
            }
            
            .password-toggle {
                right: 0.875rem;
                font-size: 1rem;
            }
            
            .btn {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
                border-radius: 12px;
            }
            
            .alert {
                padding: 0.875rem;
                border-radius: 12px;
                font-size: 0.85rem;
            }
            
            .password-requirements {
                padding: 1rem;
                font-size: 0.85rem;
            }
            
            .back-link {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 374px) {
            .reset-card {
                padding: 1.75rem 1.25rem;
            }
            
            .logo-icon {
                width: 55px;
                height: 55px;
                font-size: 1.35rem;
            }
            
            .logo-text {
                font-size: 1.35rem;
            }
            
            .form-input {
                padding: 0.75rem 2.5rem 0.75rem 2.25rem;
                font-size: 15px;
            }
            
            .theme-toggle-btn {
                width: 42px;
                height: 42px;
                font-size: 1rem;
                top: 10px;
                right: 10px;
            }
        }
        
        @media (max-width: 320px) {
            .reset-card {
                padding: 1.5rem 1rem;
                border-radius: 15px;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .logo-text {
                font-size: 1.25rem;
            }
            
            .logo-subtitle {
                font-size: 0.8rem;
            }
            
            .form-input {
                padding: 0.7rem 2.25rem 0.7rem 2.1rem;
                font-size: 14px;
            }
            
            .btn {
                padding: 0.7rem 1rem;
                font-size: 0.9rem;
            }
            
            .theme-toggle-btn {
                width: 38px;
                height: 38px;
                font-size: 0.95rem;
            }
            
            .password-requirements {
                padding: 0.875rem;
                font-size: 0.8rem;
            }
        }
        
        @media (min-width: 1200px) {
            .reset-container {
                max-width: 550px;
            }
            
            .reset-card {
                padding: 3.5rem 3rem;
            }
            
            .theme-toggle-btn {
                width: 55px;
                height: 55px;
                font-size: 1.4rem;
                top: 25px;
                right: 25px;
            }
        }
        
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }
            
            .reset-card {
                padding: 1.5rem 2rem;
                max-height: 95vh;
                overflow-y: auto;
            }
            
            .logo-section {
                margin-bottom: 1rem;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }
            
            .logo-text {
                font-size: 1.25rem;
            }
            
            .logo-subtitle {
                font-size: 0.85rem;
            }
            
            .form-group {
                margin-bottom: 1rem;
            }
            
            .password-requirements {
                margin-top: 1rem;
                padding: 1rem;
            }
            
            .theme-toggle-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                top: 10px;
                right: 10px;
            }
        }
        
        @media (hover: none) and (pointer: coarse) {
            .reset-card:hover {
                transform: translateY(0);
                box-shadow: var(--shadow-soft);
            }
            
            .btn:hover {
                transform: translateY(0);
            }
            
            .btn:active {
                transform: translateY(2px);
            }
            
            .form-input:focus {
                transform: translateY(0);
            }
            
            .theme-toggle-btn:hover {
                transform: scale(1);
            }
            
            .theme-toggle-btn:active {
                transform: scale(0.9);
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <!-- Botón de cambio de tema -->
    <button class="theme-toggle-btn" onclick="toggleTheme()" aria-label="Cambiar tema">
        <i class="fas fa-moon" id="themeIcon"></i>
    </button>
    
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="reset-container">
        <div class="reset-card">
            <div class="logo-section text-center">
                <div class="logo-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="logo-text">Restablecer Contraseña</h1>
                <p class="logo-subtitle">Crea una nueva contraseña segura</p>
            </div>
            
            <?php if ($expired): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><strong>Enlace expirado.</strong> Este enlace de recuperación ya no es válido. Por favor, solicita uno nuevo.</span>
                </div>
                <button onclick="window.location.href='login.php'" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver al Login
                </button>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <a href="login.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al login
                </a>
                
                <form method="POST" id="resetForm">
                    <div class="form-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <div class="form-input-wrapper">
                            <input type="password" class="form-input" name="new_password" id="new_password" 
                                   required placeholder="Mínimo 6 caracteres" minlength="6"
                                   oninput="checkPasswordStrength()">
                            <i class="form-icon fas fa-lock"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')" aria-label="Mostrar contraseña">
                                <i class="fas fa-eye" id="new_password-eye"></i>
                            </button>
                        </div>
                        <div class="strength-meter" id="strengthMeter" style="display: none;">
                            <div class="strength-meter-label">Seguridad: <span id="strengthText">Débil</span></div>
                            <div class="strength-bar">
                                <div class="strength-bar-fill" id="strengthBar"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirmar Contraseña</label>
                        <div class="form-input-wrapper">
                            <input type="password" class="form-input" name="confirm_password" id="confirm_password" 
                                   required placeholder="Repite la contraseña" minlength="6"
                                   oninput="checkPasswordMatch()">
                            <i class="form-icon fas fa-lock"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')" aria-label="Mostrar contraseña">
                                <i class="fas fa-eye" id="confirm_password-eye"></i>
                            </button>
                        </div>
                        <div id="matchMessage" style="margin-top: 0.5rem; font-size: 0.85rem; font-weight: 600;"></div>
                    </div>
                    
                    <div class="password-requirements">
                        <strong><i class="fas fa-shield-alt"></i> Requisitos de Seguridad:</strong>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Mínimo 6 caracteres</li>
                            <li><i class="fas fa-check-circle"></i> Recomendado: incluir mayúsculas y minúsculas</li>
                            <li><i class="fas fa-check-circle"></i> Recomendado: incluir números y símbolos</li>
                            <li><i class="fas fa-times-circle"></i> Evita usar información personal</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4" id="submitBtn">
                        <i class="fas fa-check-circle"></i> Actualizar Contraseña
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // ==================== MODO NOCHE ====================
        (function() {
            const currentTheme = localStorage.getItem('fajardoshop-theme');
            const themeIcon = document.getElementById('themeIcon');

            if (currentTheme === 'dark') {
                document.body.classList.add('dark-mode');
                if (themeIcon) {
                    themeIcon.className = 'fas fa-sun';
                }
            }
        })();

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const themeIcon = document.getElementById('themeIcon');

            if (document.body.classList.contains('dark-mode')) {
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('fajardoshop-theme', 'dark');
            } else {
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('fajardoshop-theme', 'light');
            }
        }

        // Atajo de teclado Ctrl+Shift+D
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggleTheme();
            }
        });

        // ==================== FUNCIONES DE CONTRASEÑA ====================
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Verificar fuerza de contraseña
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            if (password.length === 0) {
                strengthMeter.style.display = 'none';
                return;
            }
            
            strengthMeter.style.display = 'block';
            
            let strength = 0;
            
            // Criterios de fuerza
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            // Actualizar visual
            strengthBar.className = 'strength-bar-fill';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Débil';
                strengthText.style.color = '#ff6b6b';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Media';
                strengthText.style.color = '#ff9a3c';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Fuerte';
                strengthText.style.color = '#6bcf7f';
            }
            
            checkPasswordMatch();
        }

        // Verificar que las contraseñas coincidan
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchMessage = document.getElementById('matchMessage');
            
            if (confirmPassword.length === 0) {
                matchMessage.textContent = '';
                return;
            }
            
            if (newPassword === confirmPassword) {
                matchMessage.innerHTML = '<i class="fas fa-check-circle" style="color: #6bcf7f;"></i> Las contraseñas coinciden';
                matchMessage.style.color = '#6bcf7f';
            } else {
                matchMessage.innerHTML = '<i class="fas fa-times-circle" style="color: #ff6b6b;"></i> Las contraseñas no coinciden';
                matchMessage.style.color = '#ff6b6b';
            }
        }

        // Validar formulario antes de enviar
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass.length < 6) {
                e.preventDefault();
                alert('❌ La contraseña debe tener al menos 6 caracteres');
                document.getElementById('new_password').focus();
                return false;
            }
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('❌ Las contraseñas no coinciden. Por favor, verifica.');
                document.getElementById('confirm_password').focus();
                return false;
            }
            
            // Mostrar loading en el botón
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
        });

        // Auto-focus en el primer campo
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.getElementById('new_password');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Prevenir copiar/pegar en campo de confirmación (opcional, mejora seguridad)
        document.getElementById('confirm_password')?.addEventListener('paste', function(e) {
            e.preventDefault();
            alert('Por favor, escribe tu contraseña manualmente para confirmar.');
        });

        // Mostrar/ocultar requisitos al hacer focus
        document.getElementById('new_password')?.addEventListener('focus', function() {
            document.querySelector('.password-requirements').style.animation = 'pulse 0.3s ease';
        });

        console.log('🔐 Sistema de restablecimiento de contraseña cargado');
        console.log('🌓 Modo Noche habilitado - Usa Ctrl+Shift+D');
    </script>
</body>
</html>