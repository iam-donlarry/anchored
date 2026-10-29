<?php
/**
 * De AnchoredSelf, LLC - Public Website Master Header Partial
 * Delegates the navigation menu to the reusable `includes/navbar.php` component.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/Repository.php';

$practiceName    = Repository::setting('practice_name', 'De AnchoredSelf, LLC');
$tagline         = Repository::setting('tagline', 'Grounded in Healing. Growing in Strength. Delivering Hope.');
$telephone       = Repository::setting('telephone', '443-912-4612');
$fullAddress     = Repository::setting('full_address', '403 Eastern Boulevard, Essex, Maryland 21221');
$officeHours     = Repository::setting('office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time');
$logoDark        = resolve_asset_url(Repository::setting('logo_dark', '/assets/images/logo.svg'));
$primaryCtaText  = Repository::setting('primary_cta_text', 'Request an Appointment');

$metaTitle       = !empty($pageMetaTitle) ? $pageMetaTitle : Repository::setting('seo_default_title', $practiceName);
$metaDescription = !empty($pageMetaDescription) ? $pageMetaDescription : Repository::setting('seo_default_description', $tagline);
$activeSlug      = $activeNav ?? 'home';
$canonicalUrl    = SITE_BASE_URL . ($canonicalPath ?? '/');
?>
<!DOCTYPE html>
<html lang="en" id="top">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <link rel="icon" type="image/svg+xml" href="<?= e(resolve_asset_url('/assets/images/favicon.svg')) ?>">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e($practiceName) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">

    <!-- Preload Self-Hosted Primary Font Weights -->
    <link rel="preload" href="<?= e(SITE_BASE_URL) ?>/assets/fonts/Outfit-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= e(SITE_BASE_URL) ?>/assets/fonts/Outfit-600.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= e(SITE_BASE_URL) ?>/assets/fonts/Outfit-700.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Local Stylesheet -->
    <link rel="stylesheet" href="<?= e(SITE_BASE_URL) ?>/assets/css/main.css?v=<?= @filemtime(ROOT_PATH . '/assets/css/main.css') ?: '2' ?>">
</head>
<body>
    <a href="#mainContent" class="skip-link">Skip to main content</a>

    <!-- Top Practice Information Bar -->
    <div class="utility-bar" role="region" aria-label="Practice Hours and Location">
        <div class="container utility-bar-inner">
            <div class="utility-items">
                <span class="utility-item">
                    <i data-lucide="clock"></i>
                    <span><?= e($officeHours) ?></span>
                </span>
                <span class="utility-item">
                    <i data-lucide="map-pin"></i>
                    <span><?= e($fullAddress) ?> &bull; Telehealth &amp; In-Person</span>
                </span>
            </div>
            <div class="utility-items">
                <a href="<?= e(format_tel_href($telephone)) ?>" class="utility-item utility-phone" aria-label="Call <?= e($practiceName) ?> at <?= e($telephone) ?>">
                    <i data-lucide="phone-call"></i>
                    <span><?= e($telephone) ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Reusable Navigation Menu Component -->
    <?php include __DIR__ . '/navbar.php'; ?>

    <main id="mainContent">
