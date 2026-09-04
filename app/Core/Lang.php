<?php

declare(strict_types=1);

namespace Lilyweb\Core;

/**
 * Localization and Language Manager.
 * Supports English ('en') and Bengali ('bn') with automatic persistence.
 */
class Lang
{
    private static ?string $currentLocale = null;
    private static array $translations = [];

    public static function getLocale(): string
    {
        if (self::$currentLocale !== null) {
            return self::$currentLocale;
        }

        // 1. Check Query Parameter
        if (!empty($_GET['lang']) && in_array(strtolower((string) $_GET['lang']), ['en', 'bn'], true)) {
            self::$currentLocale = strtolower((string) $_GET['lang']);
            self::persistLocale(self::$currentLocale);
            return self::$currentLocale;
        }

        // 2. Check Cookie
        if (!empty($_COOKIE['LILY_LANG']) && in_array(strtolower((string) $_COOKIE['LILY_LANG']), ['en', 'bn'], true)) {
            self::$currentLocale = strtolower((string) $_COOKIE['LILY_LANG']);
            return self::$currentLocale;
        }

        // 3. Default to English (or Bengali if configured)
        self::$currentLocale = 'en';
        return self::$currentLocale;
    }

    public static function setLocale(string $locale): void
    {
        if (in_array(strtolower($locale), ['en', 'bn'], true)) {
            self::$currentLocale = strtolower($locale);
            self::persistLocale(self::$currentLocale);
        }
    }

    public static function isBn(): bool
    {
        return self::getLocale() === 'bn';
    }

    public static function get(string $key, array $replace = []): string
    {
        $locale = self::getLocale();
        if (!isset(self::$translations[$locale])) {
            self::loadTranslations($locale);
        }

        $line = self::$translations[$locale][$key] ?? $key;

        // If missing in BN, fallback to EN
        if ($line === $key && $locale === 'bn') {
            if (!isset(self::$translations['en'])) {
                self::loadTranslations('en');
            }
            $line = self::$translations['en'][$key] ?? $key;
        }

        foreach ($replace as $placeholder => $value) {
            $line = str_replace(':' . $placeholder, (string) $value, $line);
        }

        return $line;
    }

    public static function transNumber(string|int|float $number): string
    {
        if (!self::isBn()) {
            return (string) $number;
        }

        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];

        return str_replace($en, $bn, (string) $number);
    }

    private static function loadTranslations(string $locale): void
    {
        $file = dirname(__DIR__, 2) . '/resources/lang/' . $locale . '.php';
        if (file_exists($file)) {
            self::$translations[$locale] = require $file;
        } else {
            self::$translations[$locale] = [];
        }
    }

    private static function persistLocale(string $locale): void
    {
        if (!headers_sent()) {
            setcookie('LILY_LANG', $locale, [
                'expires' => time() + (86400 * 365),
                'path' => '/',
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
        }
    }
}
