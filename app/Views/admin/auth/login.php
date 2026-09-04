<?php
use Lilyweb\Core\Security;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= Security::e($pageTitle ?? 'Administrator Login — Lily Interiors CMS') ?></title>
    
    <!-- Complete Favicon & Mobile Touch Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-deep: #07090E;
            --surface-card: #0F1420;
            --surface-border: rgba(255, 255, 255, 0.09);
            --crimson: #C8102E;
            --crimson-glow: rgba(200, 16, 46, 0.4);
            --crimson-hover: #A00C24;
            --text-main: #FFFFFF;
            --text-muted: #94A3B8;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-deep);
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(200, 16, 46, 0.18) 0%, transparent 60%),
                radial-gradient(circle at 100% 100%, rgba(200, 16, 46, 0.08) 0%, transparent 50%);
            color: var(--text-main);
            font-family: var(--font-sans);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            background: var(--surface-card);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-lg);
            padding: 2.8rem 2.2rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 40px rgba(200, 16, 46, 0.12);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: linear-gradient(90deg, #8F0B20, #C8102E, #FF3355, #C8102E);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.2rem;
        }

        /* Large High-Resolution Official Logo Pod */
        .login-logo-container {
            display: inline-block;
            background: #FFFFFF;
            padding: 0.9rem 1.6rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.2);
            margin-bottom: 1.2rem;
            transition: transform 0.3s ease;
        }

        .login-logo-container:hover {
            transform: scale(1.02);
        }

        .login-logo-img {
            max-width: 280px;
            width: 100%;
            height: auto;
            max-height: 64px;
            object-fit: contain;
            display: block;
        }

        .login-subtitle {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.03em;
            line-height: 1.4;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.86rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            line-height: 1.4;
        }

        .alert-error {
            background: rgba(200, 16, 46, 0.15);
            border: 1px solid rgba(200, 16, 46, 0.4);
            color: #FF8596;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #86EFAC;
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #E2E8F0;
            margin-bottom: 0.5rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .input-wrap {
            position: relative;
        }

        .form-input {
            width: 100%;
            background: #090C12;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-md);
            padding: 0.9rem 1.1rem;
            color: #FFFFFF;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.25s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--crimson);
            background: #0E131E;
            box-shadow: 0 0 0 3px var(--crimson-glow);
        }

        .btn-submit {
            width: 100%;
            background: var(--crimson);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-md);
            padding: 1rem;
            font-size: 0.96rem;
            font-weight: 700;
            font-family: inherit;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 0.8rem;
            box-shadow: 0 4px 18px rgba(200, 16, 46, 0.45);
        }

        .btn-submit:hover {
            background: var(--crimson-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(200, 16, 46, 0.65);
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.82rem;
            color: #64748B;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 1.4rem;
        }

        .login-footer a {
            color: #94A3B8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: var(--crimson);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo-container">
                <img src="/assets/img/admin-logo.png" alt="Lily Interiors" class="login-logo-img">
            </div>
            <p class="login-subtitle">Executive CMS Portal • Authorized Administrator Access</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span><?= Security::e($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span><?= Security::e($success) ?></span>
            </div>
        <?php endif; ?>

        <form action="/admin/login" method="POST" autocomplete="off">
            <?= Security::csrfField() ?>

            <div class="form-group">
                <label for="login" class="form-label">Username or Email</label>
                <div class="input-wrap">
                    <input type="text" id="login" name="login" class="form-input" required autofocus placeholder="admin or email">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" class="form-input" required placeholder="••••••••••••">
                </div>
            </div>

            <button type="submit" class="btn-submit">Sign In to Dashboard &rarr;</button>
        </form>

        <div class="login-footer">
            <p><a href="/" target="_blank">&larr; Return to Public Website</a></p>
        </div>
    </div>
</body>
</html>
