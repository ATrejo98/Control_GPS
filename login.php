<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

// Manejar mensajes de error y éxito desde la URL
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Iniciar Sesión - FORZA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
     <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            /* Colores de Honduras: Azul y Blanco con acentos militares */
            --primary-gradient: linear-gradient(135deg, #0038A8 0%, #005cbf 100%);
            --secondary-gradient: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --accent-gradient: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            --success-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --danger-gradient: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(0, 56, 168, 0.2);
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --icon-color: #0038A8;
            --shadow-soft: 0 20px 60px rgba(0, 56, 168, 0.2);
            --bg-gradient-1: #e0f2fe;
            --bg-gradient-2: #bfdbfe;
            --bg-gradient-3: #93c5fd;
            --bg-gradient-4: #dbeafe;
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
            background: linear-gradient(-45deg, var(--bg-gradient-1), var(--bg-gradient-2), var(--bg-gradient-3), var(--bg-gradient-4), var(--bg-gradient-4), var(--bg-gradient-3));
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

        body.dark-mode {
            --glass-bg: rgba(17, 24, 39, 0.95);
            --glass-border: rgba(59, 130, 246, 0.3);
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --icon-color: #60a5fa;
            --shadow-soft: 0 20px 60px rgba(0, 0, 0, 0.5);
            --bg-gradient-1: #0f172a;
            --bg-gradient-2: #1e293b;
            --bg-gradient-3: #1e3a8a;
            --bg-gradient-4: #1e293b;
            background: linear-gradient(-45deg, var(--bg-gradient-1), var(--bg-gradient-2), var(--bg-gradient-3), var(--bg-gradient-4), var(--bg-gradient-4), var(--bg-gradient-3)) !important;
        }

        body.dark-mode .form-input {
            background: rgba(30, 41, 59, 0.95);
            border-color: rgba(59, 130, 246, 0.3);
            color: #f3f4f6;
        }

        body.dark-mode .form-input:focus {
            background: rgba(30, 41, 59, 1);
            border-color: #3b82f6;
        }

        body.dark-mode .form-input::placeholder {
            color: #9ca3af;
        }

        body.dark-mode .divider::before {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        body.dark-mode .footer-text {
            color: #d1d5db;
            border-top-color: rgba(59, 130, 246, 0.2);
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(0,56,168,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(0,56,168,0.03)"/><circle cx="50" cy="10" r="0.5" fill="rgba(0,56,168,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }

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
            background: rgba(0, 56, 168, 0.08);
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

        .login-container {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 10;
        }

        .theme-toggle-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 8px 20px rgba(0, 56, 168, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 12px 30px rgba(0, 56, 168, 0.6);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }

        .login-card {
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
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 80px rgba(0, 56, 168, 0.3);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-soft);
            animation: pulse 2s infinite;
            position: relative;
        }

        .logo-icon i {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .logo-text {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .logo-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
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
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid rgba(0, 56, 168, 0.2);
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-primary);
        }

        .form-input:focus {
            outline: none;
            border-color: #0038A8;
            box-shadow: 0 0 0 3px rgba(0, 56, 168, 0.1);
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
            color: #0038A8;
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
        }

        .password-toggle:hover {
            color: #0038A8;
            transform: translateY(-50%) scale(1.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
        }

        .form-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #0038A8;
        }

        .forgot-link {
            color: #0038A8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: #005cbf;
            text-decoration: underline;
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
            background: var(--primary-gradient);
            color: white;
        }

        .btn-secondary {
            background: var(--accent-gradient);
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
            box-shadow: 0 15px 40px rgba(0, 56, 168, 0.3);
        }

        .btn:active {
            transform: translateY(-1px);
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
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.1) 100%);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
            color: #059669;
            border: 1px solid rgba(5, 150, 105, 0.3);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 56, 168, 0.2), transparent);
        }

        .divider-text {
            background: var(--glass-bg);
            padding: 0 1rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            position: relative;
        }

        .forgot-password-form {
            display: none;
        }

        .forgot-password-form.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .main-form.hidden {
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #0038A8;
            transform: translateX(-3px);
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn.loading .loading-spinner {
            display: inline-block;
        }

        .btn.loading .btn-text {
            display: none;
        }

        .footer-text {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(0, 56, 168, 0.1);
        }

        /* ==================== RESPONSIVE DESIGN ==================== */

        /* Tablets y pantallas medianas */
        @media (max-width: 768px) {
            .login-card {
                padding: 2.5rem 2rem;
                max-width: 420px;
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

        /* Móviles grandes (375px - 480px) */
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
                min-height: 100vh;
            }

            .login-container {
                max-width: 100%;
            }

            .theme-toggle-btn {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
                top: 12px;
                right: 12px;
            }

            .login-card {
                padding: 2rem 1.5rem;
                margin: 0;
                border-radius: 20px;
                max-width: 100%;
            }

            .logo-section {
                margin-bottom: 2rem;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                border-radius: 15px;
                margin-bottom: 0.75rem;
            }

            .logo-text {
                font-size: 1.5rem;
                line-height: 1.3;
            }

            .logo-subtitle {
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
            }

            .form-input {
                padding: 0.875rem 0.875rem 0.875rem 2.5rem;
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

            .form-options {
                flex-direction: column;
                gap: 0.875rem;
                align-items: flex-start;
                margin-bottom: 1.5rem;
                font-size: 0.85rem;
            }

            .checkbox-wrapper {
                gap: 0.4rem;
            }

            .form-checkbox {
                width: 16px;
                height: 16px;
            }

            .alert {
                padding: 0.875rem;
                border-radius: 12px;
                font-size: 0.85rem;
                gap: 0.4rem;
            }

            .divider {
                margin: 1.5rem 0;
            }

            .footer-text {
                font-size: 0.75rem;
                line-height: 1.4;
                margin-top: 1.5rem;
                padding-top: 1.5rem;
            }
        }

        /* Móviles medianos (360px - 374px) */
        @media (max-width: 374px) {
            .login-card {
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

            .logo-subtitle {
                font-size: 0.85rem;
            }

            .form-input {
                padding: 0.75rem 0.75rem 0.75rem 2.25rem;
                font-size: 15px;
            }

            .form-icon {
                left: 0.75rem;
                font-size: 0.95rem;
            }

            .password-toggle {
                right: 0.75rem;
                font-size: 0.95rem;
            }

            .btn {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }

            .theme-toggle-btn {
                width: 42px;
                height: 42px;
                font-size: 1rem;
                top: 10px;
                right: 10px;
            }
        }

        /* Móviles pequeños (320px - 359px) */
        @media (max-width: 320px) {
            body {
                padding: 0.25rem;
            }

            .login-card {
                padding: 1.5rem 1rem;
                border-radius: 15px;
            }

            .logo-section {
                margin-bottom: 1.5rem;
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
                font-size: 0.8rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .form-input {
                padding: 0.7rem 0.7rem 0.7rem 2.1rem;
                font-size: 14px;
                border-radius: 10px;
            }

            .form-icon {
                left: 0.7rem;
                font-size: 0.9rem;
            }

            .password-toggle {
                right: 0.7rem;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.7rem 1rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .form-options {
                font-size: 0.8rem;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
            }

            .form-checkbox {
                width: 14px;
                height: 14px;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.8rem;
                border-radius: 10px;
            }

            .theme-toggle-btn {
                width: 38px;
                height: 38px;
                font-size: 0.95rem;
                top: 8px;
                right: 8px;
            }

            .footer-text {
                font-size: 0.7rem;
                margin-top: 1.25rem;
                padding-top: 1.25rem;
            }
        }

        /* Pantallas grandes */
        @media (min-width: 1200px) {
            .login-container {
                max-width: 500px;
            }

            .login-card {
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

        /* Landscape mode en móviles */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }

            .login-card {
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
                margin-bottom: 0.25rem;
            }

            .logo-subtitle {
                font-size: 0.85rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-options {
                margin-bottom: 1.25rem;
            }

            .footer-text {
                margin-top: 1rem;
                padding-top: 1rem;
                font-size: 0.75rem;
            }

            .theme-toggle-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                top: 10px;
                right: 10px;
            }
        }

        /* Landscape extremo (altura muy pequeña) */
        @media (max-height: 500px) and (orientation: landscape) {
            .login-card {
                padding: 1rem 1.5rem;
            }

            .logo-section {
                margin-bottom: 0.75rem;
            }

            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }

            .logo-text {
                font-size: 1.1rem;
            }

            .logo-subtitle {
                font-size: 0.75rem;
            }

            .form-group {
                margin-bottom: 0.75rem;
            }

            .form-label {
                margin-bottom: 0.25rem;
                font-size: 0.8rem;
            }

            .form-input {
                padding: 0.6rem 0.6rem 0.6rem 2rem;
            }

            .btn {
                padding: 0.6rem 1rem;
            }

            .form-options {
                margin-bottom: 1rem;
                font-size: 0.75rem;
            }

            .footer-text {
                margin-top: 0.75rem;
                padding-top: 0.75rem;
            }
        }

        /* Dispositivos táctiles */
        @media (hover: none) and (pointer: coarse) {
            .login-card:hover {
                transform: translateY(0);
                box-shadow: var(--shadow-soft);
            }

            .btn:hover {
                transform: translateY(0);
                box-shadow: none;
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

        /* Reducción de animaciones */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* iPhone SE y dispositivos muy pequeños */
        @media (max-width: 280px) {
            .login-card {
                padding: 1.25rem 0.75rem;
            }

            .logo-text {
                font-size: 1.1rem;
            }

            .form-input {
                font-size: 13px;
                padding: 0.65rem 0.65rem 0.65rem 2rem;
            }

            .btn {
                font-size: 0.85rem;
            }

            .theme-toggle-btn {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
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

  <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h1 class="logo-text">FORZA</h1>
                <p class="logo-subtitle">Sistema de Control de Gps • Honduras</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Login Principal -->
            <form method="POST" action="validar_login.php" class="main-form" id="loginForm" autocomplete="off">
                <div class="form-group">
                    <label class="form-label">Usuario o Email</label>
                    <div class="form-input-wrapper">
                        <input type="text" class="form-input" name="username" autocomplete="new-username" required
                            placeholder="Ingrese su usuario o email" value="">
                        <i class="form-icon fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="form-input-wrapper">
                        <input type="password" class="form-input" name="password" id="password" autocomplete="current-password" required
                            placeholder="Ingrese su contraseña">
                        <i class="form-icon fas fa-lock"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Mostrar contraseña">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="form-checkbox" name="remember" id="remember">
                        <label for="remember">Recordarme</label>
                    </div>
                    <a href="#" class="forgot-link" onclick="showForgotPassword(); return false;">
                        ¿Olvidó su contraseña?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </span>
                    <div class="loading-spinner"></div>
                </button>
            </form>

            <!-- Formulario de Recuperación de Contraseña -->
            <form method="POST" action="forgot-password.php" class="forgot-password-form" id="forgotForm">
                <input type="hidden" name="action" value="forgot_password">

                <a href="#" class="back-link" onclick="showLogin(); return false;">
                    <i class="fas fa-arrow-left"></i> Volver al login
                </a>

                <div class="form-group">
                    <label class="form-label">Email de recuperación</label>
                    <div class="form-input-wrapper">
                        <input type="email" class="form-input" name="email" autocomplete="email" required
                            placeholder="Ingrese su email registrado">
                        <i class="form-icon fas fa-envelope"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane"></i> Enviar Enlace de Recuperación
                    </span>
                    <div class="loading-spinner"></div>
                </button>
            </form>

            <div class="footer-text">
                © <?php echo date('Y'); ?> FORZA. Todos los derechos reservados.<br>
                <strong>Versión:</strong> 1.0.0
            </div>
        </div>
    </div>

    <script>
        // ==================== MODO NOCHE ====================
        (function() {
            // Verificar preferencia guardada al cargar
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

        // Funciones para alternar entre formularios
        function showForgotPassword() {
            document.querySelector('.main-form').classList.add('hidden');
            document.querySelector('.forgot-password-form').classList.add('active');
        }

        function showLogin() {
            document.querySelector('.forgot-password-form').classList.remove('active');
            document.querySelector('.main-form').classList.remove('hidden');
        }

        // Función para mostrar/ocultar contraseña
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

        // Manejo de formularios con loading
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
        });

        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = this.querySelector('.btn');
            btn.classList.add('loading');
        });

        // Auto-focus en el primer campo
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('.main-form .form-input');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Prevenir envío múltiple de formularios
        let formSubmitted = false;
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (formSubmitted) {
                    e.preventDefault();
                    return false;
                }
                formSubmitted = true;

                setTimeout(() => {
                    formSubmitted = false;
                    document.querySelectorAll('.btn').forEach(btn => {
                        btn.classList.remove('loading');
                    });
                }, 5000);
            });
        });

        // Validación de campos en tiempo real
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.required && !this.value.trim()) {
                    this.style.borderColor = '#ff416c';
                } else if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
                    this.style.borderColor = '#ff416c';
                } else {
                    this.style.borderColor = '';
                }
            });

            input.addEventListener('input', function() {
                this.style.borderColor = '';
            });
        });

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Efecto de teclas para mejorar UX
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                const visibleForm = document.querySelector('form:not(.hidden):not(.forgot-password-form), form.forgot-password-form.active');
                if (visibleForm) {
                    const submitBtn = visibleForm.querySelector('.btn');
                    if (submitBtn && !submitBtn.classList.contains('loading')) {
                        submitBtn.click();
                    }
                }
            }
        });

        // Prevenir reenvío de formularios al actualizar la página
        window.addEventListener('load', function() {
            if (window.location.search.includes('success=') || window.location.search.includes('error=')) {
                setTimeout(function() {
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    url.searchParams.delete('error');
                    window.history.replaceState({}, document.title, url.pathname);
                }, 3000);
            }
        });

        window.addEventListener('beforeunload', function() {
            if (window.location.search.includes('success=') || window.location.search.includes('error=')) {
                const url = new URL(window.location);
                url.searchParams.delete('success');
                url.searchParams.delete('error');
                window.history.replaceState({}, document.title, url.pathname);
            }
        });

        // Detectar orientación y ajustar viewport en móviles
        function handleOrientationChange() {
            if (window.innerHeight < 500 && window.orientation !== undefined) {
                document.querySelector('meta[name=viewport]').setAttribute('content',
                    'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui');
            }
        }

        console.log('🌓 Modo Noche habilitado - Usa Ctrl+Shift+D o el botón superior derecho');
    </script>

    <?php if (isset($_SESSION['reset_link'])): ?>
        <div id="resetLinkModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.8); z-index: 10000; display: flex; align-items: center; justify-content: center;">
            <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); 
                        max-width: 90%; width: 550px; animation: slideIn 0.3s ease;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff6b9d, #c44fc1); 
                                border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; 
                                font-size: 2.5rem; color: white; margin-bottom: 1rem;">
                        🔗
                    </div>
                    <h2 style="color: #2d3748; margin-bottom: 0.5rem; font-size: 1.5rem;">Enlace de Recuperación</h2>
                    <p style="color: #718096; font-size: 0.9rem;">Modo Desarrollo - Sin envío de email</p>
                </div>

                <div style="background: #f7fafc; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; 
                            border: 2px solid #e2e8f0;">
                    <p style="color: #4a5568; font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 600;">
                        📋 Copia este enlace:
                    </p>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['reset_link']); ?>"
                        id="resetLinkInput"
                        style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e0; border-radius: 8px; 
                                  font-size: 0.8rem; font-family: monospace; color: #2d3748;"
                        readonly>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <button onclick="copyResetLink()"
                        style="padding: 0.875rem; background: linear-gradient(135deg, #ff6b9d, #c44fc1); 
                                   color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; 
                                   font-size: 0.95rem; transition: all 0.3s ease;">
                        📋 Copiar Enlace
                    </button>
                    <button onclick="openResetLink()"
                        style="padding: 0.875rem; background: linear-gradient(135deg, #6bcf7f, #4fd69c); 
                                   color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; 
                                   font-size: 0.95rem; transition: all 0.3s ease;">
                        🚀 Abrir Ahora
                    </button>
                </div>

                <button onclick="closeModal()"
                    style="width: 100%; padding: 0.75rem; background: #e2e8f0; color: #4a5568; 
                               border: none; border-radius: 12px; cursor: pointer; font-weight: 600; 
                               transition: all 0.3s ease;">
                    ✖ Cerrar
                </button>

                <div style="background: linear-gradient(135deg, rgba(255,193,7,0.1), rgba(255,152,0,0.1)); 
                            border: 2px solid rgba(255,193,7,0.3); border-radius: 12px; padding: 0.875rem; 
                            margin-top: 1rem; font-size: 0.85rem; color: #f57c00;">
                    <strong>⏰ Importante:</strong> Este enlace expira en <strong>1 hora</strong>
                </div>
            </div>
        </div>

        <style>
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            #resetLinkModal button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            #resetLinkModal button:active {
                transform: translateY(0);
            }
        </style>

        <script>
            function copyResetLink() {
                const input = document.getElementById('resetLinkInput');
                input.select();
                input.setSelectionRange(0, 99999);

                try {
                    document.execCommand('copy');
                    const btn = event.target;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '✅ ¡Copiado!';
                    btn.style.background = 'linear-gradient(135deg, #6bcf7f, #4fd69c)';

                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.style.background = 'linear-gradient(135deg, #ff6b9d, #c44fc1)';
                    }, 2000);
                } catch (err) {
                    alert('Error al copiar. Copia manualmente el enlace.');
                }
            }

            function openResetLink() {
                const link = document.getElementById('resetLinkInput').value;
                window.location.href = link;
            }

            function closeModal() {
                document.getElementById('resetLinkModal').style.display = 'none';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        </script>
        <?php unset($_SESSION['reset_link']); ?>
    <?php endif; ?>
</body>

</html>