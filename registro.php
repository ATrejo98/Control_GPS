<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Efectos de luz suave y profesional */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(118, 75, 162, 0.06) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { 
                transform: translate(0, 0);
                opacity: 0.8;
            }
            50% { 
                transform: translate(-20px, -20px);
                opacity: 1;
            }
        }

        .register-container {
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 2;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(30px);
            border-radius: 24px;
            padding: 3.5rem 3rem;
            box-shadow: 
                0 25px 80px rgba(0,0,0,0.25),
                0 0 1px rgba(255,255,255,0.5) inset,
                0 1px 2px rgba(255,255,255,0.3) inset;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        /* Borde luminoso sutil */
        .register-card::before {
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
                transparent
            );
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .logo-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 1.2rem;
            box-shadow: 
                0 15px 35px rgba(102, 126, 234, 0.4),
                0 5px 15px rgba(118, 75, 162, 0.3),
                inset 0 -2px 5px rgba(0,0,0,0.1),
                inset 0 2px 5px rgba(255,255,255,0.2);
            position: relative;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 50%);
            pointer-events: none;
        }

        .logo-text {
            color: #1e293b;
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .logo-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.6rem;
            display: block;
            font-size: 0.9rem;
            letter-spacing: 0.2px;
            transition: color 0.3s ease;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1.1rem;
            color: #94a3b8;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }

        .form-group:focus-within .input-icon {
            color: #3b82f6;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.95rem 1.1rem 0.95rem 3rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #1e293b;
            font-family: 'Inter', sans-serif;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 
                0 0 0 3px rgba(59, 130, 246, 0.1),
                0 1px 2px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }

        .form-group:focus-within .form-label {
            color: #3b82f6;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: all 0.3s ease;
            font-size: 1rem;
            z-index: 2;
        }

        .toggle-password:hover {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }

        .btn {
            width: 100%;
            padding: 1.05rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin: 0.5rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            box-shadow: 
                0 4px 12px rgba(102, 126, 234, 0.3),
                0 2px 4px rgba(118, 75, 162, 0.2);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 20px rgba(102, 126, 234, 0.4),
                0 4px 8px rgba(118, 75, 162, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #475569 0%, #64748b 100%);
            box-shadow: 
                0 4px 12px rgba(71, 85, 105, 0.3),
                0 2px 4px rgba(71, 85, 105, 0.2);
        }

        .btn-secondary:hover {
            box-shadow: 
                0 8px 20px rgba(71, 85, 105, 0.4),
                0 4px 8px rgba(71, 85, 105, 0.3);
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .link:hover {
            color: #764ba2;
        }

        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert i {
            font-size: 1.1rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        /* Indicador de fortaleza de contraseña */
        .password-strength {
            height: 3px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
            display: none;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Divisor visual */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .register-container {
                max-width: 480px;
            }
            
            .register-card {
                padding: 3rem 2.5rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }

            .register-container {
                max-width: 100%;
            }

            .register-card {
                padding: 2.5rem 2rem;
                border-radius: 20px;
            }
            
            .logo-text {
                font-size: 1.6rem;
            }

            .logo-subtitle {
                font-size: 0.85rem;
            }
            
            .logo-icon {
                width: 75px;
                height: 75px;
                font-size: 1.8rem;
            }

            .form-input, .form-select {
                padding: 0.85rem 1rem 0.85rem 2.8rem;
                font-size: 0.9rem;
            }

            .input-icon {
                left: 1rem;
                font-size: 0.95rem;
            }

            .btn {
                padding: 0.95rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.25rem;
            }

            .register-card {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }
            
            .logo-text {
                font-size: 1.4rem;
            }

            .logo-subtitle {
                font-size: 0.8rem;
            }

            .logo-icon {
                width: 65px;
                height: 65px;
                font-size: 1.6rem;
                border-radius: 18px;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                font-size: 0.85rem;
                margin-bottom: 0.5rem;
            }

            .form-input, .form-select {
                padding: 0.8rem 0.9rem 0.8rem 2.6rem;
                font-size: 0.85rem;
                border-radius: 10px;
            }

            .input-icon {
                left: 0.9rem;
                font-size: 0.9rem;
            }

            .toggle-password {
                right: 12px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.9rem;
                font-size: 0.85rem;
                border-radius: 10px;
                margin: 0.4rem 0;
            }

            .alert {
                padding: 0.85rem 1rem;
                font-size: 0.85rem;
                border-radius: 10px;
            }

            .footer-text {
                font-size: 0.85rem;
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 360px) {
            .register-card {
                padding: 1.5rem 1.25rem;
            }

            .logo-text {
                font-size: 1.3rem;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .form-input, .form-select {
                padding: 0.75rem 0.85rem 0.75rem 2.5rem;
                font-size: 0.8rem;
            }

            .input-icon {
                left: 0.85rem;
                font-size: 0.85rem;
            }
        }

        /* Landscape mode para móviles */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }

            .register-card {
                padding: 1.5rem;
                margin: 0.5rem 0;
            }

            .logo-section {
                margin-bottom: 1.5rem;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                margin-bottom: 0.75rem;
            }

            .logo-text {
                font-size: 1.3rem;
            }

            .logo-subtitle {
                font-size: 0.8rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-label {
                margin-bottom: 0.4rem;
            }

            .footer-text {
                margin-top: 1.25rem;
            }   
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="logo-text">Registro de Usuario</h1>
                <p class="logo-subtitle">Complete el formulario para crear su cuenta</p>
            </div>

            <div class="alert alert-error" style="display: none;" id="errorAlert">
                <i class="fas fa-exclamation-triangle"></i> 
                <span id="errorText"></span>
            </div>

            <div class="alert alert-success" style="display: none;" id="successAlert">
                <i class="fas fa-check-circle"></i> 
                <span id="successText"></span>
            </div>

            <form method="POST" action="procesar_registro.php">
                <div class="form-group">
                    <label class="form-label">Nombre completo</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="nombre_completo" class="form-input" required placeholder="Ej: Juan Pérez González">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Usuario</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="usuario" class="form-input" required placeholder="Ej: jperez">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input" required placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Rol del Usuario</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select name="rol" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            <option value="usuario">Usuario</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-input" id="password" required placeholder="Mínimo 8 caracteres">
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmar Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="confirmar_password" class="form-input" id="confirmPassword" required placeholder="Repita su contraseña">
                        <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
                
                <button type="button" class="btn btn-secondary" onclick="window.location.href='panel.php'">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </button>
            </form>

            <div class="footer-text">
                ¿Ya tienes cuenta? <a href="login.php" class="link">Inicia sesión</a>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirmPassword');

        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });

        // Efectos adicionales de interacción
        const inputs = document.querySelectorAll('.form-input, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('focused');
            });
        });

        // Validación en tiempo real
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        confirmPasswordInput.addEventListener('input', () => {
            if (confirmPasswordInput.value !== passwordInput.value) {
                confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        });
    </script>
</body>
</html>