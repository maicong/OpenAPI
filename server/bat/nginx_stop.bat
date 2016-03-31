@echo off
tasklist | findstr /i nginx.exe && taskkill /f /im nginx.exe
tasklist | findstr /i nginx_stop.exe && taskkill /f /im nginx_stop.exe
exit