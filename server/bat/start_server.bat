@echo off
tasklist | findstr /i mysqld.exe && taskkill /f /im mysqld.exe
tasklist | findstr /i php-cgi.exe && taskkill /f /im php-cgi.exe
tasklist | findstr /i nginx.exe && taskkill /f /im nginx.exe
tasklist | findstr /i svnserve.exe && taskkill /f /im svnserve.exe
tasklist | findstr /i start_server.exe && taskkill /f /im start_server.exe

mysqld.exe | php-cgi.exe -c C:/server/php/php.ini -b 127.0.0.1:9000 | nginx.exe -p C:/server/nginx | svnserve.exe -d -r C:\server\svn --log-file C:\server\temp\svn.log
exit