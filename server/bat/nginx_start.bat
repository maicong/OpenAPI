@echo off
tasklist | findstr /i nginx.exe && taskkill /f /im nginx.exe
tasklist | findstr /i nginx_start.exe && taskkill /f /im nginx_start.exe
nginx.exe -p C:/server/nginx && exit