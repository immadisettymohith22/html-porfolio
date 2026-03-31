@echo off
set "BASE_DIR=%~dp0\..\.."
cd /d "%BASE_DIR%\mysql"

if not exist "data" (
    echo Error: Could not find MySQL data folder.
    pause
    exit /b
)

echo.
echo ========================================
echo   MySQL Data Repair for Academic Portal
echo ========================================
echo.

echo [1/5] Backing up current data folder to 'data_old'...
rename data data_old

echo [2/5] Initializing new data folder from backup...
if exist "backup" (
    xcopy /e /i /q backup data
) else (
    echo Error: 'backup' folder not found.
    pause
    exit /b
)

echo [3/5] Restoring user databases...
set "dbs=academic_portal academy studentdb"
for %%d in (%dbs%) do (
    if exist "data_old\%%d" (
        echo   - Restoring: %%d
        xcopy /e /i /q "data_old\%%d" "data\%%d"
    )
)

echo [4/5] Restoring InnoDB tablespace (ibdata1)...
if exist "data_old\ibdata1" (
    copy /y "data_old\ibdata1" "data\ibdata1"
)

echo [5/5] Finalizing...
if exist "data\mysql.pid" del "data\mysql.pid"

echo.
echo ========================================
echo   REPAIR COMPLETE!
echo ========================================
echo Please try starting MySQL from the XAMPP Control Panel now.
echo.
pause
