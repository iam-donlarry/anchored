<?php
/**
 * De AnchoredSelf, LLC - Wellness, Pain Management & Weight-Management Support (/wellness/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero & Editorial Split with Medical/Educational Scope Notice
 *  - Sticky Chapter Rail + Alternating Editorial Tapestry Stream (Chapters 01–06)
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'wellness';
$canonicalPath       = '/wellness/';
$pageMetaTitle       = 'Wellness, Pain Management & Weight-Management Support | De AnchoredSelf, LLC';
$pageMetaDescription = 'Whole-person wellness, behavioral pain-management counseling, herbalism, juice therapy, and weight-management support at De AnchoredSelf, LLC.';

$videoItems = Repository::getVideosByPlacement('wellness', 3);

$wellnessServices = [
    [
        'num'      => '01',
        'slug'     => 'whole-person-wellness',
        'title'    => 'Whole-Person Wellness',
        'subtitle' => 'Lifestyle Habits, Stress Management & Sustainable Self-Care Routines',
        'body'     => '<p>Guidance focused on lifestyle habits, stress management, emotional balance, self-care routines, and sustainable wellness goals.</p>',
        'scope'    => 'Educational and supportive wellness guidance designed to complement care from your medical providers.',
        'image'    => '/assets/images/placeholders/wellness-botanical.jpg',
        'inquiry'  => 'Whole-Person Wellness',
    ],
    [
        'num'      => '02',
        'slug'     => 'pain-management-counseling',
        'title'    => 'Behavioral Pain-Management Counseling & Program Eligibility Assessments',
        'subtitle' => 'Emotional & Behavioral Support for Living With Chronic or Persistent Pain',
        'body'     => '<p>Living with ongoing pain can affect mood, sleep, relationships, work, daily functioning, and overall quality of life. We offer supportive counseling focused on the emotional and behavioral impact of chronic or persistent pain, including stress regulation, coping strategies, pacing, emotional resilience, and adjustment support.</p><p>We also provide psychological eligibility assessments for pain management programs when required by a referring provider or program.</p>',
        'scope'    => 'Behavioral pain-management support complements medical pain care and does not include prescribing medication.',
        'image'    => '/assets/images/placeholders/calm-nature.jpg',
        'inquiry'  => 'Pain-Management Counseling',
    ],
    [
        'num'      => '03',
        'slug'     => 'herbalism',
        'title'    => 'Herbalism',
        'subtitle' => 'Educational & Wellness-Oriented Herbal Consultations',
        'body'     => '<p>Educational and wellness-oriented herbal consultations designed to support general well-being and informed self-care practices.</p>',
        'scope'    => 'Educational wellness consultations do not diagnose, treat, or cure medical conditions or replace physician evaluation.',
        'image'    => '/assets/images/placeholders/herbal-tea.jpg',
        'inquiry'  => 'Herbalism',
    ],
    [
        'num'      => '04',
        'slug'     => 'juice-therapy',
        'title'    => 'Juice Therapy',
        'subtitle' => 'Nutrition-Conscious Juicing Education as Part of a Balanced Lifestyle',
        'body'     => '<p>Wellness education and supportive guidance centered on nutrition-conscious juicing practices as part of a balanced lifestyle.</p>',
        'scope'    => 'Supportive nutrition and wellness education tailored to sustainable daily habits.',
        'image'    => '/assets/images/placeholders/juice-nutrition.jpg',
        'inquiry'  => 'Juice Therapy',
    ],
    [
        'num'      => '05',
        'slug'     => 'weight-management-support',
        'title'    => 'Weight-Management Support',
        'subtitle' => 'Personalized Support for Habits, Mindset & Sustainable Lifestyle Change',
        'body'     => '<p>Personalized support addressing habits, motivation, mindset, emotional eating patterns, accountability, and sustainable lifestyle change.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/wellness-botanical.jpg',
        'inquiry'  => 'Weight-Management Support',
    ],
    [
        'num'      => '06',
        'slug'     => 'weight-loss-surgery-assessments',
        'title'    => 'Weight-Loss Surgery Assessment Consultations',
        'subtitle' => 'Pre-Surgical Behavioral-Health Assessment Consultations',
        'body'     => '<p>Consultations and behavioral-health assessments for individuals preparing for weight-loss surgery, focused on readiness, lifestyle adjustment, support systems, and post-surgical success.</p><p>Clients should confirm specific assessment requirements with their surgical program or referring provider.</p>',
        'scope'    => 'Final surgical eligibility and clearance are determined by your surgical program and medical team.',
        'image'    => '/assets/images/placeholders/counseling-space.jpg',
        'inquiry'  => 'Weight-Loss Surgery Assessment Consultation',
    ],
];

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="leaf"></i>
                <span>Mind, Body &amp; Daily Habits</span>
            </span>
            <h1>Wellness, Pain Management, and Weight-Management Support</h1>
            <p class="page-hero-sub">
                De AnchoredSelf, LLC offers integrative wellness services that support healthy routines, informed self-care, and whole-person balance.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/wellness-botanical.jpg')) ?>"
                 alt="Whole-person wellness and botanical elements at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="editorial-split" style="margin-bottom: 4.5rem;">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Integrative Approach</span>
                <h2 class="section-title">Supporting Whole-Person Balance</h2>
                <div class="gold-divider"></div>
                <p>
                    De AnchoredSelf, LLC offers integrative wellness services that support healthy routines, informed self-care, and whole-person balance.
                </p>
                <p>
                    We recognize that emotional well-being connects closely with physical vitality, daily nutrition, chronic pain coping, and sustainable lifestyle habits. Our wellness consultations and behavioral counseling options are tailored to help you build realistic, lasting routines.
                </p>
            </div>
            <div data-reveal="fade-left">
                <div class="notice-banner warning" style="margin-bottom: 0; padding: 1.5rem;">
                    <i data-lucide="shield-alert" style="flex-shrink: 0;"></i>
                    <div>
                        <strong style="display: block; font-size: 1.05rem; margin-bottom: 0.35rem;">Important Wellness &amp; Medical Disclaimer</strong>
                        <p style="margin: 0;">
                            Wellness, herbalism, juice therapy, and weight-management services are educational and supportive in nature and do not replace primary medical care, physician evaluation, or emergency services.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="chapter-layout">
            <aside class="chapter-rail" aria-label="Wellness Offerings Index">
                <div class="chapter-rail-title">Wellness Chapters</div>
                <ul class="chapter-rail-list">
                    <?php foreach ($wellnessServices as $srv): ?>
                        <li>
                            <a href="#<?= e($srv['slug']) ?>" class="chapter-rail-link">
                                <span class="num"><?= e($srv['num']) ?></span>
                                <span><?= e($srv['title']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="tapestry-stream">
                <?php foreach ($wellnessServices as $idx => $srv):
                    $isReverse = ($idx % 2 === 1);
                ?>
                    <article class="tapestry-row <?= $isReverse ? 'reverse' : '' ?>" id="<?= e($srv['slug']) ?>" data-reveal="fade-up">
                        <div class="tapestry-text">
                            <div class="tapestry-watermark"><?= e($srv['num']) ?></div>
                            <h3 style="margin: 0 0 0.35rem; font-size: 1.6rem; color: var(--navy-900);"><?= e($srv['title']) ?></h3>
                            <div style="font-size: 0.95rem; font-weight: 600; color: var(--gold-600); margin-bottom: 0.85rem;"><?= e($srv['subtitle']) ?></div>
                            <div class="prose">
                                <?= $srv['body'] ?>
                            </div>
                            <?php if (!empty($srv['scope'])): ?>
                                <div class="service-scope-callout" style="margin-bottom: 1.25rem;">
                                    <strong>Scope Note:</strong> <?= e($srv['scope']) ?>
                                </div>
                            <?php endif; ?>
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem; margin-top: 1.25rem;">
                                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=<?= urlencode($srv['inquiry']) ?>#inquiry-form" class="btn btn-primary btn-shimmer">
                                    <i data-lucide="calendar-heart"></i>
                                    <span>Request an Appointment</span>
                                </a>
                                <span style="font-size: 0.88rem; color: var(--text-muted); font-weight: 500;">
                                    <i data-lucide="check-circle-2" style="width: 15px; height: 15px; vertical-align: text-bottom; color: var(--gold-600);"></i>
                                    Telehealth &amp; In-Person Consultations
                                </span>
                            </div>
                        </div>

                        <div class="tapestry-visual">
                            <div class="tapestry-img-frame">
                                <img src="<?= e(resolve_asset_url($srv['image'])) ?>"
                                     alt="<?= e($srv['title']) ?>"
                                     width="480"
                                     height="360"
                                     loading="lazy">
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($videoItems)): ?>
<section class="section section-ivory">
    <div class="container" data-reveal="fade-up">
        <div class="section-header">
            <span class="eyebrow-badge eyebrow-light-bg">Wellness Education</span>
            <h2 class="section-title">Wellness Videos &amp; Resources</h2>
            <div class="gold-divider"></div>
        </div>
        <?php include dirname(__DIR__) . '/includes/video-cards.php'; ?>
    </div>
</section>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
