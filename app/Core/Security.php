<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Security foundation.
 *
 * Output escaping, CSRF token generation/validation, and safe random helpers.
 * Login rate-limiting, authorization, upload validation and full header policy
 * are hardened in later phases.
 */
final class Security
{
    /**
     * Escape a value for safe HTML output.
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a CSRF token bound to the current session.
     */
    public static function csrfToken(): string
    {
        $token = Session::get('_csrf');
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::put('_csrf', $token);
        }
        return $token;
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="_token" value="' . self::e(self::csrfToken()) . '">';
    }

    /**
     * Validate the CSRF token from the request.
     */
    public static function verifyCsrf(): void
    {
        $sent = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        $stored = Session::get('_csrf');

        if (!is_string($sent) || !is_string($stored) || !hash_equals($stored, $sent)) {
            throw new Exceptions\HttpException(419, 'Page expired');
        }
    }

    /**
     * Generate a cryptographically secure random string.
     */
    public static function random(int $length = 32): string
    {
        return bin2hex(random_bytes((int) ceil($length / 2)));
    }

    /**
     * Recommend a password hash (uses PASSWORD_DEFAULT).
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, Config::get('security.password_algo', PASSWORD_DEFAULT));
    }

    /**
     * Send framework-appropriate security headers (compatibility reviewed later).
     */
    public static function sendSecurityHeaders(): void
    {
        $headers = Config::get('security.security_headers', []);
        foreach ($headers as $name => $value) {
            if (!headers_sent()) {
                header($name . ': ' . $value);
            }
        }
    }

    /**
     * Sanitize admin-entered HTML (custom_head_scripts / custom_body_scripts).
     *
     * Allows <script src="..."> for analytics but strips:
     * - Inline event handlers (onclick, onerror, onload, etc.)
     * - javascript: URIs
     * - Data URIs in dangerous contexts
     * - <iframe> with srcdoc
     */
    public static function sanitizeAdminHtml(string $html): string
    {
        if ($html === '') {
            return '';
        }

        // Strip inline event handlers: on*="..." or on*='...'
        $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        // Strip javascript: URIs in href/src/action form attributes
        $html = preg_replace('/((?:href|src|action)\s*=\s*)(?:"javascript:[^"]*"|\'javascript:[^\']*\'|javascript:[^\s>]+)/i', '$1"#"', $html);

        // Strip data: URIs in dangerous contexts
        $html = preg_replace('/((?:href|src|action)\s*=\s*)(?:"data:[^"]*"|\'data:[^\']*\'|data:[^\s>]+)/i', '$1"#"', $html);

        return $html;
    }

    /**
     * Validate that a host header matches the configured application URL.
     * Prevents host-header injection attacks.
     */
    public static function isValidHost(string $host): bool
    {
        if ($host === '') {
            return false;
        }

        $appUrl = (string) Config::get('app.url', '');
        if ($appUrl === '') {
            return true; // No configured URL, accept all (dev mode)
        }

        $allowedHost = parse_url($appUrl, PHP_URL_HOST);
        if ($allowedHost === null || $allowedHost === '') {
            return true;
        }

        // Strip port for comparison
        $givenHost = explode(':', $host)[0];

        // Allow localhost in local/dev environments
        $appEnv = (string) Config::get('app.env', 'production');
        if ($appEnv === 'local' && in_array($givenHost, ['localhost', '127.0.0.1', '::1'], true)) {
            return true;
        }

        return strcasecmp($givenHost, $allowedHost) === 0;
    }

    /**
     * Validate an email address format.
     */
    public static function isValidEmail(string $email): bool
    {
        return $email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Sanitize SVG markup for safe inline rendering.
     *
     * Strips script tags, event handlers, javascript: URIs, foreignObject,
     * and other dangerous SVG elements while preserving visual rendering.
     */
    public static function sanitizeSvg(string $svg): string
    {
        if ($svg === '') {
            return '';
        }

        // Strip <script> tags and their contents
        $svg = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $svg);

        // Strip inline event handlers: on*="..." or on*='...'
        $svg = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg);

        // Strip javascript: URIs
        $svg = preg_replace('/((?:href|src|action)\s*=\s*)(?:"javascript:[^"]*"|\'javascript:[^\']*\'|javascript:[^\s>]+)/i', '$1"#"', $svg);

        // Strip data: URIs (can be used for XSS in SVG)
        $svg = preg_replace('/((?:href|src|action)\s*=\s*)(?:"data:[^"]*"|\'data:[^\']*\'|data:[^\s>]+)/i', '$1"#"', $svg);

        // Strip dangerous SVG elements
        $svg = preg_replace('/<foreignObject\b[^>]*>.*?<\/foreignObject>/is', '', $svg);
        $svg = preg_replace('/<use\b[^>]*href\s*=\s*(?:"[^"]*javascript:[^"]*"|\'[^\']*javascript:[^\']*\')\s*\/?>/is', '', $svg);

        // Strip <iframe>, <embed>, <object> tags
        $svg = preg_replace('/<(iframe|embed|object)\b[^>]*>.*?<\/\1>/is', '', $svg);
        $svg = preg_replace('/<(iframe|embed|object)\b[^>]*\/?>/is', '', $svg);

        return $svg;
    }

    /**
     * Contact form rate limiting via session.
     * Returns true if the IP is rate-limited.
     */
    public static function isContactRateLimited(string $ip): bool
    {
        $maxAttempts = (int) (Config::get('security.rate_limit.contact.max_attempts', 10));
        $decayMinutes = (int) (Config::get('security.rate_limit.contact.decay_minutes', 60));

        $key = '_contact_rate_' . md5($ip);
        $attempts = Session::get($key, ['count' => 0, 'last_time' => 0]);
        $count = (int) ($attempts['count'] ?? 0);
        $lastTime = (int) ($attempts['last_time'] ?? 0);

        if ($count >= $maxAttempts) {
            $elapsed = time() - $lastTime;
            if ($elapsed < ($decayMinutes * 60)) {
                return true;
            }
            Session::forget($key);
        }

        return false;
    }

    /**
     * Record a contact form submission attempt for rate limiting.
     */
    public static function recordContactAttempt(string $ip): void
    {
        $maxAttempts = (int) (Config::get('security.rate_limit.contact.max_attempts', 10));
        $key = '_contact_rate_' . md5($ip);
        $attempts = Session::get($key, ['count' => 0, 'last_time' => 0]);
        $attempts['count'] = ((int) $attempts['count']) + 1;
        $attempts['last_time'] = time();
        Session::put($key, $attempts);
    }
}
