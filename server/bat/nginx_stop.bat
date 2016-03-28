@echo off

tasklist | findstr /i nginx.exe && taskkill /f /im nginx.exe
tasklist | findstr /i nginx_start.exe && taskkill /f /im nginx_start.exe
tasklist | findstr /i nginx_stop.exe && taskkill /f /im nginx_stop.exe
tasklist | findstr /i conhost.exe && taskkill /f /im conhost.exe