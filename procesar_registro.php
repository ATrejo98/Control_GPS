<?php
include 'conexion/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $usuario        = trim($_POST['usuario'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $rol            = trim($_POST['rol'] ?? '');
    $password       = trim($_POST['password'] ?? '');
    $confirmar_password = trim($_POST['confirmar_password'] ?? '');

    // Validaciones
    if (empty($nombre_completo) || empty($usuario) || empty($email) || empty($rol) || empty($password)) {
        mostrarMensaje("❌ Todos los campos son obligatorios.", "error");
        exit;
    }

    if ($password !== $confirmar_password) {
        mostrarMensaje("❌ Las contraseñas no coinciden.", "error");
        exit;
    }

    // Validar que el rol sea válido
    if (!in_array($rol, ['usuario', 'administrador'])) {
        mostrarMensaje("❌ Rol no válido.", "error");
        exit;
    }

    // Validar longitud de contraseña
    if (strlen($password) < 6) {
        mostrarMensaje("❌ La contraseña debe tener al menos 6 caracteres.", "error");
        exit;
    }

    try {
        // Verificar duplicados
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario OR email = :email");
        $stmt->execute(['usuario' => $usuario, 'email' => $email]);
        if ($stmt->fetch()) {
            mostrarMensaje("⚠️ El usuario o email ya existen.", "error");
            exit;
        }

        // Insertar usuario con rol
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO usuarios (nombre_completo, usuario, email, rol, password)
                   VALUES (:nombre_completo, :usuario, :email, :rol, :password)";
        $stmt = $conn->prepare($insert);
        $stmt->execute([
            'nombre_completo' => $nombre_completo,
            'usuario'         => $usuario,
            'email'           => $email,
            'rol'             => $rol,
            'password'        => $password_hash
        ]);

        mostrarMensaje("✅ Registro exitoso. Usuario creado como " . ucfirst($rol) . ".", "success");

    } catch (PDOException $e) {
        mostrarMensaje("❌ Error en la operación: " . $e->getMessage(), "error");
    }

} else {
    mostrarMensaje("❌ Acceso no permitido.", "error");
}

function mostrarMensaje($mensaje, $tipo) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resultado del Registro - Mahanaim</title>
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
                background: linear-gradient(135deg, #1b7889 0%, #1d6d64 50%, #7a298e 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                position: relative;
                overflow-x: hidden;
                animation: gradientShift 15s ease-in-out infinite;
            }

            @keyframes gradientShift {
                0%, 100% { 
                    background: linear-gradient(135deg, #1b7889 0%, #1d6d64 50%, #7a298e 100%);
                }
                33% { 
                    background: linear-gradient(135deg, #7a298e 0%, #1b7889 50%, #1d6d64 100%);
                }
                66% { 
                    background: linear-gradient(135deg, #1d6d64 0%, #7a298e 50%, #1b7889 100%);
                }
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
                0%, 100% { opacity: 0.3; transform: translateY(0px); }
                50% { opacity: 0.8; transform: translateY(-20px); }
            }

            .message-container {
                width: 100%;
                max-width: 500px;
                position: relative;
                z-index: 2;
            }

            .message-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 25px;
                padding: 3rem 2.5rem;
                box-shadow: 
                    0 20px 60px rgba(0,0,0,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.2);
                border: 1px solid rgba(255, 255, 255, 0.2);
                animation: cardFloat 6s ease-in-out infinite;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .message-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                animation: shine 3s ease-in-out infinite;
            }

            @keyframes cardFloat {
                0%, 100% { transform: translateY(0px) rotateX(0deg); }
                50% { transform: translateY(-10px) rotateX(2deg); }
            }

            @keyframes shine {
                0% { left: -100%; }
                100% { left: 100%; }
            }

            .message-icon {
                width: 100px;
                height: 100px;
                margin: 0 auto 2rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 3rem;
                color: white;
                animation: iconPulse 2s ease-in-out infinite;
            }

            .success-icon {
                background: linear-gradient(135deg, #00b894 0%, #17a2b8 100%);
                box-shadow: 0 15px 40px rgba(0, 184, 148, 0.3);
            }

            .error-icon {
                background: linear-gradient(135deg, #d63031 0%, #e17055 100%);
                box-shadow: 0 15px 40px rgba(214, 48, 49, 0.3);
            }

            @keyframes iconPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }

            .message-title {
                color: #2d3748;
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 1rem;
                text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .message-text {
                color: #4a5568;
                font-size: 1.1rem;
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .btn {
                display: inline-block;
                padding: 1rem 2rem;
                border: none;
                border-radius: 15px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                margin: 0.5rem;
                text-decoration: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-primary {
                background: linear-gradient(135deg, #101b4d 0%, #0c0c72 100%);
                color: white;
            }

            .btn-secondary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            }

            .btn:hover::before {
                left: 100%;
            }

            /* Auto redirect animation */
            .countdown {
                margin-top: 1rem;
                color: #718096;
                font-size: 0.9rem;
            }

            .progress-bar {
                width: 100%;
                height: 4px;
                background: rgba(0,0,0,0.1);
                border-radius: 2px;
                overflow: hidden;
                margin-top: 1rem;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #667eea, #764ba2);
                border-radius: 2px;
                animation: progress 5s linear forwards;
            }

            @keyframes progress {
                from { width: 0%; }
                to { width: 100%; }
            }

            @media (max-width: 768px) {
                .message-card {
                    padding: 2rem 1.5rem;
                }
                
                .message-title {
                    font-size: 1.5rem;
                }
                
                .message-icon {
                    width: 80px;
                    height: 80px;
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="message-container">
            <div class="message-card">
                <div class="message-icon <?php echo $tipo === 'success' ? 'success-icon' : 'error-icon'; ?>">
                    <i class="fas <?php echo $tipo === 'success' ? 'fa-check' : 'fa-times'; ?>"></i>
                </div>
                
                <h1 class="message-title">
                    <?php echo $tipo === 'success' ? 'Éxito' : 'Error'; ?>
                </h1>
                
                <p class="message-text">
                    <?php echo htmlspecialchars($mensaje); ?>
                </p>
                
                <div class="buttons">
                    <?php if ($tipo === 'success'): ?>
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    <?php endif; ?>
                    
                    <a href="registro.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Registro
                    </a>
                    
                    <a href="index2.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Ir al Inicio
                    </a>
                </div>
                
                <?php if ($tipo === 'success'): ?>
                    <div class="countdown">
                        Redirigiendo al dashboard en <span id="countdown">5</span> segundos...
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            <?php if ($tipo === 'success'): ?>
            // Auto-redirect after success
            let countdown = 5;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = 'Panel.php';
                }
            }, 1000);
            <?php endif; ?>
        </script>
    </body>
    </html>
    <?php
}
?>