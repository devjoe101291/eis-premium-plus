Write-Host "Applying all database migrations to eis_premium_plus..." -ForegroundColor Cyan
Write-Host ""

php artisan migrate --force

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "Running seeders..." -ForegroundColor Cyan
    php artisan db:seed --force
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "Done! All tables and columns have been created." -ForegroundColor Green
    } else {
        Write-Host "Error running seeders." -ForegroundColor Red
    }
} else {
    Write-Host ""
    Write-Host "Error: Could not connect to MySQL database." -ForegroundColor Red
    Write-Host "Please make sure:" -ForegroundColor Yellow
    Write-Host "1. MySQL is running in XAMPP" -ForegroundColor Yellow
    Write-Host "2. The database 'eis_premium_plus' exists" -ForegroundColor Yellow
    Write-Host "3. MySQL credentials are correct in .env file" -ForegroundColor Yellow
}
