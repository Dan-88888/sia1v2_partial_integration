@echo off
title SUSA - Start All Systems

echo ================================================
echo   SUSA - State University System for All
echo   Starting all systems...
echo ================================================
echo.

:: Start XAMPP Apache and MySQL
echo [1/3] Starting XAMPP Apache and MySQL...
start "" "C:\xampp\xampp-control.exe"
timeout /t 3 /nobreak >nul

:: Start the React frontend
echo [2/3] Starting React Frontend (localhost:3000)...
cd /d "C:\xampp\htdocs\integration_frontendUI\main-frontend"
start "React Frontend" cmd /k "npm start"
timeout /t 2 /nobreak >nul

echo.
echo ================================================
echo   Done! Wait a few seconds then open:
echo   http://localhost:3000
echo ================================================
echo.
pause
