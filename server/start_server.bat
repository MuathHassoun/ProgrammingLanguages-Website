@echo off
setlocal enabledelayedexpansion

:: -- Read config.yaml --
set "php_ini_path="
set "project_path="

:: Loop through each line of config.yaml, splitting by ':' into key and value
for /f "usebackq tokens=1,* delims=:" %%A in ("config.yaml") do (
    set "line_key=%%A"
    set "line_val=%%B"
    :: Remove spaces from key and value
    set "line_key=!line_key: =!"
    set "line_val=!line_val: =!"

    :: Check if the key matches and assign the corresponding value
    if /i "!line_key!"=="php_ini_path" set "php_ini_path=!line_val!"
    if /i "!line_key!"=="project_path" set "project_path=!line_val!"
)

:: Check if php_ini_path was found in config.yaml
if "%php_ini_path%"=="" (
    echo ERROR: php_ini_path not found in config.yaml
    pause
    exit /b 1
)

:: Check if project_path was found in config.yaml
if "%project_path%"=="" (
    echo ERROR: project_path not found in config.yaml
    pause
    exit /b 1
)

:: Extract PHP folder path from the full php.ini path
for %%i in ("%php_ini_path%") do set "php_folder=%%~dpi"
:: Remove trailing backslash if present
if "%php_folder:~-1%"=="\" set "php_folder=%php_folder:~0,-1%"

:: Show message to user
echo Open your browser and go to:
echo.
echo    http://localhost:8000
echo.

:: Change directory to the PHP folder
cd /d "%php_folder%"

:: Run the PHP built-in server serving files from the project directory
php -S localhost:8000 -t "%project_path%"

pause
