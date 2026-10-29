<?php
/**
 * De AnchoredSelf CMS - Master Header Partial
 */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

Auth::requireLogin(false);
$currentAdmin = Auth::currentUser();
$activeModule = $cmsActiveModule ?? 'dashboard';

$pdo = Database::getConnection();
$newInquiriesCount = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($cmsPageTitle ?? 'Dashboard') ?> | <?= e(CMS_NAME) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="<?= e(SITE_BASE_URL) ?>/assets/images/favicon.svg">
    <link rel="stylesheet" href="<?= e(CMS_BASE_URL) ?>/assets/css/cms.css?v=<?= @filemtime(CMS_PATH . '/assets/css/cms.css') ?: '1' ?>">
    <script>
        window.CMS_BASE_URL = <?= json_encode(CMS_BASE_URL) ?>;
        window.CMS_API_URL = <?= json_encode(CMS_BASE_URL . '/api.php') ?>;
        window.CMS_CSRF_TOKEN = <?= json_encode(csrf_token()) ?>;
    </script>
</head>
<body>
<div class="cms-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="cms-main">
        <header class="cms-topbar">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <button type="button" id="cmsSidebarToggle" class="cms-btn cms-btn-outline cms-btn-sm" aria-label="Toggle CMS Sidebar">
                    <i data-lucide="menu"></i>
                </button>
                <h1 style="margin: 0; font-size: 1.15rem; color: var(--cms-navy-900);">
                    <?= e($cmsPageTitle ?? 'CMS Dashboard') ?>
                </h1>
            </div>

            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <a href="<?= e(SITE_BASE_URL) ?>/" target="_blank" rel="noopener" class="cms-btn cms-btn-outline cms-btn-sm">
                    <i data-lucide="external-link"></i>
                    <span>View Public Website</span>
                </a>
                <div style="font-size: 0.86rem; color: var(--cms-muted); padding: 0 0.4rem;">
                    Signed in as <strong style="color: var(--cms-navy-900);"><?= e($currentAdmin['full_name'] ?? 'Administrator') ?></strong>
                </div>
                <form method="post" action="<?= e(CMS_BASE_URL) ?>/logout.php" style="margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="cms-btn cms-btn-danger cms-btn-sm">
                        <i data-lucide="log-out"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </header>
        <main class="cms-content">

