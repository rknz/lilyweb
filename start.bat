@echo off
title Lily Interiors - Local Dev Server (Production Mirror)
echo ========================================================
echo   Lily Interiors CMS - Local Development Server
echo ========================================================
echo.
echo Checking PHP at C:\xampp\php\php.exe ...
if not exist "C:\xampp\php\php.exe" (
    echo [ERROR] php.exe not found at C:\xampp\php\php.exe
    echo Please install XAMPP or edit this file to point to your PHP.
    pause
    exit /b 1
)

echo.
echo [SERVER READY]
echo Running exact live production mirror at:
echo   --^> http://127.0.0.1:8899
echo   --^> http://localhost:8899
echo.
echo Press Ctrl+C in this window to stop the server anytime.
echo.
"C:\xampp\php\php.exe" -S 127.0.0.1:8899 -t "%~dp0" "%~dp0router.php"
