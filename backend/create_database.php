<?php

/**
 * Helper script to create the MySQL database
 * Run this after starting MySQL in XAMPP: php create_database.php
 */

$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';
$database = 'eis_premium_plus';

try {
    // Connect to MySQL server without selecting a database
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✓ Database '$database' created successfully!\n";
    echo "You can now run: php artisan migrate\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nPlease make sure:\n";
    echo "1. MySQL is running in XAMPP\n";
    echo "2. MySQL service is started in XAMPP Control Panel\n";
    echo "3. The credentials in this script match your MySQL setup\n";
    exit(1);
}
