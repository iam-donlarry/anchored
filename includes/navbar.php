<?php
/**
 * De AnchoredSelf, LLC - Reusable Navigation Menu Component (includes/navbar.php)
 * Single source of truth for the public website navigation bar, dropdown menus, and primary CTA.
 * Included by `includes/header.php` on all public-facing pages.
 */

declare(strict_types=1);

$activeSlug     = $activeNav ?? 'home';
$practiceName   = $practiceName ?? Repository::setting('practice_name', 'De AnchoredSelf, LLC');
$logoDark       = $logoDark ?? resolve_asset_url(Repository::setting('logo_dark', '/assets/images/logo.svg'));
$primaryCtaText = $primaryCtaText ?? Repository::setting('primary_cta_text', 'Request an Appointment');

$navItems = [
    [
        'type'   => 'link',
        'slug'   => 'home',
        'label'  => 'Home',
        'href'   => SITE_BASE_URL . '/',
    ],
    [
        'type'   => 'link',
        'slug'   => 'about',
        'label'  => 'About Our Practice',
        'href'   => SITE_BASE_URL . '/about/',
    ],
    [
        'type'        => 'dropdown',
        'label'       => 'Psychotherapy & Faith',
        'href'        => SITE_BASE_URL . '/services/',
        'activeSlugs' => ['psychotherapy', 'christian-counseling', 'services'],
        'ariaLabel'   => 'Psychotherapy and Counseling Submenu',
        'children'    => [
            [
                'slug'  => 'psychotherapy',
                'title' => 'Psychotherapy',
                'desc'  => 'Individual, couples, family, group, child/teen (5+), trauma & substance-use',
                'href'  => SITE_BASE_URL . '/psychotherapy/',
                'icon'  => 'message-circle-heart',
            ],
            [
                'slug'  => 'christian-counseling',
                'title' => 'Christian Counseling',
                'desc'  => 'Optional faith-integrated care available upon request',
                'href'  => SITE_BASE_URL . '/christian-counseling/',
                'icon'  => 'book-open-check',
            ],
            [
                'slug'  => 'services',
                'title' => 'Complete Service Directory',
                'desc'  => 'View all practice offerings in one place',
                'href'  => SITE_BASE_URL . '/services/',
                'icon'  => 'compass',
            ],
        ],
    ],
    [
        'type'        => 'dropdown',
        'label'       => 'Wellness & Specialized',
        'href'        => SITE_BASE_URL . '/wellness/',
        'activeSlugs' => ['wellness', 'weight-loss-surgery-assessments', 'clinical-supervision'],
        'ariaLabel'   => 'Wellness, Assessments, and Supervision Submenu',
        'children'    => [
            [
                'slug'  => 'wellness',
                'title' => 'Wellness & Pain Support',
                'desc'  => 'Whole-person wellness, herbalism, juice therapy, weight & pain support',
                'href'  => SITE_BASE_URL . '/wellness/',
                'icon'  => 'leaf',
            ],
            [
                'slug'  => 'weight-loss-surgery-assessments',
                'title' => 'Weight-Loss Surgery Assessments',
                'desc'  => 'Pre-surgical behavioral health & pain program eligibility consultations',
                'href'  => SITE_BASE_URL . '/weight-loss-surgery-assessments/',
                'icon'  => 'clipboard-check',
            ],
            [
                'slug'  => 'clinical-supervision',
                'title' => 'Clinical Supervision',
                'desc'  => 'Professional counselor development & ethical LCPC-S supervision',
                'href'  => SITE_BASE_URL . '/clinical-supervision/',
                'icon'  => 'award',
            ],
        ],
    ],
    [
        'type'   => 'link',
        'slug'   => 'membership-faq',
        'label'  => 'Membership & FAQ',
        'href'   => SITE_BASE_URL . '/membership-faq/',
    ],
    [
        'type'   => 'link',
        'slug'   => 'resources',
        'label'  => 'Resources',
        'href'   => SITE_BASE_URL . '/resources/',
    ],
    [
        'type'   => 'link',
        'slug'   => 'events',
        'label'  => 'Events & Gallery',
        'href'   => SITE_BASE_URL . '/events/',
    ],
    [
        'type'   => 'link',
        'slug'   => 'contact',
        'label'  => 'Contact',
        'href'   => SITE_BASE_URL . '/contact/',
    ],
];
?>
<!-- Main Sticky Header & Navigation Component -->
<header class="site-header" id="siteHeader">
    <div class="scroll-progress-track" aria-hidden="true">
        <div class="scroll-progress-bar" id="scrollProgressBar"></div>
    </div>
    <div class="container header-inner">
        <a href="<?= e(SITE_BASE_URL) ?>/" class="brand-logo-link" aria-label="<?= e($practiceName) ?> Home">
            <img src="<?= e($logoDark) ?>" alt="<?= e($practiceName) ?> Logo" class="brand-logo" width="265" height="53">
        </a>

        <button type="button" class="mobile-nav-toggle" id="mobileNavToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Toggle navigation menu">
            <i data-lucide="menu"></i>
        </button>

        <nav class="primary-nav" id="primaryNav" aria-label="Main Navigation">
            <ul class="nav-list">
                <?php foreach ($navItems as $item): ?>
                    <?php if ($item['type'] === 'dropdown'):
                        $isGroupActive = in_array($activeSlug, $item['activeSlugs'], true);
                    ?>
                        <li class="nav-item has-dropdown">
                            <a href="<?= e($item['href']) ?>"
                               class="nav-link dropdown-toggle-btn <?= $isGroupActive ? 'active' : '' ?>"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <span><?= e($item['label']) ?></span>
                                <i data-lucide="chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu" aria-label="<?= e($item['ariaLabel']) ?>">
                                <?php foreach ($item['children'] as $child): ?>
                                    <li>
                                        <a href="<?= e($child['href']) ?>" class="dropdown-link <?= $activeSlug === $child['slug'] ? 'active' : '' ?>">
                                            <span class="dropdown-link-icon">
                                                <i data-lucide="<?= e($child['icon']) ?>"></i>
                                            </span>
                                            <span class="dropdown-link-body">
                                                <span class="dropdown-link-title"><?= e($child['title']) ?></span>
                                                <span class="dropdown-link-desc"><?= e($child['desc']) ?></span>
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="<?= e($item['href']) ?>" class="nav-link <?= $activeSlug === $item['slug'] ? 'active' : '' ?>">
                                <?= e($item['label']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="mobile-nav-cta">
                <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-gold" style="width: 100%;">
                    <i data-lucide="calendar-heart"></i>
                    <span><?= e($primaryCtaText) ?></span>
                </a>
            </div>
        </nav>

        <div class="header-actions">
            <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-primary btn-shimmer">
                <i data-lucide="calendar-heart"></i>
                <span><?= e($primaryCtaText) ?></span>
            </a>
        </div>
    </div>
</header>
