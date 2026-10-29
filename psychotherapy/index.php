<?php
/**
 * De AnchoredSelf, LLC - Psychotherapy & Counseling Services (/psychotherapy/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero & Editorial Split with Child/Adolescent (Ages 5+) Continuum
 *  - Sticky Chapter Rail + Alternating Editorial Tapestry Stream (Chapters 01–08)
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'psychotherapy';
$canonicalPath       = '/psychotherapy/';
$pageMetaTitle       = 'Psychotherapy & Counseling Services | De AnchoredSelf, LLC';
$pageMetaDescription = 'Individual, couples, family, group, and child & adolescent (ages 5+) psychotherapy, trauma-informed support, and substance-use counseling at De AnchoredSelf, LLC.';

$videoItems = Repository::getVideosByPlacement('psychotherapy', 3);

$psychotherapyServices = [
    [
        'num'      => '01',
        'slug'     => 'individual-psychotherapy',
        'title'    => 'Individual Psychotherapy',
        'subtitle' => 'Personalized Support for Emotional Healing & Everyday Functioning',
        'body'     => '<p>A supportive space to address anxiety, depression, stress, grief, trauma-related concerns, life transitions, and personal growth. We work collaboratively with you to understand patterns, strengthen coping skills, and develop practical steps toward meaningful change.</p>',
        'scope'    => 'Available for children ages 5 and older, adolescents, adults, and older adults through late adulthood.',
        'image'    => '/assets/images/placeholders/counseling-space.jpg',
        'inquiry'  => 'Individual Psychotherapy',
    ],
    [
        'num'      => '02',
        'slug'     => 'couples-psychotherapy',
        'title'    => 'Couples Psychotherapy',
        'subtitle' => 'Communication, Conflict Resolution & Strengthening Emotional Connection',
        'body'     => '<p>Support for communication challenges, conflict resolution, trust concerns, relationship transitions, and strengthening emotional connection. Sessions help partners explore their needs and work toward shared goals.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/couples-connection.jpg',
        'inquiry'  => 'Couples Psychotherapy',
    ],
    [
        'num'      => '03',
        'slug'     => 'family-psychotherapy',
        'title'    => 'Family Psychotherapy',
        'subtitle' => 'Collaborative Support for Healthier Family Dynamics',
        'body'     => '<p>Collaborative support to improve communication, navigate family stressors, strengthen relationships, and foster healthier family dynamics. Sessions create opportunities to understand different perspectives and develop healthier ways of relating.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/family-support.jpg',
        'inquiry'  => 'Family Psychotherapy',
    ],
    [
        'num'      => '04',
        'slug'     => 'group-psychotherapy',
        'title'    => 'Group Psychotherapy',
        'subtitle' => 'Shared Learning, Skill Building & Mutual Support',
        'body'     => '<p>Structured group sessions that encourage shared learning, skill building, mutual support, and personal reflection in a guided setting. Contact our practice for current group topics, schedules, eligibility, and availability.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/community-workshop.jpg',
        'inquiry'  => 'Group Psychotherapy',
    ],
    [
        'num'      => '05',
        'slug'     => 'child-and-adolescent-services',
        'title'    => 'Child & Adolescent Services (Ages 5+)',
        'subtitle' => 'Developmentally Responsive Counseling Beginning at Age 5',
        'body'     => '<p>We provide child and adolescent services beginning at age 5. Conditions supported include:</p><ul><li>Attention-Deficit/Hyperactivity Disorder (ADHD) &amp; Autism Spectrum Disorder</li><li>Behavioral Issues &amp; Oppositional Defiance</li><li>Self-Esteem &amp; Coping Skills</li><li>School Issues, Family Conflict &amp; Peer Relationships</li><li>Parenting Support, Emotional Disturbance &amp; Developmental Disorders</li><li>Depression, Anxiety, Trauma and PTSD, Grief &amp; Bipolar Disorder</li></ul>',
        'scope'    => 'Parent or legal guardian involvement and consent are required for minor inquiries and services.',
        'image'    => '/assets/images/placeholders/family-support.jpg',
        'inquiry'  => 'Child & Adolescent Services',
    ],
    [
        'num'      => '06',
        'slug'     => 'just-a-place-to-talk',
        'title'    => 'Just a Place to Talk',
        'subtitle' => 'Confidential, Supportive Conversations for Everyday Life Stressors',
        'body'     => '<p>You do not have to wait for a crisis to seek support. Sometimes people want a confidential, supportive space to talk through everyday stress, personal questions, relationship concerns, life decisions, or emotional fatigue. We welcome clients who simply need a thoughtful place to talk and reflect.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/calm-nature.jpg',
        'inquiry'  => 'A Place to Talk',
    ],
    [
        'num'      => '07',
        'slug'     => 'trauma-informed-support',
        'title'    => 'Trauma-Informed Support',
        'subtitle' => 'Care Grounded in Safety, Trust, Collaboration & Empowerment',
        'body'     => '<p>Care grounded in safety, trust, collaboration, and empowerment, recognizing how past experiences can affect emotional and physical well-being while respecting your readiness and pace.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/hero-sanctuary.jpg',
        'inquiry'  => 'Trauma-Informed Support',
    ],
    [
        'num'      => '08',
        'slug'     => 'substance-use-counseling',
        'title'    => 'Substance-Use Counseling and Recovery Support',
        'subtitle' => 'Person-Centered Counseling for Recovery Goals & Relapse Prevention',
        'body'     => '<p>Supportive, person-centered counseling for individuals navigating substance-use concerns, recovery goals, relapse prevention, and healthier coping strategies.</p>',
        'scope'    => null,
        'image'    => '/assets/images/placeholders/counseling-space.jpg',
        'inquiry'  => 'Substance-Use Counseling & Recovery Support',
    ],
];

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="message-circle-heart"></i>
                <span>Mental Health &amp; Emotional Care</span>
            </span>
            <h1>Psychotherapy and Counseling Services</h1>
            <p class="page-hero-sub">
                Our psychotherapy services offer opportunities to explore concerns, strengthen coping skills, improve relationships, and work toward meaningful change.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/counseling-space.jpg')) ?>"
                 alt="Welcoming psychotherapy room at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="editorial-split" style="margin-bottom: 4.5rem;">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Individualized Care</span>
                <h2 class="section-title">Thoughtful, Collaborative Psychotherapy</h2>
                <div class="gold-divider"></div>
                <p>
                    Our psychotherapy services offer opportunities to explore concerns, strengthen coping skills, improve relationships, and work toward meaningful change.
                </p>
                <p>
                    Whether you are seeking individual reflection, navigating relationship transitions with a partner, working to strengthen family communication, or looking for support for a child or adolescent beginning at age 5, our practice provides a warm and structured setting.
                </p>
                <div class="notice-banner" style="margin-top: 1.5rem; margin-bottom: 0;">
                    <i data-lucide="info" style="color: var(--gold-600); flex-shrink: 0;"></i>
                    <div>
                        <strong>Scope of Services Notice:</strong> We do not offer psychological testing, neuropsychological evaluations, court-ordered evaluations, custody evaluations, or forensic assessments.
                    </div>
                </div>
            </div>

            <div style="border-left: 3px solid var(--gold-500); padding-left: 2.25rem;" data-reveal="fade-left">
                <span class="eyebrow-badge eyebrow-light-bg">Ages 5 and Older</span>
                <h3 style="margin: 0 0 0.65rem; font-size: 1.4rem; color: var(--navy-900);">Child &amp; Adolescent Support (Ages 5+)</h3>
                <p style="margin: 0 0 1rem; color: var(--text-secondary); font-size: 0.95rem;">
                    We provide child and adolescent services beginning at age 5. Conditions supported include:
                </p>
                <div class="populations-pills">
                    <span class="pop-pill">ADHD</span>
                    <span class="pop-pill">Autism Spectrum Disorder</span>
                    <span class="pop-pill">Behavioral Issues</span>
                    <span class="pop-pill">Oppositional Defiance</span>
                    <span class="pop-pill">Self-Esteem</span>
                    <span class="pop-pill">Coping Skills</span>
                    <span class="pop-pill">School Issues</span>
                    <span class="pop-pill">Family Conflict</span>
                    <span class="pop-pill">Peer Relationships</span>
                    <span class="pop-pill">Parenting Support</span>
                    <span class="pop-pill">Emotional Disturbance</span>
                    <span class="pop-pill">Developmental Disorders</span>
                    <span class="pop-pill">Depression &amp; Anxiety</span>
                    <span class="pop-pill">Trauma &amp; PTSD</span>
                    <span class="pop-pill">Grief</span>
                    <span class="pop-pill">Bipolar Disorder</span>
                </div>
            </div>
        </div>

        <div class="chapter-layout">
            <aside class="chapter-rail" aria-label="Psychotherapy Offerings Index">
                <div class="chapter-rail-title">Psychotherapy Chapters</div>
                <ul class="chapter-rail-list">
                    <?php foreach ($psychotherapyServices as $srv): ?>
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
                <?php foreach ($psychotherapyServices as $idx => $srv):
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
                                    <i data-lucide="video" style="width: 15px; height: 15px; vertical-align: text-bottom; color: var(--gold-600);"></i>
                                    Telehealth &amp; In-Person
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
            <span class="eyebrow-badge eyebrow-light-bg">Educational Media</span>
            <h2 class="section-title">Psychotherapy Videos &amp; Resources</h2>
            <div class="gold-divider"></div>
        </div>
        <?php include dirname(__DIR__) . '/includes/video-cards.php'; ?>
    </div>
</section>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
