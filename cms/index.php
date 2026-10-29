<?php
/**
 * De AnchoredSelf CMS - Entry Point
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (Auth::check()) {
    header('Location: ' . CMS_BASE_URL . '/dashboard.php');
    exit;
}

header('Location: ' . CMS_BASE_URL . '/login.php');
exit;

