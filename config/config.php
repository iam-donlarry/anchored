<?php
/**
 * De AnchoredSelf, LLC - Application Configuration
 */

declare(strict_types=1);

// Prevent direct script execution if not included properly
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'config.php') {
    http_response_code(403);
    exit('Access denied.');
}

// Timezone - Eastern Time per practice standard
date_default_timezone_set('America/New_York');

// Safe Session Initialization with Cookie Hardening
if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    $lifetime = 8 * 3600; // 8 hours
    ini_set('session.gc_maxlifetime', (string)$lifetime);
    ini_set('session.cookie_lifetime', (string)$lifetime);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
    );

    session_name('ANCHORED_SESS');
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

// Core Path Constants
define('ROOT_PATH', dirname(__DIR__));
define('CMS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'cms');
define('UPLOAD_DIR', CMS_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR);
define('MAX_UPLOAD_SIZE', 15 * 1024 * 1024); // 15 MB for approved images/videos

// Dynamic Base URL Detection
if (!defined('SITE_BASE_URL')) {
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
    );
    $protocol = $isHttps ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    // Detect if hosted under /anchored subfolder (e.g. XAMPP) or root domain
    if (strpos($scriptName, '/anchored/') === 0 || $scriptName === '/anchored') {
        $siteBase = $protocol . $host . '/anchored';
    } else {
        $siteBase = $protocol . $host;
    }

    define('SITE_BASE_URL', rtrim($siteBase, '/'));
    define('CMS_BASE_URL', SITE_BASE_URL . '/cms');
    define('UPLOAD_BASE_URL', CMS_BASE_URL . '/uploads/media/');
}

define('APP_NAME', 'De AnchoredSelf, LLC');
define('CMS_NAME', 'De AnchoredSelf CMS');
define('APP_VERSION', '1.0.0');

