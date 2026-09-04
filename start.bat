@echo off
title Lily Interiors - Local Dev Server
echo ============================================
echo   Lily Interiors CMS - Local Development
echo ============================================
echo.
echo Checking PHP...
if not exist "C:\xampp\php\php.exe" (
    echo [ERROR] php.exe not found at C:\xampp\php\php.exe
    echo Please install XAMPP or edit this file to point to your PHP.
    pause
    exit /b 1
)

echo Starting server at:  http://127.0.0.1:8899
echo Press Ctrl+C to stop.
echo.
"C:\xampp\php\php.exe" -S 127.0.0.1:8899 -t "%~dp0public" "%~dp0router.php"
