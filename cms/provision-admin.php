<?php
/**
 * De AnchoredSelf CMS - CLI-Only Administrator Provisioning Script
 *
 * Usage from terminal:
 *   C:\xampp\php\php.exe c:\xampp\htdocs\anchored\cms\provision-admin.php --username="admin" --email="admin@deanchoredselfs.org" --name="Practice Administrator" --password="YourStrongPassword!"
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden: Administrator provisioning can only be executed from the command line (CLI).');
}

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';

$options = getopt('', ['username:', 'email:', 'name::', 'password:', 'role::']);

$username = trim((string)($options['username'] ?? ''));
$email    = trim((string)($options['email'] ?? ''));
$fullName = trim((string)($options['name'] ?? 'Practice Administrator'));
$password = (string)($options['password'] ?? '');
$role     = in_array($options['role'] ?? '', ['super_admin', 'editor'], true) ? $options['role'] : 'super_admin';

if ($username === '' || $email === '' || strlen($password) < 8) {
    fwrite(STDERR, "Usage: php cms/provision-admin.php --username=\"...\" --email=\"...\" --password=\"(min 8 chars)\" [--name=\"...\"]\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo = Database::getConnection();

$stmt = $pdo->prepare("
    INSERT INTO cms_admins (username, email, full_name, password_hash, role, is_active)
    VALUES (:u, :e, :n, :h, :r, 1)
    ON DUPLICATE KEY UPDATE
        email = VALUES(email),
        full_name = VALUES(full_name),
        password_hash = VALUES(password_hash),
        role = VALUES(role),
        is_active = 1
");
$stmt->execute([
    ':u' => $username,
    ':e' => $email,
    ':n' => $fullName,
    ':h' => $hash,
    ':r' => $role,
]);

echo "CMS Administrator '{$username}' ({$email}) provisioned successfully.\n";

