<?php
/**
 * Database Configuration for Velvet Vogue E-Commerce Website
 * Uses PDO for secure database connections
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'velvet_vogue_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP MySQL password is empty

// Global database connection variable
$pdo = null;

/**
 * Establish database connection using PDO
 * @return PDO Database connection object
 * @throws PDOException If connection fails
 */
function getDatabaseConnection() {
    global $pdo;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new PDOException("Database connection failed. Please check your configuration.");
        }
    }
    
    return $pdo;
}

/**
 * Close database connection
 */
function closeDatabaseConnection() {
    global $pdo;
    $pdo = null;
}

// Initialize database connection
try {
    getDatabaseConnection();
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
