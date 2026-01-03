<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORZA - Control de GPS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border: #334155;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .top-bar {
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            height: 70px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 100;
            box-shadow: var(--shadow);
            transition: left 0.3s ease;
        }

        .top-bar-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 101;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .logo-text p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .nav-menu {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        .nav-item {
            width: 100%;
            padding: 1rem 1.25rem;
            margin-bottom: 0.5rem;
            background: transparent;
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }

        .nav-item i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* ✅ COLORES INDIVIDUALES CON MEJOR CONTRASTE */
        .nav-item:nth-child(1) i {
            color: #3b82f6;
        }

        .nav-item:nth-child(1):hover {
            background: #dbeafe;
        }

        .nav-item:nth-child(2) i {
            color: #f59e0b;
        }

        .nav-item:nth-child(2):hover {
            background: #fef3c7;
        }

        .nav-item:nth-child(3) i {
            color: #8b5cf6;
        }

        .nav-item:nth-child(3):hover {
            background: #ede9fe;
        }

        .nav-item:nth-child(4) i {
            color: #ec4899;
        }

        .nav-item:nth-child(4):hover {
            background: #fce7f3;
        }

        .nav-item:nth-child(5) i {
            color: #10b981;
        }

        .nav-item:nth-child(5):hover {
            background: #dcfce7;
        }

        .nav-item:nth-child(6) i {
            color: #06b6d4;
        }

        .nav-item:nth-child(6):hover {
            background: #cffafe;
        }

        .nav-item:nth-child(7) i {
            color: #f97316;
        }

        .nav-item:nth-child(7):hover {
            background: #ffedd5;
        }

        .nav-item:nth-child(8) i {
            color: #6366f1;
        }

        .nav-item:nth-child(8):hover {
            background: #e0e7ff;
        }

        /* ESTADO ACTIVO - Mantiene color en el ícono */
        .nav-item.active {
            background: var(--primary);
            color: white;
        }

        .nav-item.active i {
            color: white;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border);
        }

        .main-content {
            margin-left: 280px;
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }

        .module-content {
            animation: fadeIn 0.3s ease;
        }

        .module-content.hidden {
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .content-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .content-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-info p {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #f97316, #ea580c);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .table-container {
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
            border-radius: 12px;
            border: 1px solid var(--border);
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            .table-container {
                max-height: 400px;
            }
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background: var(--bg-secondary);
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--bg-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--bg-secondary);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        body.dark-mode .badge-success {
            background: #166534;
            color: #dcfce7;
        }

        body.dark-mode .badge-warning {
            background: #92400e;
            color: #fef3c7;
        }

        body.dark-mode .badge-danger {
            background: #991b1b;
            color: #fee2e2;
        }

        body.dark-mode .badge-info {
            background: #1e40af;
            color: #dbeafe;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .close-modal:hover {
            background: var(--bg-secondary);
            color: var(--danger);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .hidden {
            display: none;
        }

        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .form-card form {
            padding: 2rem;
        }

        .alert {
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        body.dark-mode .alert-info {
            background: #1e3a8a;
            color: #dbeafe;
        }

        body.dark-mode .alert-success {
            background: #14532d;
            color: #dcfce7;
        }

        body.dark-mode .alert-danger {
            background: #7f1d1d;
            color: #fee2e2;
        }

        .ubicacion-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .ubicacion-instalaciones {
            background: #dcfce7;
            color: #166534;
        }

        .ubicacion-campo {
            background: #fef3c7;
            color: #92400e;
        }

        body.dark-mode .ubicacion-instalaciones {
            background: #166534;
            color: #dcfce7;
        }

        body.dark-mode .ubicacion-campo {
            background: #92400e;
            color: #fef3c7;
        }

        .user-menu-container {
            position: relative;
        }

        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-menu-btn:hover {
            background: var(--bg-primary);
            box-shadow: var(--shadow);
        }

        .user-menu-avatar {
            position: relative;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: var(--success);
            border: 2px solid var(--bg-card);
            border-radius: 50%;
        }

        .user-menu-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .user-menu-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-menu-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 280px;
            z-index: 1000;
        }

        .user-dropdown.hidden {
            display: none;
        }

        .user-dropdown-header {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .user-dropdown-avatar {
            position: relative;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .status-indicator-large {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 16px;
            height: 16px;
            background: var(--success);
            border: 3px solid var(--bg-card);
            border-radius: 50%;
        }

        .user-dropdown-name {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .user-dropdown-email {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border);
        }

        .user-dropdown-item {
            width: 100%;
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }

        .user-dropdown-item:hover {
            background: var(--bg-secondary);
        }

        .user-dropdown-item i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            color: var(--text-secondary);
        }

        .theme-toggle-top {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .theme-toggle-top:hover {
            background: var(--bg-primary);
            box-shadow: var(--shadow);
        }

        .notification-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: var(--bg-card);
            border-left: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            z-index: 1001;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .notification-panel.active {
            right: 0;
        }

        .notification-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-list {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .notification-item {
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .notification-item:hover {
            transform: translateX(-5px);
            box-shadow: var(--shadow);
        }

        .notification-item-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.5rem;
        }

        .notification-item-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .notification-item-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .notification-item-body {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .notif-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: white;
            border-radius: 9999px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        .notif-badge.hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .notification-panel {
                width: 100%;
                right: -100%;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .top-bar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .user-menu-info span {
                display: none;
            }

            .top-bar-actions .btn span {
                display: none;
            }
        }

        .mobile-menu-btn {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            z-index: 100;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .sidebar-toggle-btn {
            position: fixed;
            left: 10px;
            top: 20px;
            width: 45px;
            height: 45px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.3rem;
            z-index: 102;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .nav-item span {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 1rem;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 1rem;
            text-align: center;
        }

        .sidebar.collapsed .logo-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            margin: 0 auto;
        }

        .top-bar.sidebar-collapsed {
            left: 80px;
            /* ✅ CAMBIA ESTO */
        }

        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }

        @media (max-width: 768px) {
            .sidebar.collapsed {
                width: 250px;
            }

            .sidebar.collapsed .logo-text,
            .sidebar.collapsed .nav-item span {
                display: inline;
            }

            .top-bar.sidebar-collapsed {
                left: 0;
            }

            .main-content.sidebar-collapsed {
                margin-left: 0;
            }


        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="user-info">
            <div class="logo-container">
                <div class="logo-icon" style="width: 45px; height: 45px;">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="logo-text">
                    <h1 style="font-size: 1.25rem;">FORZA</h1>
                    <p style="font-size: 0.7rem;">Control de GPS</p>
                </div>
                <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="Contraer/Expandir">
                    <i class="fas fa-chevron-left" id="toggle-icon"></i>
                </button>
            </div>
        </div>

        <div class="top-bar-actions">
            <button class="theme-toggle-top" onclick="toggleTheme()">
                <i class="fas fa-moon" id="theme-icon"></i>
                <span id="theme-text">Tema Oscuro</span>
            </button>

            <button class="btn btn-primary" onclick="toggleNotificaciones()" style="position: relative; padding: 0.75rem 1rem;">
                <i class="fas fa-bell"></i>
                <span id="notif-badge" class="notif-badge hidden">0</span>
            </button>

            <div class="user-menu-container">
                <button class="user-menu-btn" onclick="toggleUserMenu()">
                    <div class="user-menu-avatar">
                        <i class="fas fa-user"></i>
                        <span class="status-indicator"></span>
                    </div>
                    <div class="user-menu-info">
                        <span class="user-menu-name" id="user-menu-nombre">Admin</span>
                        <span class="user-menu-role" id="user-menu-rol">Administrador</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div class="user-dropdown hidden" id="user-dropdown">
                    <div class="user-dropdown-header">
                        <div class="user-dropdown-avatar">
                            <i class="fas fa-user"></i>
                            <span class="status-indicator-large"></span>
                        </div>
                        <div>
                            <p class="user-dropdown-name" id="dropdown-nombre">Admin</p>
                            <p class="user-dropdown-email" id="dropdown-email">admin@forza.hn</p>
                            <span class="badge badge-success" id="dropdown-rol">Administrador</span>
                        </div>
                    </div>

                    <div class="user-dropdown-divider"></div>

                    <button class="user-dropdown-item" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="theme-icon-menu"></i>
                        <span id="theme-text-menu">Modo Oscuro</span>
                    </button>

                    <button class="user-dropdown-item" onclick="irAPanelAdmin()">
                        <i class="fas fa-user-shield"></i>
                        <span>Panel Admin</span>
                    </button>

                    <button class="user-dropdown-item" onclick="cerrarSesion()" style="color: var(--danger);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="notification-panel" id="notification-panel">
        <div class="notification-header">
            <h3><i class="fas fa-bell"></i> Notificaciones</h3>
            <button class="btn btn-secondary" onclick="toggleNotificaciones()" style="padding: 0.5rem 1rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="notification-list" id="notification-list">
            <div class="notification-item">
                <div class="notification-item-header">
                    <span class="notification-item-title">🔔 Sistema iniciado</span>
                    <span class="notification-item-time">Hace 1 min</span>
                </div>
                <div class="notification-item-body">
                    Bienvenido al sistema FORZA de Control de GPS
                </div>
            </div>
        </div>
    </div>

    <aside class="sidebar responsive" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="logo-text">
                    <h1>FORZA</h1>
                    <p>Control de GPS • HN</p>
                </div>
            </div>
        </div>

        <nav class="nav-menu" id="nav-menu">
            <button class="nav-item active" onclick="showModule('dashboard')">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </button>
            <button class="nav-item" onclick="showModule('gps')">
                <i class="fas fa-satellite-dish"></i>
                <span>Gestión de GPS</span>
            </button>
            <button class="nav-item" onclick="showModule('custodios')">
                <i class="fas fa-users"></i>
                <span>Custodios</span>
            </button>
            <button class="nav-item" onclick="showModule('asignar')">
                <i class="fas fa-hand-holding"></i>
                <span>Asignar GPS</span>
            </button>
            <button class="nav-item" onclick="showModule('retornar')">
                <i class="fas fa-undo"></i>
                <span>Retornar GPS</span>
            </button>
            <button class="nav-item" onclick="showModule('consulta')">
                <i class="fas fa-search"></i>
                <span>Consultar GPS</span>
            </button>
            <button class="nav-item" onclick="showModule('historial')">
                <i class="fas fa-clock"></i>
                <span>Historial</span>
            </button>
            <!-- ✅ NUEVO BOTÓN DE EXPANDIR/CONTRAER -->
            <button class="nav-item" onclick="toggleSidebarCollapse()" title="Contraer/Expandir" style="margin-top: auto;">
                <i class="fas fa-chevron-left" id="toggle-icon"></i>
                <span>Contraer</span>
            </button>
        </nav>
        </nav>
    </aside>

    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content">
        <!-- DASHBOARD -->
        <div id="module-dashboard" class="module-content">
            <div class="content-header">
                <h2>Dashboard General</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>GPS Asignados</h3>
                        <p id="stat-asignados">0</p>
                    </div>
                    <div class="stat-icon orange">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>GPS Disponibles</h3>
                        <p id="stat-disponibles">0</p>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total de GPS</h3>
                        <p id="stat-total">0</p>
                    </div>
                    <div class="stat-icon purple">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="mb-4" style="font-size: 1.25rem; font-weight: 700;">GPS ACTUALMENTE ASIGNADOS</h3>
                <div class="table-container">
                    <table id="tabla-gps-asignados">
                        <thead>
                            <tr>
                                <th>IMEI/Serie</th>
                                <th>Modelo</th>
                                <th>Custodio</th>
                                <th>Fecha Asignación</th>
                                <th>Días Asignado</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-check-circle"></i>
                                        <h3>No hay GPS asignados</h3>
                                        <p>Todos los GPS están disponibles</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- GESTIÓN DE GPS -->
        <div id="module-gps" class="module-content hidden">
            <div class="content-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h2>Gestión de GPS</h2>
                        <p>Administra el inventario de GPS</p>
                    </div>
                    <button class="btn btn-primary" onclick="showModalGPS()">
                        <i class="fas fa-plus"></i> Agregar GPS
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="buscar-gps-tabla" placeholder="Buscar GPS por IMEI, marca, modelo..." onkeyup="filtrarTablaGPS()">
                </div>
                <div class="table-container">
                    <table id="tabla-gps">
                        <thead>
                            <tr>
                                <th>IMEI/Serie</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Estado</th>
                                <th>Ubicación</th>
                                <th>Custodio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- GESTIÓN DE CUSTODIOS -->
        <div id="module-custodios" class="module-content hidden">
            <div class="content-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h2>Gestión de Custodios</h2>
                        <p>Administra el personal autorizado</p>
                    </div>
                    <button class="btn btn-primary" onclick="showModalCustodio()">
                        <i class="fas fa-plus"></i> Agregar Custodio
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="buscar-custodio-tabla" placeholder="Buscar custodio por nombre, teléfono..." onkeyup="filtrarTablaCustodios()">
                </div>
                <div class="table-container">
                    <table id="tabla-custodios">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Teléfono</th>
                                <th>Cargo</th>
                                <th>GPS Asignados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ASIGNAR GPS -->
        <div id="module-asignar" class="module-content hidden">
            <div class="content-header">
                <h2>Asignar GPS a Custodio</h2>
                <p>Registra la entrega de un equipo GPS</p>
            </div>
            <div class="form-card">
                <div class="form-header">
                    <h3>📡 Nueva Asignación de GPS</h3>
                </div>
                <form id="form-asignar" onsubmit="asignarGPS(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-satellite-dish"></i> GPS</label>
                            <select class="form-select" name="gpsId" required>
                                <option value="">Seleccione un GPS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Custodio</label>
                            <select class="form-select" name="custodioId" required>
                                <option value="">Seleccione custodio</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Cliente</label>
                            <input type="text" class="form-input" name="cliente" placeholder="Ej: Empresa XYZ, Institución ABC" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-map-pin"></i> Origen</label>
                            <input type="text" class="form-input" name="origen" placeholder="Ej: Almacén Central, Oficina Principal" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar"></i> Fecha y Hora de Asignación</label>
                        <input type="datetime-local" class="form-input" name="fechaAsignacion" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Destino / Ubicación</label>
                        <input type="text" class="form-input" name="destino" placeholder="Ej: Patrullaje Zona Norte" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-comment"></i> Observaciones</label>
                        <textarea class="form-textarea" name="observaciones" placeholder="Notas adicionales sobre la asignación..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.1rem;">
                        <i class="fas fa-check"></i> Asignar GPS
                    </button>
                </form>
            </div>
        </div>

        <!-- RETORNAR GPS -->
        <div id="module-retornar" class="module-content hidden">
            <div class="content-header">
                <h2>Retornar GPS</h2>
                <p>Registra la devolución de un equipo GPS</p>
            </div>
            <div class="form-card">
                <div class="form-header">
                    <h3>🔄 Retorno de GPS</h3>
                </div>
                <form id="form-retornar" onsubmit="retornarGPS(event)">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-satellite-dish"></i> GPS Asignado</label>
                        <select class="form-select" name="asignacionId" required onchange="mostrarInfoRetorno(this.value)">
                            <option value="">Seleccione GPS a retornar</option>
                        </select>
                    </div>
                    <div id="info-retorno" class="hidden">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle" style="font-size: 1.5rem;"></i>
                            <div id="info-retorno-contenido"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar"></i> Fecha y Hora de Retorno</label>
                        <input type="datetime-local" class="form-input" name="fechaRetorno" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-clipboard-check"></i> Estado del GPS</label>
                        <select class="form-select" name="estadoGPS" required>
                            <option value="">Seleccione estado</option>
                            <option value="perfecto">Perfecto Estado</option>
                            <option value="bueno">Buen Estado</option>
                            <option value="regular">Estado Regular</option>
                            <option value="dañado">Dañado - Requiere Reparación</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-comment"></i> Observaciones</label>
                        <textarea class="form-textarea" name="observacionesRetorno" placeholder="Describa el estado del GPS y cualquier novedad..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 1.25rem; font-size: 1.1rem;">
                        <i class="fas fa-check-circle"></i> Registrar Retorno
                    </button>
                </form>
            </div>
        </div>

        <!-- CONSULTAR GPS -->
        <div id="module-consulta" class="module-content hidden">
            <div class="content-header">
                <h2>Consultar Estado de GPS</h2>
                <p>Busca información completa sobre un GPS</p>
            </div>

            <div class="card" style="max-width: 600px;">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-search"></i> IMEI / Número de Serie
                    </label>
                    <div class="flex gap-2">
                        <input type="text" class="form-input" id="buscar-imei-consulta" placeholder="Ingrese IMEI del GPS">
                        <button class="btn btn-primary" onclick="consultarGPS()">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div id="resultado-consulta" class="hidden">
                <div class="card">
                    <h3 class="mb-4" style="font-size: 1.25rem; font-weight: 700;">Información del GPS</h3>
                    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div>
                            <p style="font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-secondary);">IMEI/Serie</p>
                            <p id="info-imei" style="font-family: monospace; font-size: 1.25rem; font-weight: 700;"></p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Estado</p>
                            <span id="info-estado-gps" class="badge"></span>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Marca</p>
                            <p id="info-marca" style="font-size: 1.25rem; font-weight: 700;"></p>
                        </div>
                        <div>
                            <p style="font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-secondary);">Modelo</p>
                            <p id="info-modelo" style="font-size: 1.25rem; font-weight: 700;"></p>
                        </div>
                    </div>
                </div>

                <div id="info-asignacion-actual" class="hidden"></div>
            </div>
        </div>

        <!-- HISTORIAL -->
        <div id="module-historial" class="module-content hidden">
            <div class="content-header">
                <h2>Historial Completo</h2>
                <p>Registro de todos los movimientos de GPS</p>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 1rem; font-size: 1.25rem; font-weight: 700;">Todas las Asignaciones</h3>
                <div class="table-container">
                    <table id="tabla-historial">
                        <thead>
                            <tr>
                                <th>IMEI/Serie</th>
                                <th>Custodio</th>
                                <th>Cliente</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Fecha Asignación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- MODALES -->
    <div class="modal" id="modal-gps">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-satellite-dish"></i> Agregar Nuevo GPS</h3>
                <button class="close-modal" onclick="closeModal('modal-gps')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="form-gps" onsubmit="agregarGPS(event)">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-barcode"></i> IMEI / Número de Serie</label>
                        <input type="text" class="form-input" name="imei" required placeholder="Ej: 123456789012345">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Marca</label>
                            <input type="text" class="form-input" name="marca" required placeholder="Ej: Garmin, TomTom">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tag"></i> Modelo</label>
                            <input type="text" class="form-input" name="modelo" required placeholder="Ej: GPS-200">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-comment"></i> Descripción</label>
                        <textarea class="form-textarea" name="descripcion" placeholder="Características adicionales del GPS..."></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-gps')" style="flex: 1; padding: 1rem;">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem;">
                            <i class="fas fa-check"></i> Guardar GPS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-custodio">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield"></i> Agregar Nuevo Custodio</h3>
                <button class="close-modal" onclick="closeModal('modal-custodio')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="form-custodio" onsubmit="agregarCustodio(event)">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user"></i> Nombre Completo</label>
                        <input type="text" class="form-input" name="nombre" required placeholder="Ej: Juan Pérez López">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone"></i> Teléfono</label>
                        <input type="tel" class="form-input" name="telefono" required placeholder="Ej: +504 9999-9999">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-briefcase"></i> Cargo</label>
                        <input type="text" class="form-input" name="cargo" required placeholder="Ej: Oficial, Agente">
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('modal-custodio')" style="flex: 1; padding: 1rem;">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem;">
                            <i class="fas fa-check"></i> Guardar Custodio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ===== FUNCIONES RESPONSIVE DEL MENU LATERAL =====
        let sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        function toggleSidebarCollapse() {
            sidebarCollapsed = !sidebarCollapsed;
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleIcon = document.getElementById('toggle-icon');

            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
                toggleIcon.className = 'fas fa-chevron-left';
                localStorage.setItem('sidebar-collapsed', 'false');
            }
        } // ===== FUNCIONES RESPONSIVE DEL MENU LATERAL =====

        let gpsDispositivos = [];
        let custodios = [];
        let asignaciones = [];

        // ===== FUNCIONES DE UTILIDAD =====
        function toggleNotificaciones() {
            const panel = document.getElementById('notification-panel');
            if (panel) {
                panel.classList.toggle('active');
                console.log('Panel toggle - clase active:', panel.classList.contains('active'));
            } else {
                console.error('Panel de notificaciones no encontrado');
            }
        }

        function toggleUserMenu() {
            document.getElementById('user-dropdown').classList.toggle('hidden');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');

            document.getElementById('theme-icon').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            document.getElementById('theme-text').textContent = isDark ? 'Tema Claro' : 'Tema Oscuro';
            document.getElementById('theme-icon-menu').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            document.getElementById('theme-text-menu').textContent = isDark ? 'Modo Claro' : 'Modo Oscuro';

            localStorage.setItem('forza-gps-theme', isDark ? 'dark' : 'light');
        }

        function irAPanelAdmin() {
            window.location.href = 'panel.php';
        }

        function cerrarSesion() {
            if (confirm('¿Está seguro que desea cerrar sesión?')) {
                window.location.href = 'login.php';
            }
        }

        function showModalGPS() {
            document.getElementById('modal-gps').classList.add('active');
            document.querySelector('#modal-gps h3').textContent = '🛰️ Agregar Nuevo GPS';
            document.querySelector('#form-gps').onsubmit = agregarGPS;
            document.getElementById('form-gps').reset();
        }

        function editarGPS(id) {
            const gps = gpsDispositivos.find(g => g.id === id);
            document.querySelector('#modal-gps h3').textContent = '✏️ Editar GPS';
            document.querySelector('#form-gps input[name="imei"]').value = gps.imei;
            document.querySelector('#form-gps input[name="marca"]').value = gps.marca;
            document.querySelector('#form-gps input[name="modelo"]').value = gps.modelo;
            document.querySelector('#form-gps textarea[name="descripcion"]').value = gps.descripcion;
            document.querySelector('#form-gps').onsubmit = function(event) {
                guardarGPS(event, id);
            };
            document.getElementById('modal-gps').classList.add('active');
        }

        function showModalCustodio() {
            document.getElementById('modal-custodio').classList.add('active');
            document.querySelector('#modal-custodio h3').textContent = '👤 Agregar Nuevo Custodio';
            document.querySelector('#form-custodio').onsubmit = agregarCustodio;
            document.getElementById('form-custodio').reset();
        }

        function editarCustodio(id) {
            const custodio = custodios.find(c => c.id === id);
            document.querySelector('#modal-custodio h3').textContent = '✏️ Editar Custodio';
            document.querySelector('#form-custodio input[name="nombre"]').value = custodio.nombre;
            document.querySelector('#form-custodio input[name="telefono"]').value = custodio.telefono;
            document.querySelector('#form-custodio input[name="cargo"]').value = custodio.cargo;
            document.querySelector('#form-custodio').onsubmit = function(event) {
                guardarCustodio(event, id);
            };
            document.getElementById('modal-custodio').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function showModule(moduleName) {
            document.querySelectorAll('.module-content').forEach(module => module.classList.add('hidden'));
            document.getElementById('module-' + moduleName).classList.remove('hidden');
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
        }

        function agregarNotificacion(titulo, mensaje) {
            const notificationList = document.getElementById('notification-list');
            const notifBadge = document.getElementById('notif-badge');

            const notifItem = document.createElement('div');
            notifItem.className = 'notification-item';
            const notifId = 'notif-' + Date.now();
            notifItem.id = notifId;
            notifItem.innerHTML = `
                <div class="notification-item-header">
                    <span class="notification-item-title">🔔 ${titulo}</span>
                    <span class="notification-item-time">Ahora</span>
                </div>
                <div class="notification-item-body">${mensaje}</div>
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <button class="btn btn-success" onclick="confirmarNotificacion('${notifId}')" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                        <i class="fas fa-check"></i> Confirmar lectura
                    </button>
                </div>
            `;

            notificationList.insertBefore(notifItem, notificationList.firstChild);

            const currentCount = parseInt(notifBadge.textContent) || 0;
            notifBadge.textContent = currentCount + 1;
            notifBadge.classList.remove('hidden');
        }

        function confirmarNotificacion(notifId) {
            const notifItem = document.getElementById(notifId);
            if (notifItem) {
                notifItem.style.opacity = '0.5';
                notifItem.style.textDecoration = 'line-through';
                const notifBadge = document.getElementById('notif-badge');
                const currentCount = parseInt(notifBadge.textContent) || 0;
                if (currentCount > 0) {
                    notifBadge.textContent = currentCount - 1;
                    if (currentCount - 1 === 0) {
                        notifBadge.classList.add('hidden');
                    }
                }
                setTimeout(() => {
                    notifItem.remove();
                }, 300);
            }
        }

        // ===== FUNCIONES DE DATOS =====
        document.addEventListener('DOMContentLoaded', function() {
            cargarDatos();
            cargarDatosUsuario();
            actualizarTodo();

            const tema = localStorage.getItem('forza-gps-theme');
            if (tema === 'dark') {
                document.body.classList.add('dark-mode');
                document.getElementById('theme-icon').className = 'fas fa-sun';
                document.getElementById('theme-text').textContent = 'Tema Claro';
                document.getElementById('theme-icon-menu').className = 'fas fa-sun';
                document.getElementById('theme-text-menu').textContent = 'Modo Claro';
            }

            // Verificar que el botón de notificaciones existe
            const btnNotif = document.querySelector('button[onclick="toggleNotificaciones()"]');
            if (btnNotif) {
                console.log('Botón de notificaciones encontrado');
            } else {
                console.error('Botón de notificaciones NO encontrado');
            }



            // Restaurar estado del sidebar
            if (sidebarCollapsed) {
                const sidebar = document.getElementById('sidebar');
                const topBar = document.querySelector('.top-bar');
                const mainContent = document.querySelector('.main-content');
                const toggleIcon = document.getElementById('toggle-icon');

                sidebar.classList.add('collapsed');
                topBar.classList.add('sidebar-collapsed');
                mainContent.classList.add('sidebar-collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
            }

        });

        function cargarDatosUsuario() {
            // Intentar cargar desde localStorage
            const usuarioLogueado = localStorage.getItem('usuario-logueado');

            if (usuarioLogueado) {
                try {
                    const usuario = JSON.parse(usuarioLogueado);

                    // Actualizar nombre en el menú superior
                    if (usuario.nombre) {
                        document.getElementById('user-menu-nombre').textContent = usuario.nombre;
                        document.getElementById('dropdown-nombre').textContent = usuario.nombre;
                    }

                    // Actualizar email
                    if (usuario.email) {
                        document.getElementById('dropdown-email').textContent = usuario.email;
                    }

                    // Actualizar rol
                    if (usuario.rol) {
                        const rolCapitalizado = usuario.rol.charAt(0).toUpperCase() + usuario.rol.slice(1);
                        document.getElementById('user-menu-rol').textContent = rolCapitalizado;
                        document.getElementById('dropdown-rol').textContent = rolCapitalizado;
                    }

                    console.log('Datos de usuario cargados:', usuario);
                } catch (e) {
                    console.error('Error al parsear datos de usuario:', e);
                }
            } else {
                console.warn('No se encontraron datos de usuario logueado');
                // Mantener valores por defecto
            }
        }

        function guardarDatos() {
            localStorage.setItem('forza-gps', JSON.stringify(gpsDispositivos));
            localStorage.setItem('forza-custodios-gps', JSON.stringify(custodios));
            localStorage.setItem('forza-asignaciones', JSON.stringify(asignaciones));
        }

        function cargarDatos() {
            gpsDispositivos = JSON.parse(localStorage.getItem('forza-gps') || '[]');
            custodios = JSON.parse(localStorage.getItem('forza-custodios-gps') || '[]');
            asignaciones = JSON.parse(localStorage.getItem('forza-asignaciones') || '[]');
        }

        function actualizarTodo() {
            actualizarDashboard();
            actualizarTablaGPS();
            actualizarTablaCustodios();
            actualizarTablaHistorial();
            cargarSelectores();
        }

        function agregarGPS(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const imei = formData.get('imei').trim();

            if (gpsDispositivos.some(g => g.imei.toLowerCase() === imei.toLowerCase())) {
                alert('❌ Ya existe un GPS con este IMEI');
                return;
            }

            const nuevoGPS = {
                id: Date.now(),
                imei: imei,
                marca: formData.get('marca').trim(),
                modelo: formData.get('modelo').trim(),
                descripcion: formData.get('descripcion') || '',
                estado: 'disponible',
                custodioActual: null
            };
            gpsDispositivos.push(nuevoGPS);
            guardarDatos();
            actualizarTodo();
            agregarNotificacion('Nuevo GPS agregado', `GPS ${nuevoGPS.imei} registrado exitosamente`);
            event.target.reset();
            closeModal('modal-gps');
            alert('✅ GPS agregado correctamente');
        }

        function guardarGPS(event, idEditar) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const imei = formData.get('imei').trim();

            const gps = gpsDispositivos.find(g => g.id === idEditar);
            gps.imei = imei;
            gps.marca = formData.get('marca').trim();
            gps.modelo = formData.get('modelo').trim();
            gps.descripcion = formData.get('descripcion');

            guardarDatos();
            actualizarTodo();
            event.target.reset();
            closeModal('modal-gps');
            alert('✅ GPS actualizado correctamente');
        }

        function eliminarGPS(id) {
            const gps = gpsDispositivos.find(g => g.id === id);
            if (gps.estado === 'asignado') {
                alert('❌ No se puede eliminar un GPS que está asignado');
                return;
            }
            if (confirm('¿Está seguro de eliminar este GPS?')) {
                gpsDispositivos = gpsDispositivos.filter(g => g.id !== id);
                guardarDatos();
                actualizarTodo();
                alert('✅ GPS eliminado correctamente');
            }
        }

        function agregarCustodio(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const telefono = formData.get('telefono').trim();

            if (custodios.some(c => c.telefono === telefono)) {
                alert('❌ Ya existe un custodio con este número de teléfono');
                return;
            }

            const nuevoCustodio = {
                id: Date.now(),
                nombre: formData.get('nombre').trim(),
                telefono: telefono,
                cargo: formData.get('cargo').trim()
            };
            custodios.push(nuevoCustodio);
            guardarDatos();
            actualizarTodo();
            event.target.reset();
            closeModal('modal-custodio');
            alert('✅ Custodio agregado correctamente');
        }

        function guardarCustodio(event, idEditar) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const telefono = formData.get('telefono').trim();

            const custodio = custodios.find(c => c.id === idEditar);
            custodio.nombre = formData.get('nombre').trim();
            custodio.telefono = telefono;
            custodio.cargo = formData.get('cargo').trim();

            guardarDatos();
            actualizarTodo();
            event.target.reset();
            closeModal('modal-custodio');
            alert('✅ Custodio actualizado correctamente');
        }

        function eliminarCustodio(id) {
            const tieneGPSAsignado = asignaciones.some(a => a.custodioId === id && a.estado === 'asignado');
            if (tieneGPSAsignado) {
                alert('❌ No se puede eliminar un custodio que tiene GPS asignados');
                return;
            }
            if (confirm('¿Está seguro de eliminar este custodio?')) {
                custodios = custodios.filter(c => c.id !== id);
                guardarDatos();
                actualizarTodo();
                alert('✅ Custodio eliminado correctamente');
            }
        }

        function asignarGPS(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const gpsId = parseInt(formData.get('gpsId'));
            const custodioId = parseInt(formData.get('custodioId'));

            const gps = gpsDispositivos.find(g => g.id === gpsId);
            const custodio = custodios.find(c => c.id === custodioId);

            const nuevaAsignacion = {
                id: Date.now(),
                gpsId,
                custodioId,
                cliente: formData.get('cliente').trim(),
                origen: formData.get('origen').trim(),
                fechaAsignacion: formData.get('fechaAsignacion'),
                destino: formData.get('destino'),
                observaciones: formData.get('observaciones'),
                fechaRetorno: null,
                estado: 'asignado'
            };

            gpsDispositivos = gpsDispositivos.map(g =>
                g.id === gpsId ? {
                    ...g,
                    estado: 'asignado',
                    custodioActual: custodioId
                } : g
            );
            asignaciones.push(nuevaAsignacion);
            guardarDatos();
            actualizarTodo();
            agregarNotificacion('GPS Asignado', `GPS ${gps.imei} asignado a ${custodio.nombre} - Cliente: ${nuevaAsignacion.cliente}`);
            event.target.reset();
            alert('✅ GPS asignado correctamente');
        }

        function retornarGPS(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const asignacionId = parseInt(formData.get('asignacionId'));
            const asignacion = asignaciones.find(a => a.id === asignacionId);

            const gps = gpsDispositivos.find(g => g.id === asignacion.gpsId);
            const custodio = custodios.find(c => c.id === asignacion.custodioId);

            asignaciones = asignaciones.map(a => a.id === asignacionId ? {
                ...a,
                fechaRetorno: formData.get('fechaRetorno'),
                estadoRetorno: formData.get('estadoGPS'),
                observacionesRetorno: formData.get('observacionesRetorno'),
                estado: 'retornado'
            } : a);

            gpsDispositivos = gpsDispositivos.map(g =>
                g.id === asignacion.gpsId ? {
                    ...g,
                    estado: 'disponible',
                    custodioActual: null
                } : g
            );

            guardarDatos();
            actualizarTodo();
            agregarNotificacion('GPS Retornado', `GPS ${gps.imei} retornado por ${custodio.nombre}`);
            event.target.reset();
            document.getElementById('info-retorno').classList.add('hidden');
            alert('✅ Retorno registrado correctamente');
        }

        function actualizarDashboard() {
            const disponibles = gpsDispositivos.filter(g => g.estado === 'disponible').length;
            const asignados = gpsDispositivos.filter(g => g.estado === 'asignado').length;

            document.getElementById('stat-disponibles').textContent = disponibles;
            document.getElementById('stat-asignados').textContent = asignados;
            document.getElementById('stat-total').textContent = gpsDispositivos.length;

            const tbody = document.querySelector('#tabla-gps-asignados tbody');
            const asignacionesActivas = asignaciones.filter(a => a.estado === 'asignado');

            if (asignacionesActivas.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center">
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h3>No hay GPS asignados</h3>
                        <p>Todos los GPS están disponibles</p>
                    </div>
                </td></tr>`;
                return;
            }

            let html = '';
            asignacionesActivas.forEach(asignacion => {
                const gps = gpsDispositivos.find(g => g.id === asignacion.gpsId);
                const custodio = custodios.find(c => c.id === asignacion.custodioId);
                const dias = Math.floor((new Date() - new Date(asignacion.fechaAsignacion)) / (1000 * 60 * 60 * 24));

                html += `
                    <tr>
                        <td style="font-family: monospace;">${gps?.imei || 'N/A'}</td>
                        <td>${gps?.marca} ${gps?.modelo}</td>
                        <td>${custodio?.nombre || 'N/A'}</td>
                        <td>${new Date(asignacion.fechaAsignacion).toLocaleString('es-HN')}</td>
                        <td>${dias} días</td>
                        <td><span class="ubicacion-badge ubicacion-campo"><i class="fas fa-map-marker-alt"></i> En Campo</span></td>
                        <td><span class="badge badge-warning">Asignado</span></td>
                    </tr>`;
            });
            tbody.innerHTML = html;
        }

        function actualizarTablaGPS() {
            const tbody = document.querySelector('#tabla-gps tbody');
            if (gpsDispositivos.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center;">
            <div class="empty-state">
                <i class="fas fa-satellite-dish"></i>
                <h3>No hay GPS registrados</h3>
                <p>Comienza agregando tu primer GPS</p>
            </div>
        </td></tr>`;
                return;
            }

            let html = '';
            gpsDispositivos.forEach(gps => {
                const custodio = custodios.find(c => c.id === gps.custodioActual);
                let estadoClass = '',
                    estadoTexto = '',
                    ubicacion = '';

                switch (gps.estado) {
                    case 'disponible':
                        estadoClass = 'badge-success';
                        estadoTexto = 'Disponible';
                        ubicacion = '<span class="ubicacion-badge ubicacion-instalaciones"><i class="fas fa-building"></i> Instalaciones</span>';
                        break;
                    case 'asignado':
                        estadoClass = 'badge-warning';
                        estadoTexto = 'Asignado';
                        ubicacion = '<span class="ubicacion-badge ubicacion-campo"><i class="fas fa-map-marker-alt"></i> En Campo</span>';
                        break;
                }

                html += `
            <tr>
                <td style="font-family: monospace;">${gps.imei}</td>
                <td>${gps.marca}</td>
                <td>${gps.modelo}</td>
                <td><span class="badge ${estadoClass}">${estadoTexto}</span></td>
                <td>${ubicacion}</td>
                <td>${custodio?.nombre || '-'}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn" onclick="editarGPS(${gps.id})" style="padding: 0.5rem 1rem; background-color: #fbbf24; color: #000; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s;">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger" onclick="eliminarGPS(${gps.id})" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </td>
            </tr>`;
            });
            tbody.innerHTML = html;
        }

        function actualizarTablaCustodios() {
            const tbody = document.querySelector('#tabla-custodios tbody');
            if (custodios.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No hay custodios registrados</h3>
                <p>Agrega el primer custodio</p>
            </div>
        </td></tr>`;
                return;
            }

            let html = '';
            custodios.forEach(custodio => {
                const gpsAsignados = asignaciones.filter(a => a.custodioId === custodio.id && a.estado === 'asignado').length;
                html += `
            <tr>
                <td>${custodio.nombre}</td>
                <td>${custodio.telefono}</td>
                <td>${custodio.cargo}</td>
                <td><span class="badge badge-info">${gpsAsignados}</span></td>
                <td>
                    <div class="btn-group">
                        <button class="btn" onclick="editarCustodio(${custodio.id})" style="padding: 0.5rem 1rem; background-color: #fbbf24; color: #000; font-weight: 600;">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger" onclick="eliminarCustodio(${custodio.id})" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </td>
            </tr>`;
            });
            tbody.innerHTML = html;
        }

        function actualizarTablaHistorial() {
            const tbody = document.querySelector('#tabla-historial tbody');
            if (asignaciones.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center;">
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <h3>No hay historial</h3>
                        <p>Las asignaciones aparecerán aquí</p>
                    </div>
                </td></tr>`;
                return;
            }

            let html = '';
            asignaciones.slice().reverse().forEach(asignacion => {
                const gps = gpsDispositivos.find(g => g.id === asignacion.gpsId);
                const custodio = custodios.find(c => c.id === asignacion.custodioId);
                const estadoClass = asignacion.estado === 'asignado' ? 'badge-warning' : 'badge-success';
                const estadoTexto = asignacion.estado.charAt(0).toUpperCase() + asignacion.estado.slice(1);

                html += `
                    <tr>
                        <td style="font-family: monospace;">${gps?.imei || 'N/A'}</td>
                        <td>${custodio?.nombre || 'N/A'}</td>
                        <td>${asignacion.cliente || '-'}</td>
                        <td>${asignacion.origen || '-'}</td>
                        <td>${asignacion.destino || '-'}</td>
                        <td>${new Date(asignacion.fechaAsignacion).toLocaleString('es-HN')}</td>
                        <td><span class="badge ${estadoClass}">${estadoTexto}</span></td>
                    </tr>`;
            });
            tbody.innerHTML = html;
        }

        function cargarSelectores() {
            const selectGPS = document.querySelector('#form-asignar select[name="gpsId"]');
            if (selectGPS) {
                const disponibles = gpsDispositivos.filter(g => g.estado === 'disponible');
                selectGPS.innerHTML = '<option value="">Seleccione un GPS</option>';
                disponibles.forEach(g => {
                    selectGPS.innerHTML += `<option value="${g.id}">${g.imei} - ${g.marca} ${g.modelo}</option>`;
                });
            }

            document.querySelectorAll('select[name="custodioId"]').forEach(select => {
                select.innerHTML = '<option value="">Seleccione custodio</option>';
                custodios.forEach(c => {
                    select.innerHTML += `<option value="${c.id}">${c.nombre} - ${c.cargo}</option>`;
                });
            });

            const activos = asignaciones.filter(a => a.estado === 'asignado');
            const selectRetornar = document.querySelector('#form-retornar select[name="asignacionId"]');
            if (selectRetornar) {
                selectRetornar.innerHTML = '<option value="">Seleccione GPS</option>';
                activos.forEach(a => {
                    const gps = gpsDispositivos.find(g => g.id === a.gpsId);
                    const cust = custodios.find(c => c.id === a.custodioId);
                    selectRetornar.innerHTML += `<option value="${a.id}">${gps?.imei} (${cust?.nombre})</option>`;
                });
            }
        }

        function mostrarInfoRetorno(id) {
            if (!id) {
                document.getElementById('info-retorno').classList.add('hidden');
                return;
            }

            const a = asignaciones.find(x => x.id === parseInt(id));
            const gps = gpsDispositivos.find(g => g.id === a.gpsId);
            const c = custodios.find(x => x.id === a.custodioId);
            const dias = Math.floor((Date.now() - new Date(a.fechaAsignacion)) / 86400000);

            document.getElementById('info-retorno').innerHTML = `
                <div class="card" style="margin-bottom: 1.5rem; border-left: 5px solid var(--info);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="font-size: 1.8rem;">ℹ️</div>
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 700;">Información de la Asignación</h3>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Detalles del GPS a retornar</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">GPS (IMEI)</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary); font-family: monospace;">${gps.imei}</p>
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">${gps.marca} ${gps.modelo}</p>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Custodio</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">${c.nombre}</p>
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">${c.cargo}</p>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Teléfono</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">${c.telefono}</p>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Cliente</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">${a.cliente}</p>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Origen</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">${a.origen}</p>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                            <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Destino</p>
                            <p style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">${a.destino}</p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.75rem;">Duración de la Asignación</p>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                            <div>
                                <p style="font-size: 0.85rem; color: var(--text-secondary);">Asignado desde</p>
                                <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">${new Date(a.fechaAsignacion).toLocaleString('es-HN')}</p>
                            </div>
                            <div>
                                <p style="font-size: 0.85rem; color: var(--text-secondary);">Días en posesión</p>
                                <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">${dias} día${dias !== 1 ? 's' : ''}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('info-retorno').classList.remove('hidden');
        }

        function consultarGPS() {
            const imei = document.getElementById('buscar-imei-consulta').value.trim();
            if (!imei) {
                alert('Por favor ingrese un IMEI');
                return;
            }

            const gps = gpsDispositivos.find(g => g.imei.toLowerCase().includes(imei.toLowerCase()));
            if (!gps) {
                alert('❌ GPS no encontrado');
                document.getElementById('resultado-consulta').classList.add('hidden');
                return;
            }

            document.getElementById('info-imei').textContent = gps.imei;
            document.getElementById('info-marca').textContent = gps.marca;
            document.getElementById('info-modelo').textContent = gps.modelo;

            const estadoGps = document.getElementById('info-estado-gps');
            if (gps.estado === 'disponible') {
                estadoGps.className = 'badge badge-success';
                estadoGps.textContent = 'Disponible';
            } else if (gps.estado === 'asignado') {
                estadoGps.className = 'badge badge-warning';
                estadoGps.textContent = 'Asignado';
            }

            const asignacionActual = asignaciones.find(a => a.gpsId === gps.id && a.estado === 'asignado');
            const infoAsignacion = document.getElementById('info-asignacion-actual');

            if (asignacionActual) {
                const custodio = custodios.find(c => c.id === asignacionActual.custodioId);
                const dias = Math.floor((Date.now() - new Date(asignacionActual.fechaAsignacion)) / 86400000);

                infoAsignacion.innerHTML = `
                    <div class="card" style="margin-top: 2rem; border-left: 5px solid var(--warning);">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="font-size: 2rem;">⚠️</div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--warning);">GPS ACTUALMENTE ASIGNADO</h3>
                                <p style="color: var(--text-secondary); font-size: 0.9rem;">Información detallada de la asignación activa</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Custodio</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">${custodio.nombre}</p>
                            </div>
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Teléfono</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); font-family: monospace;">${custodio.telefono}</p>
                            </div>
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Cargo</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">${custodio.cargo}</p>
                            </div>
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Cliente</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">${asignacionActual.cliente}</p>
                            </div>
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Origen</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">${asignacionActual.origen}</p>
                            </div>
                            <div style="padding: 1rem; background: var(--bg-secondary); border-radius: 10px;">
                                <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Destino</p>
                                <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">${asignacionActual.destino}</p>
                            </div>
                        </div>

                        <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                            <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1rem;">Detalles de la Asignación</h4>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                                <div>
                                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Fecha de Asignación</p>
                                    <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">${new Date(asignacionActual.fechaAsignacion).toLocaleString('es-HN')}</p>
                                </div>
                                <div>
                                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Días Asignado</p>
                                    <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">${dias} día${dias !== 1 ? 's' : ''}</p>
                                </div>
                                <div>
                                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Estado</p>
                                    <span class="badge badge-warning">Asignado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                infoAsignacion.classList.remove('hidden');
            } else {
                infoAsignacion.innerHTML = `
                    <div class="card" style="margin-top: 2rem; border-left: 5px solid var(--success);">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="font-size: 2rem;">✅</div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--success);">GPS DISPONIBLE</h3>
                                <p style="color: var(--text-secondary);">Este GPS se encuentra actualmente disponible para asignación</p>
                            </div>
                        </div>
                    </div>
                `;
                infoAsignacion.classList.remove('hidden');
            }

            document.getElementById('resultado-consulta').classList.remove('hidden');
        }

        function filtrarTablaGPS() {
            const term = document.getElementById('buscar-gps-tabla').value.toLowerCase();
            document.querySelectorAll('#tabla-gps tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        }

        function filtrarTablaCustodios() {
            const term = document.getElementById('buscar-custodio-tabla').value.toLowerCase();
            document.querySelectorAll('#tabla-custodios tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        }

        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }

            if (!e.target.closest('.user-menu-container')) {
                document.getElementById('user-dropdown').classList.add('hidden');
            }
        }
    </script>
</body>

</html>