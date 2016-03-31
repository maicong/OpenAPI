@echo off
tasklist | findstr /i svnserve.exe && taskkill /f /im svnserve.exe
tasklist | findstr /i svnserve_start.exe && taskkill /f /im svnserve_start.exe
svnserve.exe -d -r C:\server\svn --log-file C:\server\temp\svn.log
exit