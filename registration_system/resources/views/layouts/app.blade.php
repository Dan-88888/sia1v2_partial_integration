<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="University Registration System — Seamless enrollment, scheduling, and academic management">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/PSU.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* ===== CSS CUSTOM PROPERTIES ===== */
        :root {
            --navy: #1b2a4a;
            --navy-light: #1e3a8a;
            --navy-dark: #0f172a;
            --gold: #FFD700;
            --gold-light: #FFE44D;
            --gold-dark: #CCB000;
            --bg-primary: #ffffff; /* Clear White as requested */
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --glass-bg: #ffffff;
            --glass-border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent-gradient: var(--navy); /* No gradients as requested */
            --card-shadow: none; /* No shading as requested */
            --border: #e2e8e0;
            --sidebar-width: 260px;
            --navbar-height: 100px;
            --transition: all 0.2s ease;
        }

        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Background Mesh Removed for Solid White Look */

        /* ===== NAVBAR ===== */
        .topbar-gov {
            background: #fdfdfd;
            border-bottom: 1px solid #e0e0e0;
            padding: 2px 0;
            font-size: 0.7rem;
            color: #333;
            font-weight: 600;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1051;
            height: 30px;
            display: flex;
            align-items: center;
        }

        .navbar-main {
            background: var(--navy); /* Solid Navy Locked */
            border-bottom: 2px solid var(--gold);
            padding: 0.75rem 0;
            position: fixed;
            top: 30px; 
            left: 0;
            right: 0;
            z-index: 1000; /* Lower than backdrop (1050) */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: var(--transition);
        }

        .navbar-brand-custom:hover {
            transform: translateY(-1px);
        }

        .brand-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.3px;
        }

        .brand-tagline {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-link-custom {
            color: var(--text-secondary) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: var(--gold) !important;
            background: rgba(255, 215, 0, 0.08);
        }

        .nav-link-custom i {
            margin-right: 6px;
            font-size: 0.85rem;
        }

        /* ===== SIDEBAR (Authenticated) ===== */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: #1b2a4a; /* Solid Portas Navy as per Light Design request */
            border-right: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem 0;
            z-index: 990; /* Lower than backdrop (1050) */
            overflow-y: auto;
            transition: var(--transition);
        }

        .sidebar-section {
            padding: 0 1rem;
            margin-bottom: 1.5rem;
        }

        .sidebar-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.7rem 0.75rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            color: var(--gold);
            background: rgba(255, 215, 0, 0.08);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            color: var(--gold);
            background: rgba(255, 215, 0, 0.12);
            box-shadow: inset 3px 0 0 var(--gold);
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-badge {
            margin-left: auto;
            background: var(--accent-gradient);
            color: var(--navy);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* Student Info Card in Sidebar */
        .sidebar-profile {
            padding: 1rem;
            margin: 0 1rem 1rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
        }

        .sidebar-profile-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .sidebar-profile-id {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .sidebar-profile-badge {
            display: inline-block;
            margin-top: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-admitted {
            background: rgba(40, 199, 111, 0.15);
            color: #28c76f;
            border: 1px solid rgba(40, 199, 111, 0.3);
        }

        .badge-pending {
            background: rgba(255, 159, 67, 0.15);
            color: #ff9f43;
            border: 1px solid rgba(255, 159, 67, 0.3);
        }

        .badge-rejected {
            background: rgba(234, 84, 85, 0.15);
            color: #ea5455;
            border: 1px solid rgba(234, 84, 85, 0.3);
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            flex: 1;
            margin-top: var(--navbar-height);
            position: relative;
            z-index: 1;
        }

        .main-wrapper.with-sidebar {
            margin-left: var(--sidebar-width);
        }

        .content-area {
            padding: clamp(1rem, 3vw, 2.5rem);
            max-width: 100%;
            margin: 0;
            min-height: calc(100vh - var(--navbar-height) - 80px);
        }

        /* Auth pages - no sidebar, centered */
        .auth-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - var(--navbar-height));
            padding: 2rem;
        }

        /* ===== GLASSMORPHISM CARDS ===== */
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            transition: var(--transition);
        }

        .glass-card:hover {
            border-color: rgba(255, 215, 0, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        /* ===== MODERN FORM STYLES ===== */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
            letter-spacing: 0.3px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
            color: var(--text-primary);
        }

        .form-control.is-invalid {
            border-color: #ea5455;
        }

        .invalid-feedback {
            color: #ea5455;
            font-size: 0.8rem;
        }

        /* ===== BUTTONS ===== */
        .btn-gold {
            background: var(--gold);
            color: var(--navy) !important;
            border: 2px solid var(--gold);
            font-weight: 700;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2);
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-gold:hover {
            background: var(--navy);
            color: var(--gold) !important;
            border-color: var(--navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(27, 42, 74, 0.3);
        }

        .btn-gold:active {
            transform: translateY(0);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--gold) !important;
            border: 2px solid var(--gold);
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            transition: var(--transition);
        }

        .btn-outline-gold:hover {
            background: var(--gold);
            color: var(--navy) !important;
            transform: translateY(-2px);
            box-shadow: var(--glow-gold);
        }

        .btn-navy {
            background: var(--navy);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            transition: var(--transition);
        }

        .btn-navy:hover {
            background: var(--navy-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 53, 0.2);
            color: #ffffff !important;
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #ea5455 0%, #ff6b6b 100%);
            color: white !important;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .btn-danger-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234, 84, 85, 0.4);
            color: white !important;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #28c76f 0%, #48da89 100%);
            color: white !important;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .btn-success-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 199, 111, 0.4);
            color: white !important;
        }

        /* ===== MODERN TABLE ===== */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background: rgba(255, 215, 0, 0.08);
            color: var(--gold);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.8rem 1rem;
            border: none;
            border-bottom: 1px solid var(--glass-border);
        }

        .table-modern thead th:first-child {
            border-radius: 10px 0 0 0;
        }

        .table-modern thead th:last-child {
            border-radius: 0 10px 0 0;
        }

        .table-modern tbody td {
            padding: 0.8rem 1rem;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--text-secondary);
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: var(--transition);
        }

        .table-modern tbody tr:hover {
            background: rgba(255, 215, 0, 0.03);
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.success {
            background: rgba(40, 199, 111, 0.15);
            color: #28c76f;
        }

        .status-badge.warning {
            background: rgba(255, 159, 67, 0.15);
            color: #ff9f43;
        }

        .status-badge.danger {
            background: rgba(234, 84, 85, 0.15);
            color: #ea5455;
        }

        .status-badge.info {
            background: rgba(0, 207, 232, 0.15);
            color: #00cfe8;
        }

        /* ===== STAT CARD ===== */
        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            transition: var(--transition);
        }

        .stat-card:hover {
            border-color: rgba(255, 215, 0, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-icon.gold {
            background: rgba(255, 215, 0, 0.12);
            color: var(--gold);
        }

        .stat-icon.blue {
            background: rgba(0, 207, 232, 0.12);
            color: #00cfe8;
        }

        .stat-icon.green {
            background: rgba(40, 199, 111, 0.12);
            color: #28c76f;
        }

        .stat-icon.purple {
            background: rgba(115, 103, 240, 0.12);
            color: #7367f0;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ===== ALERTS ===== */
        .alert-modern {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        .alert-modern.alert-success {
            background: rgba(40, 199, 111, 0.12);
            color: #28c76f;
            border-left: 3px solid #28c76f;
        }

        .alert-modern.alert-danger {
            background: rgba(234, 84, 85, 0.12);
            color: #ea5455;
            border-left: 3px solid #ea5455;
        }

        .alert-modern.alert-warning {
            background: rgba(255, 159, 67, 0.12);
            color: #ff9f43;
            border-left: 3px solid #ff9f43;
        }

        .alert-modern.alert-info {
            background: rgba(0, 207, 232, 0.12);
            color: #00cfe8;
            border-left: 3px solid #00cfe8;
        }

        .alert-modern .btn-close {
            filter: invert(1);
            opacity: 0.5;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== HEADING STYLES ===== */
        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* ===== DROPDOWN MODERN ===== */
        .dropdown-menu {
            background: rgba(17, 22, 57, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            padding: 0.5rem;
            animation: fadeInUp 0.2s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            color: var(--text-secondary);
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(255, 215, 0, 0.1);
            color: var(--gold);
        }

        .dropdown-divider {
            border-color: var(--glass-border);
        }

        /* ===== FOOTER ===== */
        footer {
            background: rgba(10, 14, 39, 0.9);
            border-top: 1px solid var(--glass-border);
            padding: 1.25rem 0;
            margin-top: auto;
            position: relative;
            z-index: 1;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        /* ===== PAGINATION ===== */
        .pagination {
            gap: 4px;
        }

        .page-link {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            border-radius: 8px !important;
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .page-link:hover {
            background: rgba(255, 215, 0, 0.1);
            color: var(--gold);
            border-color: rgba(255, 215, 0, 0.2);
        }

        .page-item.active .page-link {
            background: var(--accent-gradient);
            color: var(--navy);
            border-color: var(--gold);
            font-weight: 700;
        }

        .page-item.disabled .page-link {
            background: transparent;
            color: var(--text-muted);
            border-color: transparent;
        }

        /* ===== PROGRESS BAR ===== */
        .progress-modern {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            height: 8px;
            overflow: hidden;
        }

        .progress-modern .progress-bar {
            background: var(--accent-gradient);
            border-radius: 20px;
            transition: width 0.6s ease;
        }

        .progress-modern .progress-bar.danger {
            background: linear-gradient(135deg, #ea5455 0%, #ff6b6b 100%);
        }

        /* ===== LOADING SCREEN ===== */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.4s ease;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 215, 0, 0.2);
            border-top: 3px solid var(--gold);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 215, 0, 0.4);
        }

        /* ===== SCROLL TO TOP ===== */
        .scroll-top-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 42px;
            height: 42px;
            background: var(--accent-gradient);
            color: var(--navy);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            z-index: 1000;
            border: none;
            font-size: 1rem;
        }

        .scroll-top-btn.show {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(255, 215, 0, 0.5);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper.with-sidebar {
                margin-left: 0;
            }

            .content-area {
                padding: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .brand-name {
                font-size: 0.9rem;
            }

            .brand-tagline {
                display: none;
            }

            .brand-logo {
                height: 35px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .page-title {
                font-size: 1.25rem;
            }
        }

        /* ===== ADMISSION PENDING BANNER ===== */
        .admission-pending-banner {
            background: linear-gradient(135deg, rgba(255, 159, 67, 0.12) 0%, rgba(255, 215, 0, 0.08) 100%);
            border: 1px solid rgba(255, 159, 67, 0.25);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
        }

        .admission-pending-banner h5 {
            color: #ff9f43;
            font-weight: 700;
        }

        .admission-pending-banner p {
            color: var(--text-secondary);
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* ===== CARD HEADER ===== */
        .card-header-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .card-header-modern h3, .card-header-modern h4 {
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }

        .card-header-modern i {
            color: var(--gold);
        }

        /* FORM CHECK CUSTOM */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: var(--gold);
            border-color: var(--gold);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Navbar toggler */
        .navbar-toggler {
            border-color: rgba(255,215,0,.3);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 215, 0, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Tab Styles */
        .nav-tabs-modern {
          border-bottom: 1px solid var(--glass-border);
          gap: 4px;
        }
        .nav-tabs-modern .nav-link {
          border: none;
          color: var(--text-muted);
          font-weight: 600;
          font-size: 0.9rem;
          padding: 0.7rem 1.25rem;
          border-radius: 10px 10px 0 0;
          transition: var(--transition);
        }
        .nav-tabs-modern .nav-link:hover {
          color: var(--gold);
          background: rgba(255,215,0,0.05);
        }
        .nav-tabs-modern .nav-link.active {
          color: var(--gold);
          background: rgba(255, 215, 0, 0.1);
          border-bottom: 2px solid var(--gold);
        }

        /* ===== CLEAR WHITE COMPONENT OVERRIDES ===== */
        .section-header {
            background: #ffffff;
            padding: 1.5rem 0;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9; /* Lighter border for clean look */
            transition: all 0.2s;
            box-shadow: none !important; /* Strictly no shading */
        }

        .stat-card:hover {
            border-color: var(--navy);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
        .stat-icon.green { background: #f0fdf4; color: #22c55e; }
        .stat-icon.gold { background: #fffbeb; color: #f59e0b; }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .glass-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: none !important; /* Strictly no shading */
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            padding: 12px 20px;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #f1f5f9;
            background: #fafafa;
        }

        .table-modern tbody tr {
            transition: all 0.2s;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
        }

        .table-modern tbody td {
            padding: 16px 20px;
            font-size: 0.85rem;
            color: var(--text-primary);
            border-bottom: 1px solid #f1f5f9;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-badge.success { background: #dcfce7; color: #166534; }
        .status-badge.warning { background: #fef9c3; color: #854d0e; }
        .status-badge.danger { background: #fee2e2; color: #991b1b; }

        .btn-navy { background: var(--navy) !important; color: #ffffff !important; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: background 0.2s; }
        .btn-navy:hover { background: var(--navy-light) !important; color: #ffffff !important; }

        .btn-success-modern { 
            background: #22c55e !important; 
            color: #ffffff !important; 
            border: none; 
            box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2);
            font-weight: 700;
        }
        .btn-danger-modern { 
            background: #ef4444 !important; 
            color: #ffffff !important; 
            border: none;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
            font-weight: 700;
        }

        /* Modern Form Styling */
        .form-modern-group { margin-bottom: 1.5rem; }
        .form-modern-label { 
            display: block; 
            font-weight: 700; 
            font-size: 0.8rem; 
            color: var(--navy); 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .form-modern-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-modern-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(27, 42, 74, 0.1);
        }

        .sidebar-link i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
            margin-right: 12px;
            opacity: 0.9;
        }

        /* Modal Overrides for Interaction Fix */
        .modal { z-index: 2000 !important; }
        .modal-backdrop { z-index: 1050 !important; }
    </style>
</head>
<body>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-spinner"></div>
</div>

<!-- Gov.ph Topbar -->
<div class="topbar-gov">
    <div class="container-fluid px-3 px-lg-4 d-flex justify-content-between align-items-center">
        <div style="font-weight: 800; font-size: 0.8rem; letter-spacing: 0.5px;">GOV.PH</div>
        <div class="d-none d-md-block text-center" style="font-weight: 600; font-size: 0.75rem;">
            SY: {{ $app_settings['active_school_year'] ?? '2024-2025' }} | Semester: {{ $app_settings['active_semester'] ?? '1' }}
        </div>
        <div class="d-none d-md-block text-end" style="font-weight: 500; font-size: 0.65rem; line-height: 1.2;">
            ISO 9001:2015 Certified<br>
            <span style="font-weight: 300;">Certificate No.: 01 100 1734809</span>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar-main" id="mainNavbar">
    <div class="container-fluid px-3 px-lg-4">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a class="navbar-brand-custom" href="{{ url('/') }}">
                <img src="{{ asset('images/nobgParsulogo.png') }}" alt="University Logo" class="brand-logo">
                <div class="brand-info">
                    <span class="brand-name" style="color: white; font-size: 1.3rem;">{{ strtoupper(explode(' ', $app_settings['school_name'] ?? 'PARTIDO STATE UNIVERSITY')[0]) }}</span>
                    <span class="brand-tagline" style="color: white; text-transform:none; font-size:0.9rem; letter-spacing:0.5px; font-weight:400; border-top: 1px solid rgba(255,255,255,0.4); padding-top:2px; margin-top:2px;">{{ strtoupper(implode(' ', array_slice(explode(' ', $app_settings['school_name'] ?? 'PARTIDO STATE UNIVERSITY'), 1))) }}</span>
                </div>
            </a>

            <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" style="background:transparent;border:1px solid rgba(255,215,0,.3);padding:6px 10px;border-radius:8px;">
                <span class="navbar-toggler-icon" style="width:20px;height:20px;"></span>
            </button>

            <!-- Middle ParSU Links Removed as Requested -->

            <div class="d-none d-lg-flex align-items-center gap-2">
                @guest
                    @if (Route::has('login'))
                        <a class="nav-link-custom {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a class="nav-link-custom {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    @endif
                @else
                    <div class="dropdown">
                        <a class="nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" 
                           style="font-size: 0.95rem; font-weight: 700; color: #ffffff !important; background: var(--navy-dark); padding: 8px 18px !important; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1);">
                            <i class="fas fa-user-circle me-1" style="color: var(--gold);"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-cog me-2"></i> Account Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

@auth
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-profile">
        <div class="sidebar-profile-name">{{ Auth::user()->name }}</div>
        <div class="sidebar-profile-id">
            @if(Auth::user()->student)
                {{ Auth::user()->student->student_number }}
            @else
                {{ Auth::user()->email }}
            @endif
        </div>
        @if(Auth::user()->student)
            <span class="sidebar-profile-badge 
                @if(Auth::user()->student->admission_status === 'admitted') badge-admitted
                @elseif(Auth::user()->student->admission_status === 'pending') badge-pending
                @else badge-rejected @endif">
                <i class="fas fa-circle" style="font-size: 6px;"></i>
                {{ Auth::user()->student->admission_badge }}
            </span>
        @endif
    </div>

    @if(Auth::user()->role === 'admin')
        @include('dashboard.partials.admin_sidebar')
    @elseif(Auth::user()->role === 'teacher')
        <div class="sidebar-section">
            <div class="sidebar-label">Main Menu</div>
            <a href="{{ route('teacher.dashboard') }}" class="sidebar-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('teacher.teaching_load') }}" class="sidebar-link {{ request()->routeIs('teacher.teaching_load') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> Teaching Load
            </a>
            <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                <i class="fas fa-university"></i> Departments
            </a>
        </div>
    @else
        <div class="sidebar-section">
            <div class="sidebar-label">Transactions</div>
            <a href="{{ route('student.transactions.pre_enlistment') }}" class="sidebar-link {{ request()->routeIs('student.transactions.pre_enlistment') ? 'active' : '' }}">
                <i class="fas fa-edit"></i> Pre-Enlistment
            </a>
            <a href="{{ route('student.transactions.enrollment') }}" class="sidebar-link {{ request()->routeIs('student.transactions.enrollment') ? 'active' : '' }}">
                <i class="fas fa-file-signature"></i> Enrollment
            </a>
            <a href="{{ route('student.finance') }}" class="sidebar-link {{ request()->routeIs('student.finance') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i> My Billing
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Reports</div>
            <a href="{{ route('student.reports.enrolled_subjects') }}" class="sidebar-link {{ request()->routeIs('student.reports.enrolled_subjects') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Enrolled Subjects
            </a>
            <a href="{{ route('student.reports.term_grades') }}" class="sidebar-link {{ request()->routeIs('student.reports.term_grades') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Term Grades
            </a>
            <a href="{{ route('enrollments.cor') }}" class="sidebar-link {{ request()->routeIs('enrollments.cor') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> View COR
            </a>
        </div>
    @endif

    <div class="sidebar-section" style="margin-top:auto;">
        <a href="{{ route('logout') }}" class="sidebar-link"
           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</aside>
@endauth

<!-- Main Content -->
<div class="main-wrapper @auth with-sidebar @endauth">
    @if(Route::currentRouteName() == 'login' || Route::currentRouteName() == 'register')
        <div class="auth-page">
            <div class="w-100" style="max-width: 480px;" data-aos="fade-up" data-aos-duration="600">
                @if(session('success'))
                    <div class="alert-modern alert-success alert alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-modern alert-danger alert alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    @else
        <div class="content-area">
            @if(session('success'))
                <div class="alert-modern alert-success alert alert-dismissible fade show mb-3" role="alert" data-aos="fade-down">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-modern alert-danger alert alert-dismissible fade show mb-3" role="alert" data-aos="fade-down">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    @endif
</div>

<footer @auth class="with-sidebar" style="margin-left: var(--sidebar-width);" @endauth>
    <div style="background: var(--navy); border-top: 3px solid var(--gold); color: #ffffff;">
        <div class="container-fluid px-5 py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6 text-center text-md-start">
                    <div style="background: white; width: 75px; height: 75px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; padding: 5px; overflow: hidden;">
                        <img src="{{ asset('images/nobgParsulogo.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h4 style="color: #ffffff; font-weight: 800; margin-bottom: 0.5rem; font-size:1.4rem;">Partido State University</h4>
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.85rem; margin-bottom: 1.5rem; line-height:1.6;">
                        San Juan Bautista, Goa,<br>
                        Camarines Sur, Philippines 4422
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                        <a href="#" style="color: #ffffff;"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" style="color: #ffffff;"><i class="fab fa-youtube fa-lg"></i></a>
                        <a href="#" style="color: #ffffff;"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 1.5rem; font-size:1.1rem;">Latest News</h5>
                    <div class="mb-4">
                        <a href="#" style="color: #ffffff; text-decoration: none; font-size: 0.85rem; display: block; font-weight: 700; line-height:1.4; margin-bottom: 5px;">ParSU Joins SSF Standards Push, Hosts Fisheries Validation Workshop</a>
                        <small style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">06 April 2026</small>
                    </div>
                    <div>
                        <a href="#" style="color: #ffffff; text-decoration: none; font-size: 0.85rem; display: block; font-weight: 700; line-height:1.4; margin-bottom: 5px;">ParSU Surpasses COA Target, Upholds Transparency and Good Governance</a>
                        <small style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">01 April 2026</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 1.5rem; font-size:1.1rem;">Quick Links</h5>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
                        <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem;">News Archive</a></li>
                        <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem;">Citizen's Charter</a></li>
                        <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem;">Annual Reports</a></li>
                        <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem;">Freedom of Information Manual</a></li>
                        <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem;">Job Opportunities</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 0.5rem; font-size:1.1rem;">Client Satisfaction Measurement -</h5>
                    <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 1.5rem; font-size:1.1rem;">Help us serve you better!</h5>
                    <div style="background: white; padding: 0.5rem; display: inline-block; border-radius: 12px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ParSU-Satisfaction-Survey" alt="QR Code" style="width: 140px; height: 140px;">
                    </div>
                </div>
            </div>
            
            <div style="border-top: 1px solid #eee; margin-top: 3rem; padding-top: 1.5rem;">
                <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0; text-align:center;">This site is maintained and managed by the ICTMO © {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top -->
<button class="scroll-top-btn" id="scrollTopBtn">
    <i class="fas fa-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize AOS
    AOS.init({ duration: 600, once: true, offset: 50 });

    // Hide loading screen
    window.addEventListener('load', () => {
        const ls = document.getElementById('loadingScreen');
        setTimeout(() => {
            ls.style.opacity = '0';
            setTimeout(() => ls.style.display = 'none', 400);
        }, 300);
    });

    /* Navbar scroll transition removed for solid locked behavior */

    // Scroll to top
    const scrollBtn = document.getElementById('scrollTopBtn');
    window.addEventListener('scroll', () => {
        scrollBtn.classList.toggle('show', window.scrollY > 300);
    });
    scrollBtn.style.background = '#FFD700'; /* Exact Yellow from image */
    scrollBtn.style.color = '#000000';
    scrollBtn.style.width = '45px';
    scrollBtn.style.height = '45px';
    scrollBtn.style.borderRadius = '10px';
    scrollBtn.style.display = 'flex';
    scrollBtn.style.alignItems = 'center';
    scrollBtn.style.justifyContent = 'center';
    scrollBtn.style.border = 'none';
    scrollBtn.style.boxShadow = '0 4px 10px rgba(0,0,0,0.3)';

    scrollBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Sidebar toggle (mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Sidebar submenu toggle
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        if (submenu) {
            submenu.classList.toggle('show');
        }
    }

    // Responsive sidebar/footer
    function handleResize() {
        const footer = document.querySelector('footer.with-sidebar');
        if (footer) {
            footer.style.marginLeft = window.innerWidth <= 991 ? '0' : 'var(--sidebar-width)';
        }
    }
    window.addEventListener('resize', handleResize);
    handleResize();
</script>

    @stack('modals')
    @stack('scripts')
</body>
</html>