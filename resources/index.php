<?php
/**
 * De AnchoredSelf, LLC - Videos & Educational Resources (/resources/)
 * Code-defined page structure with scroll-reveal animations and CMS-managed published resources (`videos_resources`).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'resources';
$canonicalPath       = '/resources/';
$pageMetaTitle       = 'Videos & Educational Resources | De AnchoredSelf, LLC';
$pageMetaDescription = 'Practice introductions, psychotherapy insights, and whole-person wellness educational resources from De AnchoredSelf, LLC.';

$videoItems = Repository::getAllPublishedVideos();

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="play-circle"></i>
                <span>Practice Introductions &amp; Wellness Education</span>
            </span>
            <h1>Videos and Educational Resources</h1>
            <p class="page-hero-sub">
                Explore short video introductions, reflections on emotional well-being, and educational wellness guides from our practice.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/calm-nature.jpg')) ?>"
                 alt="Calm natural landscape representing reflection and whole-person education"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container" data-reveal="fade-up">
        <div class="notice-banner" style="margin-bottom: 2.5rem;">
            <i data-lucide="book-open" style="color: var(--gold-600); flex-shrink: 0;"></i>
            <div>
                <strong>Educational Purpose Notice:</strong> All videos, articles, and guides on this page are provided for general informational and educational purposes. They do not establish a therapist-client relationship or replace individualized clinical care or medical advice.
            </div>
        </div>

        <?php if (empty($videoItems)): ?>
            <div class="inquiry-card" style="text-align: center; max-width: 680px; margin: 0 auto;">
                <h2 style="margin: 0 0 0.65rem; font-size: 1.5rem; color: var(--navy-900);">Educational Resources Coming Soon</h2>
                <p style="margin: 0 0 1.5rem; color: var(--text-secondary);">
                    We are preparing new educational videos and wellness guides. Please check back soon or reach out to our office directly with any questions about our services.
                </p>
                <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-primary btn-shimmer">
                    <i data-lucide="calendar-heart"></i>
                    <span>Request an Appointment</span>
                </a>
            </div>
        <?php else: ?>
            <?php include dirname(__DIR__) . '/includes/video-cards.php'; ?>
        <?php endif; ?>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
