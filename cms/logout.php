<?php
/**
 * De AnchoredSelf CMS - Administrator Sign Out
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token()) {
    Auth::logout();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    Auth::logout();
}

header('Location: ' . CMS_BASE_URL . '/login.php');
exit;

