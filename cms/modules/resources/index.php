<?php
/**
 * De AnchoredSelf CMS - Resources Module Alias (Redirects to Videos & Resources Module)
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/auth.php';
header('Location: ' . CMS_BASE_URL . '/modules/videos/');
exit;

