<?php
session_start();

// Verificar si el usuario está logueado
$user_logged = false;
$user_name = 'Usuario';
$user_role = 'Usuario';
$user_id = 0;

// Obtener información de sesión
if (isset($_SESSION['usuario_id'])) {
    $user_id = $_SESSION['usuario_id'];
    $user_logged = true;
}

if (isset($_SESSION['usuario'])) {
    $user_name = $_SESSION['usuario'];
}

if (isset($_SESSION['rol'])) {
    $user_role = $_SESSION['rol'];
    $is_admin = (strtolower($_SESSION['rol']) === 'administrador');
} else {
    $is_admin = false;
}

// Redirigir si no está logueado
if (!$user_logged) {
    header("Location: login.php");
    exit();
}

// Verificar si es administrador
if (!$is_admin) {
    header("Location: index.php");
    exit();
}

// Incluir conexión a la base de datos
require_once 'conexion/db.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Mahanaim</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
     <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, rgba(25, 32, 102, 0.64) 0%, rgba(28, 25, 94, 0.59) 50%, rgba(42, 31, 105, 1) 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 120, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 120, 120, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 255, 120, 0.1) 0%, transparent 50%);
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                opacity: 0.3;
                transform: translateY(0px);
            }

            50% {
                opacity: 0.8;
                transform: translateY(-20px);
            }
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .header-text {
            text-align: left;
        }

        .admin-title {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .admin-subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-info {
            text-align: right;
            margin-right: 1rem;
        }

        .user-name {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .user-role {
            color: #718096;
            font-size: 0.9rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .users-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .users-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .users-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 0.75rem 1rem;
            border-radius: 15px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.2);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border-radius: 15px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            overflow: hidden;
            border-radius: 15px;
        }

        .users-table th,
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .users-table th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 700;
            color: #2d3748;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .users-table tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            margin-right: 1rem;
        }

        .user-info-cell {
            display: flex;
            align-items: center;
        }

        .user-details h4 {
            color: #2d3748;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .user-details p {
            color: #718096;
            font-size: 0.8rem;
        }

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-admin {
            background: rgba(255, 65, 108, 0.1);
            color: #ff416c;
        }

        .role-user {
            background: rgba(17, 153, 142, 0.1);
            color: #00b894;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(17, 153, 142, 0.1);
            color: #00b894;
            border-left: 4px solid #00b894;
        }

        .alert-error {
            background: rgba(255, 65, 108, 0.1);
            color: #d63031;
            border-left: 4px solid #d63031;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #718096;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #2d3748;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 15px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.2);
        }

        /* Modal de Registro */
        .register-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 2000;
            overflow-y: auto;
            padding: 1rem;
        }

        .register-modal-content {
            position: relative;
            max-width: 520px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(30px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow:
                0 25px 80px rgba(0, 0, 0, 0.3),
                0 0 1px rgba(255, 255, 255, 0.5) inset;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .register-modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(102, 126, 234, 0.6) 25%,
                    rgba(118, 75, 162, 0.8) 50%,
                    rgba(102, 126, 234, 0.6) 75%,
                    transparent);
        }

        .register-modal-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-modal-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow:
                0 15px 35px rgba(102, 126, 234, 0.4),
                0 5px 15px rgba(118, 75, 162, 0.3);
        }

        .register-modal-title {
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-modal-subtitle {
            color: #64748b;
            font-size: 0.9rem;
        }

        .register-close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: rgba(71, 85, 105, 0.1);
            border: none;
            color: #64748b;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .register-close-btn:hover {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            transform: rotate(90deg);
        }

        .register-form-group {
            margin-bottom: 1.3rem;
            position: relative;
        }

        .register-form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.85rem;
            letter-spacing: 0.2px;
        }

        .register-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .register-input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }

        .register-form-group:focus-within .register-input-icon {
            color: #667eea;
        }

        .register-form-input,
        .register-form-select {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.9rem;
            background: #ffffff;
            transition: all 0.3s ease;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
        }

        .register-form-input::placeholder {
            color: #94a3b8;
        }

        .register-form-input:focus,
        .register-form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .register-form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .register-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            z-index: 2;
        }

        .register-toggle-password:hover {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }

        .register-btn {
            width: 100%;
            padding: 0.95rem;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            margin: 0.4rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .register-btn-secondary {
            background: linear-gradient(135deg, #475569 0%, #64748b 100%);
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.3);
        }

        .register-btn-secondary:hover {
            box-shadow: 0 8px 20px rgba(71, 85, 105, 0.4);
        }

        /* ==================== MODO OSCURO ==================== */
        body.dark-mode {
            background: linear-gradient(-45deg, #1a1a2e, #16213e, #0f3460, #1a1a2e) !important;
            background-size: 400% 400% !important;
            animation: gradientBG 20s ease infinite !important;
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

        body.dark-mode .admin-header,
        body.dark-mode .stat-card,
        body.dark-mode .users-card {
            background: rgba(30, 30, 45, 0.95) !important;
            border: 2px solid rgba(102, 126, 234, 0.3) !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
        }

        body.dark-mode .admin-title,
        body.dark-mode .users-title,
        body.dark-mode .stat-number,
        body.dark-mode .user-name,
        body.dark-mode .user-details h4 {
            color: #e0e0e0 !important;
        }

        body.dark-mode .admin-subtitle,
        body.dark-mode .user-role,
        body.dark-mode .stat-label,
        body.dark-mode .user-details p {
            color: #b0b0b0 !important;
        }

        body.dark-mode .users-table th {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%) !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .users-table td {
            color: #e0e0e0 !important;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2) !important;
        }

        body.dark-mode .users-table tr:hover {
            background: rgba(102, 126, 234, 0.15) !important;
        }

        body.dark-mode .search-input,
        body.dark-mode .filter-select {
            background: rgba(40, 40, 60, 0.95) !important;
            border: 2px solid rgba(102, 126, 234, 0.3) !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .search-input::placeholder {
            color: #888 !important;
        }

        body.dark-mode .search-input:focus,
        body.dark-mode .filter-select:focus {
            background: rgba(45, 45, 65, 1) !important;
            border-color: #667eea !important;
        }

        body.dark-mode .modal {
            background: rgba(0, 0, 0, 0.8) !important;
        }

        body.dark-mode .modal-content {
            background: rgba(30, 30, 45, 0.98) !important;
            border: 2px solid rgba(102, 126, 234, 0.4) !important;
        }

        body.dark-mode .modal-title,
        body.dark-mode .form-label {
            color: #e0e0e0 !important;
        }

        body.dark-mode .form-input,
        body.dark-mode .form-select {
            background: rgba(40, 40, 60, 0.95) !important;
            border: 2px solid rgba(102, 126, 234, 0.3) !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .form-input::placeholder {
            color: #888 !important;
        }

        body.dark-mode .form-input:focus,
        body.dark-mode .form-select:focus {
            background: rgba(45, 45, 65, 1) !important;
            border-color: #667eea !important;
        }

        body.dark-mode .close-btn {
            color: #b0b0b0 !important;
        }

        body.dark-mode .close-btn:hover {
            color: #e0e0e0 !important;
        }

        /* Modo oscuro para el modal de registro */
        body.dark-mode .register-modal {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        body.dark-mode .register-modal-content {
            background: rgba(30, 30, 45, 0.98) !important;
            border: 2px solid rgba(102, 126, 234, 0.4) !important;
        }

        body.dark-mode .register-modal-title {
            color: #e0e0e0 !important;
        }

        body.dark-mode .register-modal-subtitle,
        body.dark-mode .register-form-label {
            color: #b0b0b0 !important;
        }

        body.dark-mode .register-form-input,
        body.dark-mode .register-form-select {
            background: rgba(40, 40, 60, 0.95) !important;
            border: 1.5px solid rgba(102, 126, 234, 0.3) !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .register-form-input::placeholder {
            color: #888 !important;
        }

        body.dark-mode .register-close-btn {
            background: rgba(102, 126, 234, 0.2) !important;
            color: #b0b0b0 !important;
        }

        body.dark-mode .register-close-btn:hover {
            background: rgba(220, 38, 38, 0.2) !important;
            color: #ff6b6b !important;
        }

        /* Botón flotante de cambio de tema */
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            cursor: pointer;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.15) rotate(15deg);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }

        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                text-align: center;
            }

            .header-left {
                justify-content: center;
                margin-bottom: 1rem;
            }

            .header-text {
                text-align: center;
            }

            .users-table {
                font-size: 0.8rem;
            }

            .users-table th,
            .users-table td {
                padding: 0.5rem;
            }

            .actions {
                flex-direction: column;
            }

            .search-input {
                min-width: auto;
            }

            .users-header {
                flex-direction: column;
                align-items: stretch;
            }

            .theme-toggle-btn {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                bottom: 20px;
                right: 20px;
            }

            .register-modal-content {
                padding: 2rem 1.5rem;
                margin: 1rem auto;
            }

            .register-modal-icon {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
            }

            .register-modal-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <div class="header-left">
                <div class="logo-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="header-text">
                    <h1 class="admin-title">Panel de Administración</h1>
                    <p class="admin-subtitle">Gestiona los usuarios de FajardoShop</p>
                </div>
            </div>
            <div class="header-actions">
                <div class="user-info">
                    <div class="user-name">Bienvenido, <?php echo htmlspecialchars($user_name); ?></div>
                    <div class="user-role">(<?php echo htmlspecialchars($user_role); ?>)</div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number" id="totalUsers">0</div>
                <div class="stat-label">Total de Usuarios</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-number" id="totalAdmins">0</div>
                <div class="stat-label">Administradores</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-number" id="totalRegularUsers">0</div>
                <div class="stat-label">Usuarios Regulares</div>
            </div>
        </div>

        <!-- Users Management -->
        <div class="users-card">
            <div class="users-header">
                <h2 class="users-title">
                    <i class="fas fa-users"></i>
                    Gestión de Usuarios
                </h2>
                <a href="registro.php" class="btn">
                    <i class="fas fa-plus"></i>
                    Nuevo Usuario
                </a>
            </div>

            <div id="alertContainer"></div>

            <div class="search-bar">
                <input type="text" class="search-input" id="searchInput" placeholder="Buscar por nombre, usuario o email...">
                <select class="filter-select" id="roleFilter">
                    <option value="">Todos los roles</option>
                    <option value="Administrador">Administradores</option>
                    <option value="Usuario">Usuarios</option>
                </select>
                <button class="btn btn-small" onclick="loadUsers()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                                <p style="margin-top: 1rem; color: #718096;">Cargando usuarios...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para editar usuario -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Editar Usuario</h2>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm">
                <input type="hidden" id="editUserId">
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" id="editName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <input type="text" id="editUsername" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" id="editEmail" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <select id="editRole" class="form-select" required>
                        <option value="Usuario">Usuario</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn" style="flex: 1;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <button type="button" class="btn btn-delete" onclick="closeEditModal()" style="flex: 1;">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let users = [];
        let filteredUsers = [];

        function getInitials(name) {
            if (!name) return 'U';
            return name.split(' ').map(word => word[0] || '').join('').toUpperCase().substring(0, 2);
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            } catch (e) {
                return 'N/A';
            }
        }

        async function loadUsers() {
            try {
                const response = await fetch('get_users.php');

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.success && data.users) {
                    users = data.users;
                    filteredUsers = [...users];
                    renderUsersTable();
                    updateStats();
                } else {
                    throw new Error(data.error || 'Error desconocido al cargar usuarios');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Error al cargar usuarios: ' + error.message, 'error');
                document.getElementById('usersTableBody').innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: #d63031;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            Error al cargar usuarios: ${error.message}
                        </td>
                    </tr>
                `;
            }
        }

        function renderUsersTable() {
            const tbody = document.getElementById('usersTableBody');

            if (filteredUsers.length === 0) {
                tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 2rem; color: #718096;">
                    <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    No se encontraron usuarios
                </td>
            </tr>
        `;
                return;
            }

            tbody.innerHTML = filteredUsers.map(user => {
                const userName = user.nombre_completo || user.usuario || 'Sin nombre';
                const userLogin = user.usuario || 'sin_usuario';
                const userEmail = user.email || 'Sin email';
                const userRole = user.rol || 'Usuario';
                const isAdmin = (userRole.toLowerCase() === 'administrador');

                return `
            <tr>
                <td>
                    <div class="user-info-cell">
                        <div class="user-avatar">${getInitials(userName)}</div>
                        <div class="user-details">
                            <h4>${escapeHtml(userName)}</h4>
                            <p>@${escapeHtml(userLogin)}</p>
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(userEmail)}</td>
                <td>
                    <span class="role-badge ${isAdmin ? 'role-admin' : 'role-user'}">
                        ${escapeHtml(userRole)}
                    </span>
                </td>
                <td>${formatDate(user.created_at)}</td>
                <td>
                    <div class="actions">
                        <button class="btn btn-small btn-edit" onclick="editUser(${user.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-small btn-delete" onclick="deleteUser(${user.id})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
            }).join('');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }

        function updateStats() {
            const totalUsers = users.length;
            const totalAdmins = users.filter(u => u.rol === 'Administrador' || u.rol === 'administrador').length;
            const totalRegularUsers = users.filter(u => u.rol === 'Usuario' || u.rol === 'usuario').length;

            document.getElementById('totalUsers').textContent = totalUsers;
            document.getElementById('totalAdmins').textContent = totalAdmins;
            document.getElementById('totalRegularUsers').textContent = totalRegularUsers;
        }

        function filterUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roleFilter = document.getElementById('roleFilter').value;

            filteredUsers = users.filter(user => {
                const matchesSearch = (user.nombre_completo || '').toLowerCase().includes(searchTerm) ||
                    (user.usuario || '').toLowerCase().includes(searchTerm) ||
                    (user.email || '').toLowerCase().includes(searchTerm);

                const matchesRole = roleFilter === '' || user.rol.toLowerCase() === roleFilter.toLowerCase();

                return matchesSearch && matchesRole;
            });

            renderUsersTable();
        }

        function editUser(id) {
            const user = users.find(u => u.id === id);
            if (user) {
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editName').value = user.nombre_completo || user.usuario || '';
                document.getElementById('editUsername').value = user.usuario || '';
                document.getElementById('editEmail').value = user.email || '';
                document.getElementById('editRole').value = user.rol || 'Usuario';

                document.getElementById('editModal').style.display = 'block';
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        async function deleteUser(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                try {
                    const formData = new FormData();
                    formData.append('user_id', id);

                    const response = await fetch('delete_user.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();

                    if (data.success) {
                        showAlert('Usuario eliminado correctamente', 'success');
                        loadUsers();
                    } else {
                        showAlert('Error al eliminar usuario: ' + (data.error || 'Error desconocido'), 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Error de conexión: ' + error.message, 'error');
                }
            }
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
            `;

            alertContainer.appendChild(alert);

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Event Listeners
        document.getElementById('searchInput').addEventListener('input', filterUsers);
        document.getElementById('roleFilter').addEventListener('change', filterUsers);

        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('id', document.getElementById('editUserId').value);
            formData.append('nombre_completo', document.getElementById('editName').value);
            formData.append('usuario', document.getElementById('editUsername').value);
            formData.append('email', document.getElementById('editEmail').value);
            formData.append('rol', document.getElementById('editRole').value);

            try {
                const response = await fetch('update_user.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                if (data.success) {
                    showAlert('Usuario actualizado correctamente', 'success');
                    closeEditModal();
                    loadUsers();
                } else {
                    showAlert('Error al actualizar usuario: ' + (data.error || 'Error desconocido'), 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Error de conexión: ' + error.message, 'error');
            }
        });

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('editModal');
            if (e.target === modal) {
                closeEditModal();
            }
        });

        // Inicializar cuando la página se carga
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
        });
    </script>
    <!-- Botón de cambio de tema -->
    <button class="theme-toggle-btn" onclick="toggleTheme()" id="themeToggleBtn">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        // ==================== MODO NOCHE ====================
        (function() {
            const currentTheme = localStorage.getItem('fajardoshop-theme');
            const themeBtn = document.getElementById('themeToggleBtn');

            if (currentTheme === 'dark') {
                document.body.classList.add('dark-mode');
                if (themeBtn) {
                    themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
                }
            }
        })();

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const themeBtn = document.getElementById('themeToggleBtn');

            if (document.body.classList.contains('dark-mode')) {
                themeBtn.innerHTML = '<i class="fas fa-sun"></i>';
                localStorage.setItem('fajardoshop-theme', 'dark');
                showAlert('🌙 Modo Noche activado', 'success');
            } else {
                themeBtn.innerHTML = '<i class="fas fa-moon"></i>';
                localStorage.setItem('fajardoshop-theme', 'light');
                showAlert('☀️ Modo Día activado', 'success');
            }
        }

        // Atajo de teclado Ctrl+Shift+D
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggleTheme();
            }
        });
    </script>
</body>

</html>