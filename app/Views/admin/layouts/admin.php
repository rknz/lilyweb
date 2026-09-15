<?php
use Lilyweb\Core\Security;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Session;

$currentUser = Auth::user();
$currentPath = \Lilyweb\Core\Request::path();
$flashSuccess = Session::getFlash('success');
$flashError = Session::getFlash('error');
Session::flush();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= Security::e($pageTitle ?? 'CMS Administration') ?> — Lily Interiors</title>
    
    <!-- Favicon & Mobile Touch Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Zero-Flicker Admin Theme Pre-loader -->
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('lily_admin_theme') || 'dark';
                document.documentElement.setAttribute('data-theme', theme);
            } catch(e) {}
        })();
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Serif+Bengali:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ========================================================
           EXECUTIVE LUXURY DESIGN SYSTEM (SOURCE: dashboard ui dark mode.png)
           ======================================================== */
        :root {
            --crimson: #C8102E;
            --crimson-hover: #A00C24;
            --crimson-gradient: linear-gradient(135deg, #E61E40 0%, #C8102E 100%);
            --crimson-glow: rgba(200, 16, 46, 0.45);
            --font-sans: 'Plus Jakarta Sans', 'Noto Serif Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --sidebar-width: 270px;
            --header-height: 72px;
            --transition-smooth: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* DARK THEME (Recipe 1: Clean Midnight Slate & Crisp Modular Boxes) */
        [data-theme="dark"] {
            --bg-body: #0D111A;
            --bg-sidebar: #090D15;
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --sidebar-text: #94A3B8;
            --sidebar-text-hover: #FFFFFF;
            --sidebar-active-bg: var(--crimson-gradient);
            --sidebar-active-text: #FFFFFF;
            --sidebar-section-title: #64748B;
            --sidebar-brand-bg: #090D15;
            
            --bg-topbar: #0D111A;
            --bg-card: #151C28;
            --bg-card-alt: #1C2433;
            --bg-card-hover: #232D40;
            --bg-input: #101520;
            
            --border-color: rgba(255, 255, 255, 0.09);
            --border-subtle: rgba(255, 255, 255, 0.05);
            --border-focus: #C8102E;
            
            --text-heading: #FFFFFF;
            --text-main: #E2E8F0;
            --text-muted: #8E9BAE;
            --text-dim: #64748B;
            
            --table-header-bg: #1C2433;
            --table-header-text: #FFFFFF;
            --table-row-hover: #20293A;
            --table-border: rgba(255, 255, 255, 0.08);
            
            --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.45);
            --card-shadow-hover: 0 14px 40px rgba(0, 0, 0, 0.65);
            --topbar-shadow: 0 2px 14px rgba(0, 0, 0, 0.4);
            --badge-bg: rgba(255, 255, 255, 0.08);
            --badge-text: #F8FAFC;
        }

        /* LIGHT THEME (Recipe 1: Soft Executive Grey Canvas & Elevated White Cards) */
        [data-theme="light"] {
            --bg-body: #F4F6F9;
            --bg-sidebar: #FFFFFF;
            --sidebar-border: #E2E8F0;
            --sidebar-text: #475569;
            --sidebar-text-hover: #0F172A;
            --sidebar-active-bg: var(--crimson-gradient);
            --sidebar-active-text: #FFFFFF;
            --sidebar-section-title: #94A3B8;
            --sidebar-brand-bg: #FFFFFF;
            
            --bg-topbar: #FFFFFF;
            --bg-card: #FFFFFF;
            --bg-card-alt: #F8FAFC;
            --bg-card-hover: #F1F5F9;
            --bg-input: #FFFFFF;
            
            --border-color: #E2E8F0;
            --border-subtle: #EDF2F7;
            --border-focus: #C8102E;
            
            --text-heading: #0F172A;
            --text-main: #334155;
            --text-muted: #64748B;
            --text-dim: #94A3B8;
            
            --table-header-bg: #F8FAFC;
            --table-header-text: #0F172A;
            --table-row-hover: #F1F5F9;
            --table-border: #E2E8F0;
            
            --card-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 8px 24px -4px rgba(15, 23, 42, 0.04);
            --card-shadow-hover: 0 4px 6px -1px rgba(15, 23, 42, 0.08), 0 14px 30px -3px rgba(15, 23, 42, 0.06);
            --topbar-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            --badge-bg: #E2E8F0;
            --badge-text: #0F172A;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: var(--font-sans);
            font-size: 15px;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            transition: background-color 0.2s ease, color 0.2s ease;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            width: 100%;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
            transition: var(--transition-smooth);
        }

        .admin-sidebar::-webkit-scrollbar,
        .sidebar-nav::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .sidebar-brand {
            height: var(--header-height);
            min-height: var(--header-height);
            max-height: var(--header-height);
            box-sizing: border-box;
            padding: 0 1.4rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none;
            background: var(--sidebar-brand-bg);
            position: relative;
        }

        .brand-logo-full {
            max-width: 100%;
            height: auto;
            max-height: 44px;
            object-fit: contain;
            display: block;
        }

        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 18px;
            right: 14px;
            background: #0B0F19;
            color: #FFFFFF;
            border: 1px solid #1E293B;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--sidebar-section-title);
            padding: 0.95rem 0.75rem 0.35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.62rem 0.85rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            transition: var(--transition-smooth);
            position: relative;
        }

        .nav-item:hover {
            color: var(--sidebar-text-hover);
            background: var(--bg-card-hover);
        }

        .nav-item.is-active {
            background: var(--sidebar-active-bg) !important;
            color: var(--sidebar-active-text) !important;
            font-weight: 700;
            box-shadow: 0 4px 16px var(--crimson-glow);
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            stroke-width: 2.2;
        }

        .nav-item .nav-badge {
            margin-left: auto;
            background: var(--crimson);
            color: #FFFFFF;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.12rem 0.48rem;
            border-radius: var(--radius-full);
            line-height: 1.2;
        }

        .nav-item.is-active .nav-badge {
            background: #FFFFFF;
            color: var(--crimson);
        }

        .nav-item-logout {
            color: #EF4444 !important;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            transition: var(--transition-smooth);
            cursor: pointer;
        }

        .nav-item-logout:hover {
            background: #EF4444 !important;
            color: #FFFFFF !important;
            border-color: #EF4444 !important;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4);
            transform: translateX(3px);
        }

        /* Topbar */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
            background-color: var(--bg-body);
        }

        .admin-topbar {
            height: var(--header-height);
            min-height: var(--header-height);
            max-height: var(--header-height);
            box-sizing: border-box;
            background: var(--bg-topbar);
            border-bottom: 1px solid var(--border-color);
            padding: 0 2.4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: var(--topbar-shadow);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1.15rem;
        }

        .topbar-title h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .topbar-title p {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 0.1rem;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: 1.5px solid var(--border-color);
            padding: 0.5rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            color: var(--text-heading);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .status-pill-online {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.42rem 0.95rem;
            background: rgba(34, 197, 94, 0.12);
            color: #22C55E;
            border: 1px solid rgba(34, 197, 94, 0.28);
            border-radius: var(--radius-full);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .status-dot-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22C55E;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.35);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .theme-toggle-btn {
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            padding: 0.42rem 0.95rem;
            border-radius: var(--radius-full);
            cursor: pointer;
            color: var(--text-heading);
            font-size: 0.84rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-smooth);
        }

        .theme-toggle-btn:hover {
            border-color: var(--crimson);
            color: var(--crimson);
        }

        [data-theme="light"] .theme-icon-moon { display: none; }
        [data-theme="light"] .theme-icon-sun { display: inline-block; }
        [data-theme="dark"] .theme-icon-moon { display: inline-block; }
        [data-theme="dark"] .theme-icon-sun { display: none; }

        .btn-notification-bell {
            position: relative;
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: var(--text-heading);
            cursor: pointer;
            transition: var(--transition-smooth);
            text-decoration: none;
        }

        .btn-notification-bell:hover {
            border-color: var(--crimson);
            color: var(--crimson);
        }

        .bell-badge-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--crimson);
            color: #FFFFFF;
            font-size: 0.68rem;
            font-weight: 800;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            border: 2px solid var(--bg-topbar);
        }

        .topbar-user-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.95rem 0.35rem 0.45rem;
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-full);
            text-decoration: none;
            color: var(--text-heading);
        }

        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--crimson);
            color: #FFFFFF;
            font-weight: 800;
            font-size: 0.82rem;
            display: grid;
            place-items: center;
        }

        .topbar-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .topbar-user-name {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .topbar-user-role {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        /* ========================================================
           HIGHLIGHTED BACK BUTTON & GLOBAL ATOMS
           ======================================================== */
        .btn-back-highlight, .btn-back, a.btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: rgba(200, 16, 46, 0.12);
            border: 1.5px solid rgba(200, 16, 46, 0.35);
            color: #FFFFFF !important;
            font-size: 0.88rem;
            font-weight: 800;
            padding: 0.55rem 1.25rem;
            border-radius: var(--radius-full);
            text-decoration: none;
            margin-top: 0.5rem;
            margin-bottom: 1.85rem;
            box-shadow: 0 4px 14px rgba(200, 16, 46, 0.25);
            transition: var(--transition-smooth);
        }

        [data-theme="light"] .btn-back-highlight, [data-theme="light"] .btn-back {
            background: rgba(200, 16, 46, 0.08);
            border-color: rgba(200, 16, 46, 0.3);
            color: var(--crimson) !important;
        }

        .btn-back-highlight:hover, .btn-back:hover {
            background: var(--crimson-gradient);
            color: #FFFFFF !important;
            border-color: var(--crimson);
            box-shadow: 0 6px 20px var(--crimson-glow);
            transform: translateX(-4px);
        }

        .admin-content {
            padding: 1.85rem 2.4rem;
            max-width: 100%;
            width: 100%;
            flex: 1;
        }

        /* Universal Luxury Cards & Panels */
        .card, .admin-card, .panel, .admin-panel, .box, .admin-box, .content-card, .table-card {
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-xl);
            padding: 1.65rem 1.65rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.65rem;
            transition: var(--transition-smooth);
        }

        .card:hover, .admin-card:hover {
            border-color: rgba(200, 16, 46, 0.3);
            box-shadow: var(--card-shadow-hover);
        }

        .card-header, .admin-card-header, .page-header, .card-header-flex, .page-actions-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1.15rem;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-title, .page-title {
            font-size: 1.22rem;
            font-weight: 800;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            letter-spacing: -0.01em;
        }

        .card-subtitle, .page-subtitle {
            font-size: 0.84rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Floating Page Action Bar (sticky below the topbar) — shared by all pages */
        .page-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.65rem;
            position: sticky;
            top: calc(var(--header-height) + 1rem);
            z-index: 80;
            margin: 0 0 1.4rem;
        }

        @media (max-width: 640px) {
            .page-actions {
                top: calc(var(--header-height) + 0.5rem);
                flex-wrap: wrap;
            }
        }

        /* Sticky Upload Card (Media Library) — stays fixed below the topbar while scrolling */
        .admin-card.card-specs.sticky-upload {
            position: sticky;
            top: calc(var(--header-height) + 1rem);
            z-index: 85;
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            box-shadow: var(--card-shadow-hover);
        }

        @media (max-width: 640px) {
            .admin-card.card-specs.sticky-upload {
                top: calc(var(--header-height) + 0.5rem);
            }
        }

        /* Universal Tables */
        table, .table, .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin: 0.85rem 0;
            font-size: 0.88rem;
        }

        table thead th, .table thead th, .admin-table thead th {
            background: var(--table-header-bg);
            color: var(--table-header-text);
            font-weight: 800;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.95rem 1rem;
            border-bottom: 1.5px solid var(--border-color);
        }

        table tbody td, .table tbody td, .admin-table tbody td {
            padding: 1rem 1rem;
            border-bottom: 1px solid var(--table-border);
            color: var(--text-main);
            vertical-align: middle;
        }

        table tbody tr:hover, .table tbody tr:hover, .admin-table tbody tr:hover {
            background: var(--table-row-hover);
        }

        /* Forms, Inputs & Textareas */
        .form-group, .form-row {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            margin-bottom: 1.25rem;
        }

        .form-label, label {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .form-help {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="number"], 
        input[type="url"], input[type="tel"], input[type="date"], select, textarea, .form-control, .form-input, .form-textarea {
            width: 100%;
            background: var(--bg-input);
            border: 1.5px solid var(--border-color);
            color: var(--text-heading);
            padding: 0.7rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: var(--transition-smooth);
        }

        input:focus, select:focus, textarea:focus, .form-control:focus, .form-input:focus, .form-textarea:focus {
            border-color: var(--crimson);
            box-shadow: 0 0 0 3.5px rgba(200, 16, 46, 0.18);
        }

        /* Buttons & Actions */
        .btn, .btn-primary, .btn-secondary, .btn-danger, .btn-success, .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            font-weight: 700;
            font-size: 0.86rem;
            padding: 0.62rem 1.25rem;
            border-radius: var(--radius-md);
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: var(--transition-smooth);
        }

        .btn-primary {
            background: var(--crimson-gradient);
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px var(--crimson-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(200, 16, 46, 0.6);
        }

        .btn-secondary {
            background: var(--bg-card-alt);
            color: var(--text-heading) !important;
            border: 1.5px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--crimson);
            color: var(--crimson) !important;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: #EF4444;
            color: #FFFFFF !important;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.78rem;
        }

        /* Status Badges */
        .badge, .badge-status, .badge-pill-blue, .badge-pill-crimson {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.22rem 0.65rem;
            border-radius: var(--radius-full);
            font-size: 0.74rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .badge-success, .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: #22C55E;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-warning, .badge-draft, .badge-pending {
            background: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-danger, .badge-inactive, .badge-pill-crimson {
            background: rgba(200, 16, 46, 0.15);
            color: #E61E40;
            border: 1px solid rgba(200, 16, 46, 0.3);
        }

        .badge-pill-blue {
            background: rgba(59, 130, 246, 0.15);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        /* ========================================================
           INTERACTIVE IMAGE UPLOADER & MEDIA PICKER COMPONENT
           ======================================================== */
        .media-uploader-box {
            background: var(--bg-card-alt);
            border: 1.5px dashed var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            transition: var(--transition-smooth);
            margin-top: 0.4rem;
        }

        .media-uploader-box:hover {
            border-color: var(--crimson);
        }

        .media-uploader-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-uploader-upload {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--crimson-gradient);
            color: #FFFFFF !important;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.55rem 1rem;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--crimson-glow);
            transition: var(--transition-smooth);
        }

        .btn-uploader-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(200, 16, 46, 0.6);
        }

        .btn-uploader-picker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            color: var(--text-heading);
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.55rem 1rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .btn-uploader-picker:hover {
            border-color: var(--crimson);
            color: var(--crimson);
        }

        .media-preview-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.95rem;
            padding-top: 0.85rem;
            border-top: 1px solid var(--border-subtle);
        }

        .media-preview-thumb {
            width: 72px;
            height: 72px;
            border-radius: var(--radius-md);
            object-fit: cover;
            border: 1.5px solid var(--border-color);
            background: #000;
        }

        .media-preview-meta {
            flex: 1;
            min-width: 0;
        }

        .media-preview-url {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-heading);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-media-remove {
            color: #EF4444;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 0.25rem 0.65rem;
            border-radius: var(--radius-sm);
            font-size: 0.74rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .btn-media-remove:hover {
            background: #EF4444;
            color: #FFFFFF;
        }

        /* Media Picker Modal */
        .picker-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .picker-modal-dialog {
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-xl);
            width: 100%;
            max-width: 780px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: var(--card-shadow-hover);
            overflow: hidden;
        }

        .picker-modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .picker-modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex: 1;
        }

        .picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 1rem;
        }

        .picker-item {
            background: var(--bg-card-alt);
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition-smooth);
            position: relative;
        }

        .picker-item:hover {
            border-color: var(--crimson);
            transform: scale(1.03);
            box-shadow: 0 4px 14px var(--crimson-glow);
        }

        .picker-item img {
            width: 100%;
            height: 95px;
            object-fit: cover;
            display: block;
        }

        .picker-item-name {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-heading);
            padding: 0.4rem 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background: var(--bg-card-alt);
        }

        /* Gallery Manager & Multi-picker CSS */
        .picker-item.is-selected {
            border-color: var(--crimson) !important;
            box-shadow: 0 0 0 2px var(--crimson), 0 6px 16px var(--crimson-glow) !important;
        }
        .picker-item.is-selected::after {
            content: '✓';
            position: absolute;
            top: 6px;
            right: 6px;
            background: var(--crimson);
            color: #FFFFFF;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        }
        .picker-modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-card-alt);
        }
        .gallery-manager-box {
            background: var(--bg-input);
            border: 1.5px dashed var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: var(--transition-smooth);
        }
        .gallery-manager-box:hover {
            border-color: var(--crimson);
        }
        .gallery-grid-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 1rem;
            margin-top: 1.25rem;
        }
        .gallery-item-card {
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            position: relative;
            box-shadow: var(--card-shadow);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
        }
        .gallery-item-card:hover {
            border-color: var(--crimson);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.3);
        }
        .gallery-item-thumb {
            width: 100%;
            height: 95px;
            object-fit: cover;
            background: #000;
            display: block;
        }
        .gallery-item-info {
            padding: 0.4rem 0.5rem;
            font-size: 0.72rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-card-alt);
        }
        .gallery-item-idx {
            font-weight: 800;
            color: var(--crimson);
        }
        .gallery-item-del-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(239, 68, 68, 0.9);
            color: #FFFFFF;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
            transition: var(--transition-smooth);
        }
        .gallery-item-del-btn:hover {
            background: #DC2626;
            transform: scale(1.15);
        }
        .gallery-counter-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(200, 16, 46, 0.1);
            color: var(--crimson);
            border: 1px solid rgba(200, 16, 46, 0.25);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <!-- Mobile Backdrop Overlay -->
    <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

    <!-- Media Picker Modal Component -->
    <div id="picker-modal" class="picker-modal-backdrop">
        <div class="picker-modal-dialog">
            <div class="picker-modal-header">
                <h3 id="picker-modal-title" style="font-size: 1.15rem; font-weight: 800; color: var(--text-heading);">📁 Select Image from Media Library</h3>
                <button type="button" id="picker-close-btn" style="background: none; border: none; font-size: 1.4rem; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 0.85rem 1.5rem 0;">
                <input type="text" id="picker-search" placeholder="Search by filename or title..." class="form-input" style="padding: 0.5rem 0.85rem; font-size: 0.84rem;">
            </div>
            <div class="picker-modal-body">
                <div id="picker-grid" class="picker-grid">
                    <p style="color: var(--text-muted); font-size: 0.86rem;">Loading media library images...</p>
                </div>
            </div>
            <div id="picker-modal-footer" class="picker-modal-footer" style="display: none;">
                <span id="picker-selection-count" style="font-size: 0.85rem; font-weight: 700; color: var(--text-heading);">0 selected</span>
                <button type="button" id="picker-confirm-btn" class="btn-primary" style="padding: 0.55rem 1.4rem; font-size: 0.85rem;">Add Selected Images →</button>
            </div>
        </div>
    </div>

    <!-- Sidebar Navigation (Streamlined Clean Architecture) -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <a href="/admin" class="sidebar-brand">
            <img src="/assets/img/admin-logo.png" alt="Lily Interiors" class="brand-logo-full">
            <button type="button" id="sidebar-close-btn" class="sidebar-close-btn" aria-label="Close menu">&times;</button>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section-title">HOME &amp; CORE PAGES</div>
            
            <a href="/admin" class="nav-item <?= ($currentPath === '/admin' || $currentPath === '/admin/dashboard') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Dashboard Overview</span>
            </a>

            <a href="/admin/homepage" class="nav-item <?= str_starts_with($currentPath, '/admin/homepage') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>Homepage (All Sections)</span>
            </a>

            <div class="nav-section-title">PORTFOLIO &amp; MEDIA</div>

            <a href="/admin/projects" class="nav-item <?= str_starts_with($currentPath, '/admin/projects') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Projects Portfolio</span>
            </a>

            <a href="/admin/categories" class="nav-item <?= str_starts_with($currentPath, '/admin/categories') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span>Project Categories</span>
            </a>

            <a href="/admin/media" class="nav-item <?= str_starts_with($currentPath, '/admin/media') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>Media Library</span>
            </a>

            <div class="nav-section-title">LEADS &amp; GLOBAL CONTROL</div>

            <a href="/admin/contacts" class="nav-item <?= (str_starts_with($currentPath, '/admin/contacts') || str_starts_with($currentPath, '/admin/inquiries')) ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>Contact Inquiries</span>
                <span class="nav-badge">1</span>
            </a>

            <a href="/admin/settings" class="nav-item <?= str_starts_with($currentPath, '/admin/settings') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Site Settings</span>
            </a>

            <a href="/admin/seo" class="nav-item <?= str_starts_with($currentPath, '/admin/seo') ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Google SEO &amp; AI</span>
            </a>

            <a href="/admin/backup" class="nav-item <?= (str_starts_with($currentPath, '/admin/backup') || str_starts_with($currentPath, '/admin/backups')) ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Backup &amp; Restore</span>
            </a>

            <a href="/admin/docs" class="nav-item <?= (str_starts_with($currentPath, '/admin/docs') || str_starts_with($currentPath, '/admin/guide')) ? 'is-active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                <span>ব্যবহার নির্দেশিকা (User Guide)</span>
            </a>

            <div class="nav-section-title">ACCOUNT &amp; SESSION</div>

            <a href="/admin/logout" class="nav-item nav-item-logout" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span>Sign Out</span>
            </a>
        </nav>
    </aside>

    <!-- Hidden Logout Form -->
    <form id="admin-logout-form" action="/admin/logout" method="POST" style="display: none;">
        <?= Security::csrfField() ?>
    </form>

    <!-- Main Workspace -->
    <main class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <button type="button" id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="Toggle navigation menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="topbar-title">
                    <h1><?= Security::e($pageHeading ?? 'Dashboard Overview') ?></h1>
                    <p>Welcome back, Lily Interiors Owner 👋</p>
                </div>
            </div>

            <div class="topbar-actions">
                <div class="status-pill-online" title="PHP Engine & MySQL Database Active">
                    <span class="status-dot-pulse"></span>
                    <span>System Online</span>
                </div>

                <button type="button" id="theme-toggle-btn" class="theme-toggle-btn" title="Switch Theme (Dark / Light)">
                    <span class="theme-icon theme-icon-sun">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    </span>
                    <span class="theme-icon theme-icon-moon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    </span>
                    <span id="theme-text">Light Mode</span>
                </button>

                <a href="/admin/contacts" class="btn-notification-bell" title="Notifications">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="bell-badge-count">3</span>
                </a>

                <!-- Topbar User Pill with Quick Logout -->
                <div style="position: relative;">
                    <button type="button" id="user-menu-btn" class="topbar-user-pill" style="cursor: pointer; background: var(--bg-card);">
                        <div class="topbar-user-avatar">L</div>
                        <div class="topbar-user-info">
                            <span class="topbar-user-name">Lily Interiors Owner</span>
                            <span class="topbar-user-role">Administrator</span>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: 0.2rem; color: var(--text-muted);"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="user-dropdown-menu" style="display: none; position: absolute; right: 0; top: calc(100% + 8px); width: 220px; background: var(--bg-card); border: 1.5px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--card-shadow-hover); padding: 0.5rem; z-index: 100;">
                        <div style="padding: 0.65rem 0.85rem; border-bottom: 1px solid var(--border-color); margin-bottom: 0.35rem;">
                            <div style="font-size: 0.84rem; font-weight: 800; color: var(--text-heading);">Lily Interiors Owner</div>
                            <div style="font-size: 0.74rem; color: var(--text-muted);">admin@lilyinteriorsbd.com</div>
                        </div>
                        <a href="/admin/settings" class="nav-item" style="padding: 0.45rem 0.65rem; font-size: 0.82rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <span>Site Settings</span>
                        </a>
                        <a href="/admin/logout" class="nav-item nav-item-logout" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" style="padding: 0.45rem 0.65rem; font-size: 0.82rem; margin-top: 0.35rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="admin-content">
            <?php if (!empty($flashSuccess)): ?>
                <div style="background: rgba(34, 197, 94, 0.12); border: 1.5px solid rgba(34, 197, 94, 0.3); color: #22C55E; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 700;">
                    ✓ <?= Security::e($flashSuccess) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($flashError)): ?>
                <div style="background: rgba(239, 68, 68, 0.12); border: 1.5px solid rgba(239, 68, 68, 0.3); color: #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 700;">
                    ⚠ <?= Security::e($flashError) ?>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>

        <footer class="admin-footer-strip">
            <div>&copy; 2026 Lily Interiors. All rights reserved.</div>
            <div>Designed with <span style="color: var(--crimson);">&hearts;</span> for perfection</div>
        </footer>
    </main>

    <!-- Theme & Universal Media Picker Scripts -->
    <script>
        (function() {
            var themeBtn = document.getElementById('theme-toggle-btn');
            var themeText = document.getElementById('theme-text');
            var mobileToggle = document.getElementById('mobile-menu-toggle');
            var sidebarClose = document.getElementById('sidebar-close-btn');
            var backdrop = document.getElementById('sidebar-backdrop');

            function updateThemeText(theme) {
                if (themeText) {
                    themeText.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
                }
            }

            var currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            updateThemeText(currentTheme);

            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    var now = document.documentElement.getAttribute('data-theme') || 'dark';
                    var next = now === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    try {
                        localStorage.setItem('lily_admin_theme', next);
                    } catch(e) {}
                    updateThemeText(next);
                });
            }

            function openSidebar() { document.body.classList.add('sidebar-open'); }
            function closeSidebar() { document.body.classList.remove('sidebar-open'); }

            if (mobileToggle) mobileToggle.addEventListener('click', openSidebar);
            if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // ========================================================
            // UNIVERSAL MEDIA PICKER (SINGLE & MULTI-SELECT)
            // ========================================================
            var activeTargetInput = null;
            var activePreviewContainer = null;
            var activeMultiCallback = null;
            var isMultiMode = false;
            var selectedMultiUrls = [];

            var pickerModal = document.getElementById('picker-modal');
            var pickerTitle = document.getElementById('picker-modal-title');
            var pickerGrid = document.getElementById('picker-grid');
            var pickerCloseBtn = document.getElementById('picker-close-btn');
            var pickerSearch = document.getElementById('picker-search');
            var pickerFooter = document.getElementById('picker-modal-footer');
            var pickerCount = document.getElementById('picker-selection-count');
            var pickerConfirmBtn = document.getElementById('picker-confirm-btn');
            var cachedMedia = [];

            function loadMediaList() {
                if (!pickerGrid) return;
                pickerGrid.innerHTML = '<p style="color: var(--text-muted); font-size: 0.86rem;">Loading images...</p>';
                fetch('/admin/media/picker-list')
                    .then(function(res) { return res.json(); })
                    .then(function(res) {
                        if (res.success && Array.isArray(res.data)) {
                            cachedMedia = res.data;
                            renderMediaGrid(cachedMedia);
                        } else {
                            pickerGrid.innerHTML = '<p style="color: #EF4444;">Could not load media library.</p>';
                        }
                    })
                    .catch(function() {
                        pickerGrid.innerHTML = '<p style="color: #EF4444;">Error fetching media items.</p>';
                    });
            }

            function updateMultiCount() {
                if (pickerCount) {
                    pickerCount.textContent = selectedMultiUrls.length + ' image(s) selected';
                }
                if (pickerConfirmBtn) {
                    pickerConfirmBtn.disabled = selectedMultiUrls.length === 0;
                }
            }

            function renderMediaGrid(items) {
                if (!pickerGrid) return;
                if (!items.length) {
                    pickerGrid.innerHTML = '<p style="color: var(--text-muted);">No images found.</p>';
                    return;
                }
                pickerGrid.innerHTML = items.map(function(m) {
                    var isSelected = isMultiMode && selectedMultiUrls.indexOf(m.storage_path) !== -1;
                    return '<div class="picker-item ' + (isSelected ? 'is-selected' : '') + '" data-url="' + m.storage_path + '" data-name="' + m.filename + '">' +
                        '<img src="' + m.storage_path + '" alt="' + (m.alt_en || '') + '">' +
                        '<div class="picker-item-name">' + m.filename + '</div>' +
                    '</div>';
                }).join('');

                var pickerItems = pickerGrid.querySelectorAll('.picker-item');
                pickerItems.forEach(function(item) {
                    item.addEventListener('click', function() {
                        var url = this.getAttribute('data-url');
                        if (isMultiMode) {
                            var idx = selectedMultiUrls.indexOf(url);
                            if (idx === -1) {
                                selectedMultiUrls.push(url);
                                this.classList.add('is-selected');
                            } else {
                                selectedMultiUrls.splice(idx, 1);
                                this.classList.remove('is-selected');
                            }
                            updateMultiCount();
                        } else {
                            if (activeTargetInput) {
                                activeTargetInput.value = url;
                                if (activePreviewContainer) {
                                    activePreviewContainer.innerHTML = '<img src="' + url + '" class="media-preview-thumb"><div class="media-preview-meta"><div class="media-preview-url">' + url + '</div></div><button type="button" class="btn-media-remove">Remove</button>';
                                    bindRemoveBtn(activePreviewContainer, activeTargetInput);
                                }
                            }
                            closePicker();
                        }
                    });
                });
            }

            function openPicker(inputElem, previewElem) {
                isMultiMode = false;
                activeMultiCallback = null;
                selectedMultiUrls = [];
                activeTargetInput = inputElem;
                activePreviewContainer = previewElem;
                if (pickerTitle) pickerTitle.textContent = '📁 Select Image from Media Library';
                if (pickerFooter) pickerFooter.style.display = 'none';

                if (pickerModal) {
                    pickerModal.style.display = 'flex';
                    if (!cachedMedia.length) {
                        loadMediaList();
                    } else {
                        renderMediaGrid(cachedMedia);
                    }
                }
            }

            window.openMultiPicker = function(callback) {
                isMultiMode = true;
                activeTargetInput = null;
                activePreviewContainer = null;
                activeMultiCallback = callback;
                selectedMultiUrls = [];
                updateMultiCount();

                if (pickerTitle) pickerTitle.textContent = '📁 Select Multiple Images for Gallery';
                if (pickerFooter) pickerFooter.style.display = 'flex';

                if (pickerModal) {
                    pickerModal.style.display = 'flex';
                    if (!cachedMedia.length) {
                        loadMediaList();
                    } else {
                        renderMediaGrid(cachedMedia);
                    }
                }
            };

            function closePicker() {
                if (pickerModal) pickerModal.style.display = 'none';
                isMultiMode = false;
                selectedMultiUrls = [];
            }

            if (pickerConfirmBtn) {
                pickerConfirmBtn.addEventListener('click', function() {
                    if (isMultiMode && typeof activeMultiCallback === 'function') {
                        activeMultiCallback(selectedMultiUrls.slice());
                    }
                    closePicker();
                });
            }

            if (pickerCloseBtn) pickerCloseBtn.addEventListener('click', closePicker);
            if (pickerModal) {
                pickerModal.addEventListener('click', function(e) {
                    if (e.target === pickerModal) closePicker();
                });
            }

            if (pickerSearch) {
                pickerSearch.addEventListener('input', function() {
                    var q = this.value.toLowerCase().trim();
                    var filtered = cachedMedia.filter(function(m) {
                        return (m.filename && m.filename.toLowerCase().indexOf(q) !== -1) ||
                               (m.alt_en && m.alt_en.toLowerCase().indexOf(q) !== -1);
                    });
                    renderMediaGrid(filtered);
                });
            }

            function bindRemoveBtn(container, input) {
                var btn = container.querySelector('.btn-media-remove');
                if (btn) {
                    btn.addEventListener('click', function() {
                        input.value = '';
                        container.innerHTML = '<p style="font-size: 0.78rem; color: var(--text-muted);">No image selected</p>';
                    });
                }
            }

            // Initialize all .media-uploader-box components (Single Uploaders)
            window.initMediaUploaders = function() {
                document.querySelectorAll('.media-uploader-box').forEach(function(box) {
                    var inputName = box.getAttribute('data-input-name');
                    var targetInput = box.querySelector('input[name="' + inputName + '"]');
                    var uploadBtn = box.querySelector('.btn-uploader-upload');
                    var pickerBtn = box.querySelector('.btn-uploader-picker');
                    var fileInput = box.querySelector('.hidden-file-input');
                    var previewContainer = box.querySelector('.media-preview-container');

                    if (pickerBtn && !pickerBtn._bound) {
                        pickerBtn._bound = true;
                        pickerBtn.addEventListener('click', function() {
                            openPicker(targetInput, previewContainer);
                        });
                    }

                    if (uploadBtn && fileInput && !uploadBtn._bound) {
                        uploadBtn._bound = true;
                        uploadBtn.addEventListener('click', function() {
                            fileInput.click();
                        });

                        fileInput.addEventListener('change', function() {
                            if (!this.files || !this.files[0]) return;
                            var file = this.files[0];
                            var formData = new FormData();
                            formData.append('file', file);

                            uploadBtn.innerHTML = '⏳ Uploading...';
                            uploadBtn.disabled = true;

                            fetch('/admin/media/quick-upload', {
                                method: 'POST',
                                body: formData
                            })
                            .then(function(r) { return r.json(); })
                            .then(function(res) {
                                uploadBtn.innerHTML = '📤 Upload Photo Directly';
                                uploadBtn.disabled = false;
                                if (res.success && res.url) {
                                    targetInput.value = res.url;
                                    previewContainer.innerHTML = '<img src="' + res.url + '" class="media-preview-thumb"><div class="media-preview-meta"><div class="media-preview-url">' + res.url + '</div><span class="badge badge-success" style="font-size: 0.68rem; color: #15803D;">✓ Uploaded Now</span></div><button type="button" class="btn-media-remove">Remove</button>';
                                    bindRemoveBtn(previewContainer, targetInput);
                                } else {
                                    alert(res.message || 'Upload failed');
                                }
                            })
                            .catch(function() {
                                uploadBtn.innerHTML = '📤 Upload Photo Directly';
                                uploadBtn.disabled = false;
                                alert('Error during file upload.');
                            });
                        });
                    }

                    if (previewContainer && targetInput) {
                        bindRemoveBtn(previewContainer, targetInput);
                    }
                });
            };

            // ========================================================
            // PROJECT MULTI-IMAGE GALLERY MANAGER
            // ========================================================
            window.initGalleryManagers = function() {
                var galleryBoxes = document.querySelectorAll('.gallery-manager-box');
                galleryBoxes.forEach(function(box) {
                    if (box._initialized) return;
                    box._initialized = true;

                    var jsonInput = box.querySelector('input[type="hidden"].gallery-json-data');
                    var gridContainer = box.querySelector('.gallery-grid-preview');
                    var countBadge = box.querySelector('.gallery-count-num');
                    var uploadBtn = box.querySelector('.btn-gallery-upload');
                    var pickerBtn = box.querySelector('.btn-gallery-picker');
                    var fileInput = box.querySelector('.gallery-file-input');

                    var galleryImages = [];
                    try {
                        var raw = jsonInput ? jsonInput.value : '[]';
                        galleryImages = JSON.parse(raw) || [];
                        if (!Array.isArray(galleryImages)) galleryImages = [];
                    } catch(e) {
                        galleryImages = [];
                    }

                    function syncData() {
                        if (jsonInput) {
                            jsonInput.value = JSON.stringify(galleryImages);
                        }
                        if (countBadge) {
                            countBadge.textContent = galleryImages.length;
                        }
                        renderGrid();
                    }

                    function renderGrid() {
                        if (!gridContainer) return;
                        if (galleryImages.length === 0) {
                            gridContainer.innerHTML = '<div style="grid-column: 1 / -1; padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.88rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px dashed var(--border-color);">' +
                                '📸 No gallery photos added yet. Click <strong>Upload Multiple Photos</strong> or <strong>Choose from Media Library</strong> above.' +
                            '</div>';
                            return;
                        }

                        gridContainer.innerHTML = galleryImages.map(function(url, idx) {
                            var filename = url.split('/').pop();
                            return '<div class="gallery-item-card" data-idx="' + idx + '">' +
                                '<button type="button" class="gallery-item-del-btn" data-del-idx="' + idx + '" title="Remove from gallery">&times;</button>' +
                                '<img src="' + url + '" class="gallery-item-thumb" alt="Gallery Photo ' + (idx + 1) + '">' +
                                '<div class="gallery-item-info">' +
                                    '<span class="gallery-item-idx">#' + (idx + 1) + '</span>' +
                                    '<span title="' + url + '">' + filename + '</span>' +
                                '</div>' +
                            '</div>';
                        }).join('');

                        // Bind delete buttons
                        gridContainer.querySelectorAll('.gallery-item-del-btn').forEach(function(btn) {
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                var delIdx = parseInt(this.getAttribute('data-del-idx'), 10);
                                if (!isNaN(delIdx) && delIdx >= 0 && delIdx < galleryImages.length) {
                                    galleryImages.splice(delIdx, 1);
                                    syncData();
                                }
                            });
                        });
                    }

                    // Multi Media Picker Click
                    if (pickerBtn) {
                        pickerBtn.addEventListener('click', function() {
                            window.openMultiPicker(function(selectedUrls) {
                                if (Array.isArray(selectedUrls) && selectedUrls.length > 0) {
                                    selectedUrls.forEach(function(url) {
                                        if (galleryImages.indexOf(url) === -1) {
                                            galleryImages.push(url);
                                        }
                                    });
                                    syncData();
                                }
                            });
                        });
                    }

                    // Direct Multi-File Upload Click
                    if (uploadBtn && fileInput) {
                        uploadBtn.addEventListener('click', function() {
                            fileInput.click();
                        });

                        fileInput.addEventListener('change', function() {
                            if (!this.files || this.files.length === 0) return;
                            var formData = new FormData();
                            for (var i = 0; i < this.files.length; i++) {
                                formData.append('files[]', this.files[i]);
                            }

                            var origText = uploadBtn.innerHTML;
                            uploadBtn.innerHTML = '⏳ Uploading ' + this.files.length + ' photo(s)...';
                            uploadBtn.disabled = true;

                            fetch('/admin/media/quick-upload', {
                                method: 'POST',
                                body: formData
                            })
                            .then(function(r) { return r.json(); })
                            .then(function(res) {
                                uploadBtn.innerHTML = origText;
                                uploadBtn.disabled = false;
                                if (res.success && Array.isArray(res.urls)) {
                                    res.urls.forEach(function(u) {
                                        if (galleryImages.indexOf(u) === -1) {
                                            galleryImages.push(u);
                                        }
                                    });
                                    syncData();
                                } else if (res.success && res.url) {
                                    if (galleryImages.indexOf(res.url) === -1) {
                                        galleryImages.push(res.url);
                                    }
                                    syncData();
                                } else {
                                    alert(res.message || 'Upload failed');
                                }
                            })
                            .catch(function() {
                                uploadBtn.innerHTML = origText;
                                uploadBtn.disabled = false;
                                alert('Error uploading gallery photos.');
                            });

                            // reset file input
                            fileInput.value = '';
                        });
                    }

                    // Initial render
                    syncData();
                });
            };

            // User Profile Menu Dropdown
            var userMenuBtn = document.getElementById('user-menu-btn');
            var userDropdownMenu = document.getElementById('user-dropdown-menu');
            if (userMenuBtn && userDropdownMenu) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isShown = userDropdownMenu.style.display === 'block';
                    userDropdownMenu.style.display = isShown ? 'none' : 'block';
                });

                document.addEventListener('click', function(e) {
                    if (!userDropdownMenu.contains(e.target) && e.target !== userMenuBtn) {
                        userDropdownMenu.style.display = 'none';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                window.initMediaUploaders();
                window.initGalleryManagers();
            });
            window.initMediaUploaders();
            window.initGalleryManagers();
        })();
    </script>
</body>
</html>
