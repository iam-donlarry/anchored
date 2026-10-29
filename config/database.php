<?php
/**
 * De AnchoredSelf, LLC - Database Connection (PDO)
 * Uses existing MySQL database `anchored`
 */

declare(strict_types=1);

if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'database.php') {
    http_response_code(403);
    exit('Access denied.');
}

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Load optional local environment overrides if present outside git
            $envFile = __DIR__ . '/.env.local.php';
            $env = is_file($envFile) ? (include $envFile) : [];

            $host     = $env['DB_HOST'] ?? (getenv('DB_HOST') ?: 'localhost');
            $port     = $env['DB_PORT'] ?? (getenv('DB_PORT') ?: '3306');
            $dbname   = 'anchored'; // Non-negotiable existing database name
            $username = $env['DB_USER'] ?? (getenv('DB_USER') ?: 'root');
            $password = $env['DB_PASS'] ?? (getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];

            try {
                self::$instance = new PDO($dsn, (string)$username, (string)$password, $options);
            } catch (PDOException $e) {
                error_log('De AnchoredSelf Database Connection Error: ' . $e->getMessage());
                if (PHP_SAPI === 'cli') {
                    fwrite(STDERR, "Database connection failed.\n");
                    exit(1);
                }
                http_response_code(500);
                exit('The service is temporarily unavailable. Please call 443-912-4612 for assistance.');
            }
        }

        return self::$instance;
    }
}

