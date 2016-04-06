@echo off
tasklist | findstr /i mysqld.exe && taskkill /f /im mysqld.exe
tasklist | findstr /i php-cgi.exe && taskkill /f /im php-cgi.exe
tasklist | findstr /i nginx.exe && taskkill /f /im nginx.exe
tasklist | findstr /i svnserve.exe && taskkill /f /im svnserve.exe
tasklist | findstr /i stop_server.exe && taskkill /f /im stop_server.exe
exit