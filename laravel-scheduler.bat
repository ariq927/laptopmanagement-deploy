@echo off
cd C:\projects\laptop-management-Deploy
C:\xampp\php\php.exe artisan schedule:run >> NUL 2>&1