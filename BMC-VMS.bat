@echo off
cd /d C:\sofwares\xampp

start "" /MIN cmd /c apache_start.bat
start "" /MIN cmd /c mysql_start.bat

timeout /t 1 /nobreak >nul

start "" "http://localhost/BulSUVMS/index.html"

exit
