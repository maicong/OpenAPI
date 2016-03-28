@echo off
tasklist | findstr /i svnserve.exe && taskkill /f /im svnserve.exe
tasklist | findstr /i svnserve_stop.exe && taskkill /f /im svnserve_stop.exe