<?php
/**
 * De AnchoredSelf CMS - Authentication, Session Security, and Brute-Force Throttling
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/Repository.php';

class Auth
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;
    private const IDLE_TIMEOUT_SECONDS = 7200; // 2 hours inactivity timeout

    public static function check(): bool
    {
        if (empty($_SESSION['cms_admin_id'])) {
            return false;
        }

        // Check session idle expiration
        if (!empty($_SESSION['cms_last_activity']) && (time() - (int)$_SESSION['cms_last_activity']) > self::IDLE_TIMEOUT_SECONDS) {
            self::logout();
            return false;
        }

        $_SESSION['cms_last_activity'] = time();
        return true;
    }

    public static function requireLogin(bool $isJson = false): void
    {
        if (!self::check()) {
            if ($isJson) {
                http_response_code(401);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(['success' => false, 'message' => 'Your session has expired. Please sign in again.']);
                exit;
            }
            header('Location: ' . CMS_BASE_URL . '/login.php');
            exit;
        }
    }

    public static function currentUser(): ?array
    {
        if (!self::check()) {
            return null;
        }
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id, username, email, full_name, role, last_login_at FROM cms_admins WHERE id = :id AND is_active = 1 LIMIT 1");
        $stmt->execute([':id' => (int)$_SESSION['cms_admin_id']]);
        $user = $stmt->fetch();
        if (!$user) {
            self::logout();
            return null;
        }
        return $user;
    }

    public static function isThrottled(string $identifier, string $ip): bool
    {
        $pdo = Database::getConnection();
        // Clean up old attempts older than 1 day
        $pdo->exec("DELETE FROM cms_login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 DAY)");

        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM cms_login_attempts
            WHERE (ip_address = :ip OR identifier = :id)
              AND attempted_at >= (NOW() - INTERVAL " . self::LOCKOUT_MINUTES . " MINUTE)
        ");
        $stmt->execute([':ip' => $ip, ':id' => strtolower(trim($identifier))]);
        return (int)$stmt->fetchColumn() >= self::MAX_ATTEMPTS;
    }

    public static function recordFailedAttempt(string $identifier, string $ip): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO cms_login_attempts (ip_address, identifier) VALUES (:ip, :id)");
        $stmt->execute([
            ':ip' => mb_substr($ip, 0, 45),
            ':id' => mb_substr(strtolower(trim($identifier)), 0, 160),
        ]);
    }

    public static function clearAttempts(string $identifier, string $ip): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM cms_login_attempts WHERE ip_address = :ip OR identifier = :id");
        $stmt->execute([':ip' => $ip, ':id' => strtolower(trim($identifier))]);
    }

    public static function attemptLogin(string $identifier, string $password): array
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $identifier = trim($identifier);

        if ($identifier === '' || $password === '') {
            return ['success' => false, 'message' => 'Please enter both your username/email and password.'];
        }

        if (self::isThrottled($identifier, $ip)) {
            return [
                'success' => false,
                'message' => 'Too many failed sign-in attempts. Please wait 15 minutes before trying again.',
            ];
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM cms_admins
            WHERE (LOWER(username) = LOWER(:u) OR LOWER(email) = LOWER(:e))
              AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute([':u' => $identifier, ':e' => $identifier]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, (string)$admin['password_hash'])) {
            self::recordFailedAttempt($identifier, $ip);
            return ['success' => false, 'message' => 'Invalid username/email or password.'];
        }

        // Upgrade hash if needed
        if (password_needs_rehash((string)$admin['password_hash'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upStmt = $pdo->prepare("UPDATE cms_admins SET password_hash = :h WHERE id = :id");
            $upStmt->execute([':h' => $newHash, ':id' => $admin['id']]);
        }

        self::clearAttempts($identifier, $ip);

        // Regenerate session ID to prevent session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION['cms_admin_id']      = (int)$admin['id'];
        $_SESSION['cms_admin_name']    = (string)$admin['full_name'];
        $_SESSION['cms_admin_role']    = (string)$admin['role'];
        $_SESSION['cms_last_activity'] = time();
        $_SESSION['csrf_token']        = bin2hex(random_bytes(32));

        $pdo->prepare("UPDATE cms_admins SET last_login_at = NOW() WHERE id = :id")
            ->execute([':id' => $admin['id']]);

        Repository::logAudit((int)$admin['id'], 'login', 'cms_admins', (int)$admin['id'], 'Administrator signed in');

        return ['success' => true, 'message' => 'Signed in successfully.'];
    }

    public static function logout(): void
    {
        if (!empty($_SESSION['cms_admin_id'])) {
            Repository::logAudit((int)$_SESSION['cms_admin_id'], 'logout', 'cms_admins', (int)$_SESSION['cms_admin_id'], 'Administrator signed out');
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool)$params['secure'],
                (bool)$params['httponly']
            );
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}

