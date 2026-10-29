<?php
/**
 * De AnchoredSelf CMS - Administrator Login
 * Strictly for practice administrators (no public registration, no client login).
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (Auth::check()) {
    header('Location: ' . CMS_BASE_URL . '/dashboard.php');
    exit;
}

$error = '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'Security token expired. Please reload the page and try again.';
    } else {
        $identifier = trim((string)($_POST['identifier'] ?? ''));
        $password   = (string)($_POST['password'] ?? '');
        $res        = Auth::attemptLogin($identifier, $password);

        if ($res['success']) {
            header('Location: ' . CMS_BASE_URL . '/dashboard.php');
            exit;
        }
        $error = $res['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Sign In | <?= e(CMS_NAME) ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="<?= e(SITE_BASE_URL) ?>/assets/images/favicon.svg">
    <link rel="stylesheet" href="<?= e(CMS_BASE_URL) ?>/assets/css/cms.css?v=<?= @filemtime(CMS_PATH . '/assets/css/cms.css') ?: '1' ?>">
</head>
<body style="background: linear-gradient(135deg, var(--cms-navy-950) 0%, var(--cms-navy-900) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="width: 100%; max-width: 430px; background: #FFFFFF; border-radius: 14px; border-top: 4px solid var(--cms-gold-500); box-shadow: 0 24px 48px rgba(0, 0, 0, 0.32); padding: 2.25rem;">
        <div style="text-align: center; margin-bottom: 1.75rem;">
            <img src="<?= e(SITE_BASE_URL) ?>/assets/images/logo.svg" alt="De AnchoredSelf, LLC" width="240" height="48" style="margin: 0 auto 0.75rem;">
            <h1 style="margin: 0; font-size: 1.3rem; color: var(--cms-navy-900);">Practice CMS Sign In</h1>
            <p style="margin: 0.25rem 0 0; font-size: 0.86rem; color: var(--cms-muted);">
                Authorized practice administrators only
            </p>
        </div>

        <?php if ($error !== ''): ?>
            <div style="background: #FEF2F2; border-left: 4px solid #DC2626; color: #991B1B; padding: 0.85rem 1rem; border-radius: 6px; font-size: 0.88rem; margin-bottom: 1.25rem;" role="alert">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(CMS_BASE_URL) ?>/login.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <div class="cms-form-group" style="margin-bottom: 1.1rem;">
                <label for="loginIdentifier" class="cms-label">Username or Email</label>
                <input type="text" id="loginIdentifier" name="identifier" class="cms-input" required autofocus autocomplete="username" value="<?= e($identifier) ?>">
            </div>

            <div class="cms-form-group" style="margin-bottom: 1.4rem;">
                <label for="loginPassword" class="cms-label">Password</label>
                <input type="password" id="loginPassword" name="password" class="cms-input" required autocomplete="current-password">
            </div>

            <button type="submit" class="cms-btn cms-btn-primary" style="width: 100%; padding: 0.75rem;">
                <i data-lucide="lock"></i>
                <span>Sign In to CMS</span>
            </button>
        </form>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--cms-border); text-align: center; font-size: 0.82rem;">
            <a href="<?= e(SITE_BASE_URL) ?>/" style="color: var(--cms-muted); text-decoration: none;">
                &larr; Return to Public Website
            </a>
        </div>
    </div>
    <script src="<?= e(CMS_BASE_URL) ?>/assets/js/lucide.min.js"></script>
    <script>if (window.lucide) window.lucide.createIcons();</script>
</body>
</html>

