@echo off
echo Applying all database migrations to eis_premium_plus...
echo.
php artisan migrate --force
echo.
echo Running seeders...
php artisan db:seed --force
echo.
echo Done! All tables and columns have been created.
pause
