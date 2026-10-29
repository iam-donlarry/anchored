<?php
/**
 * De AnchoredSelf, LLC - Homepage
 * Redesigned Sanctuary Editorial Concept:
 *  - Architectural Split Hero with Ambient Gold Lighting & Floating Ribbon
 *  - Flowing Lifespan Continuum Timeline (Children 5+, Adolescents, Adults/Older Adults, Couples/Families, Groups)
 *  - Sticky Split-Screen Care Pathways (Numbered Editorial Chapters 01–03)
 *  - Asymmetric Editorial Split ("Our Approach")
 *  - Interactive Service Spotlight Explorer (01–06)
 *  - Optional Dynamic CMS Sections for Videos/Resources and Upcoming Events
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/Repository.php';

$activeNav           = 'home';
$canonicalPath       = '/';
$pageMetaTitle       = 'De AnchoredSelf, LLC | Compassionate Psychotherapy & Whole-Person Wellness in Essex, MD';
$pageMetaDescription = 'A place to talk. A space to heal. Support for moving forward. Compassionate psychotherapy, Christian counseling, and whole-person wellness in Essex, Maryland and via telehealth.';

$telephone   = '443-912-4612';
$officeHours = 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time';
$fullAddress = '403 Eastern Boulevard, Essex, Maryland 21221';

$featuredServices = [
    [
        'num'                   => '01',
        'category_name'         => 'Psychotherapy and Counseling Services',
        'page_route'            => '/psychotherapy/#individual-psychotherapy',
        'title'                 => 'Individual Psychotherapy',
        'subtitle'              => 'Personalized Support for Emotional Healing & Everyday Functioning',
        'short_description'     => 'A supportive space to address anxiety, depression, stress, grief, trauma-related concerns, life transitions, and personal growth. We work with you to understand patterns, strengthen coping strategies, and develop goals that support everyday functioning.',
        'scope_disclaimer'      => 'Available for children ages 5 and older, adolescents, adults, and older adults through late adulthood.',
        'image_path'            => '/assets/images/placeholders/counseling-space.jpg',
        'image_alt'             => 'Calm counseling room with comfortable seating and natural light',
        'inquiry_option_label'  => 'Individual Psychotherapy',
    ],
    [
        'num'                   => '02',
        'category_name'         => 'Psychotherapy and Counseling Services',
        'page_route'            => '/psychotherapy/#couples-psychotherapy',
        'title'                 => 'Couples & Family Psychotherapy',
        'subtitle'              => 'Strengthening Communication, Trust & Family Connection',
        'short_description'     => 'Collaborative support for improving communication, navigating conflict, rebuilding trust, strengthening emotional connection, navigating transitions, and addressing patterns affecting relationship and family well-being.',
        'scope_disclaimer'      => null,
        'image_path'            => '/assets/images/placeholders/couples-connection.jpg',
        'image_alt'             => 'Supportive conversation between partners in a calm setting',
        'inquiry_option_label'  => 'Couples Psychotherapy',
    ],
    [
        'num'                   => '03',
        'category_name'         => 'Psychotherapy and Counseling Services',
        'page_route'            => '/psychotherapy/#child-and-adolescent-services',
        'title'                 => 'Child & Adolescent Services (Ages 5+)',
        'subtitle'              => 'Developmentally Responsive Care for Children & Teens',
        'short_description'     => 'Addressing anxiety, depression, ADHD, Autism Spectrum Disorder, behavioral issues, oppositional defiance, self-esteem, coping skills, school issues, family conflict, peer relationships, parenting support, emotional disturbance, developmental disorders, trauma and PTSD, grief, and bipolar disorder.',
        'scope_disclaimer'      => 'Parent or legal guardian involvement and consent are required for minor inquiries and services.',
        'image_path'            => '/assets/images/placeholders/family-support.jpg',
        'image_alt'             => 'Warm family moment outdoors representing child, adolescent, and family support',
        'inquiry_option_label'  => 'Child & Adolescent Services',
    ],
    [
        'num'                   => '04',
        'category_name'         => 'Christian Counseling',
        'page_route'            => '/christian-counseling/',
        'title'                 => 'Christian Counseling',
        'subtitle'              => 'Optional Faith-Integrated Counseling Available Upon Request',
        'short_description'     => 'Bring your faith into the counseling process. Christian counseling offers space to explore emotional struggles, relationships, grief, forgiveness, purpose, and spiritual growth with optional prayer, biblical reflection, and spiritual discussion.',
        'scope_disclaimer'      => 'Christian counseling is optional; we respect each client’s beliefs and preferences when planning care.',
        'image_path'            => '/assets/images/placeholders/christian-reflection.jpg',
        'image_alt'             => 'Peaceful open book and warm light representing faith-integrated reflection',
        'inquiry_option_label'  => 'Christian Counseling',
    ],
    [
        'num'                   => '05',
        'category_name'         => 'Wellness Services',
        'page_route'            => '/wellness/',
        'title'                 => 'Whole-Person Wellness, Herbalism, Juice Therapy & Pain Support',
        'subtitle'              => 'Connecting Stress, Sleep, Nutrition Habits, Movement & Emotional Well-Being',
        'short_description'     => 'Explore whole-person wellness education, herbalism education, balanced juice therapy guidance, weight-management support, behavioral pain-management counseling, and pain management program psychological eligibility assessments.',
        'scope_disclaimer'      => 'Wellness, herbalism, juice therapy, and weight-management services are educational and supportive in nature and do not replace primary medical care.',
        'image_path'            => '/assets/images/placeholders/wellness-botanical.jpg',
        'image_alt'             => 'Fresh botanical elements and wholesome nourishment representing whole-person wellness',
        'inquiry_option_label'  => 'Whole-Person Wellness',
    ],
    [
        'num'                   => '06',
        'category_name'         => 'Assessments & Clinical Supervision',
        'page_route'            => '/weight-loss-surgery-assessments/',
        'title'                 => 'Weight-Loss Surgery Assessments & Clinical Supervision',
        'subtitle'              => 'Pre-Surgical Behavioral Health Consultations & Counselor Supervision',
        'short_description'     => 'Consultations and behavioral-health assessments for individuals preparing for weight-loss surgery, alongside professional clinical supervision for counselors seeking licensure or consultation with Dr. Emmanuella Owobu, LCPC-S.',
        'scope_disclaimer'      => 'Clients should confirm specific assessment requirements with their surgical program or referring provider.',
        'image_path'            => '/assets/images/placeholders/community-workshop.jpg',
        'image_alt'             => 'Professional consultation and clinical supervision environment',
        'inquiry_option_label'  => 'Weight-Loss Surgery Assessment Consultation',
    ],
];

$welcomeVideo   = Repository::getWelcomeVideo();
$homeResources  = Repository::getVideosByPlacement('home', 3);
if (empty($homeResources)) {
    $homeResources = Repository::getVideosByPlacement('wellness', 2);
}
$upcomingEvents = Repository::getUpcomingEvents(2);

include __DIR__ . '/includes/header.php';
?>

<!-- Architectural Sanctuary Hero -->
<section class="hero-home" aria-label="Welcome to De AnchoredSelf, LLC">
    <div class="container hero-grid">
        <div data-reveal="fade-right">
            <div class="eyebrow-badge">
                <i data-lucide="anchor"></i>
                <span>Grounded in Healing. Growing in Strength. Delivering Hope.</span>
            </div>

            <h1 class="hero-title">
                A place to talk. A space to heal. Support for moving forward.
            </h1>

            <p class="hero-lead">
                Welcome to De AnchoredSelf, LLC, where compassionate psychotherapy and whole-person wellness come together.
            </p>

            <div class="hero-copy-prose">
                <p>
                    Whether you are navigating emotional challenges, strengthening relationships, seeking Christian counseling, living with ongoing pain, or pursuing wellness and weight-management goals, our practice offers a welcoming space to begin.
                </p>
                <p>
                    We provide individual, couples, family, and group psychotherapy, alongside wellness services, herbalism, juice therapy, weight-management support, consultations regarding weight-loss surgery assessments and pain management program psychological eligibility assessment.
                </p>
                <p>
                    Your experiences, culture, beliefs, and personal goals matter. We work collaboratively with you to understand your needs, recognize your strengths, and develop practical steps toward meaningful change.
                </p>
                <p style="margin-bottom: 0;">
                    Sometimes you need structured support. Sometimes you simply need a place to talk. We welcome the opportunity to help you take your next step.
                </p>
            </div>

            <div class="hero-cta-group">
                <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-gold btn-shimmer">
                    <i data-lucide="calendar-heart"></i>
                    <span>Request an Appointment</span>
                </a>
                <a href="<?= e(SITE_BASE_URL) ?>/services/" class="btn btn-outline-light">
                    <i data-lucide="compass"></i>
                    <span>Explore Our Services</span>
                </a>
            </div>

            <div class="hero-meta-strip">
                <span class="hero-meta-item">
                    <i data-lucide="video"></i>
                    <span>Telehealth and in-person appointments available.</span>
                </span>
                <span class="hero-meta-item">
                    <i data-lucide="clock"></i>
                    <span><?= e($officeHours) ?></span>
                </span>
                <span class="hero-meta-item">
                    <i data-lucide="phone"></i>
                    <a href="<?= e(format_tel_href($telephone)) ?>" style="color: var(--gold-400); font-weight: 600; text-decoration: none;"><?= e($telephone) ?></a>
                </span>
            </div>
        </div>

        <div class="hero-visual-frame" data-reveal="scale-in" data-reveal-delay="120">
            <div class="hero-image-card">
                <img src="<?= e(resolve_asset_url('/assets/images/placeholders/hero-sanctuary.jpg')) ?>"
                     alt="Calm, welcoming counseling environment at De AnchoredSelf, LLC"
                     width="680"
                     height="730"
                     fetchpriority="high">
            </div>
            <div class="hero-floating-card">
                <strong>Whole-Person Psychotherapy &amp; Wellness</strong>
                <span>Serving children ages 5 and older, adolescents, adults, couples, and families in Essex, Maryland and via telehealth.</span>
            </div>
        </div>
    </div>
</section>

<!-- Flowing Lifespan Continuum Timeline (Instead of a Boxy Strip) -->
<section class="lifespan-continuum" aria-label="Populations Served">
    <div class="container">
        <div class="continuum-header" data-reveal="fade-up">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--gold-600); display: block;">
                    Lifespan Care Continuum
                </span>
                <h2 style="margin: 0.15rem 0 0; font-size: 1.4rem; color: var(--navy-900);">
                    Who Our Practice Welcomes
                </h2>
            </div>
            <p style="margin: 0; color: var(--text-secondary); font-size: 0.95rem; max-width: 58ch;">
                Individualized care across every stage of life—honoring your experiences, culture, beliefs, and personal goals.
            </p>
        </div>

        <div class="continuum-track">
            <div class="continuum-node" data-reveal="fade-up" data-reveal-delay="0">
                <div class="continuum-dot"><i data-lucide="sprout"></i></div>
                <h3>Children (Ages 5+)</h3>
                <p>ADHD, Autism Spectrum, emotional regulation, school &amp; behavioral support.</p>
            </div>
            <div class="continuum-node" data-reveal="fade-up" data-reveal-delay="80">
                <div class="continuum-dot"><i data-lucide="sparkles"></i></div>
                <h3>Adolescents &amp; Teens</h3>
                <p>Self-esteem, coping skills, peer relationships, anxiety, depression &amp; trauma.</p>
            </div>
            <div class="continuum-node" data-reveal="fade-up" data-reveal-delay="160">
                <div class="continuum-dot"><i data-lucide="user-check"></i></div>
                <h3>Adults &amp; Older Adults</h3>
                <p>Life transitions, stress, grief, chronic pain coping, recovery &amp; personal growth.</p>
            </div>
            <div class="continuum-node" data-reveal="fade-up" data-reveal-delay="240">
                <div class="continuum-dot"><i data-lucide="heart-handshake"></i></div>
                <h3>Couples &amp; Families</h3>
                <p>Communication, conflict resolution, trust concerns &amp; healthier family dynamics.</p>
            </div>
            <div class="continuum-node" data-reveal="fade-up" data-reveal-delay="320">
                <div class="continuum-dot"><i data-lucide="messages-square"></i></div>
                <h3>Group &amp; Supervision</h3>
                <p>Guided group psychotherapy sessions &amp; board-approved LCPC-S supervision.</p>
            </div>
        </div>
    </div>
</section>

<!-- Sticky Split-Screen Care Pathways (Flowing Numbered Chapters instead of 3 Cards) -->
<section class="section section-ivory" aria-labelledby="pillarsHeading">
    <div class="container">
        <div class="care-pathways-split">
            <div class="care-pathways-sticky" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Practice Foundations</span>
                <h2 id="pillarsHeading" class="section-title">Three Connected Pathways to Whole-Person Care</h2>
                <div class="gold-divider"></div>
                <p class="section-subtitle">
                    Your experiences, culture, beliefs, and personal goals matter. We work collaboratively with you to understand your needs, recognize your strengths, and develop practical steps toward meaningful change.
                </p>
                <div class="care-pathways-visual">
                    <img src="<?= e(resolve_asset_url('/assets/images/placeholders/calm-nature.jpg')) ?>"
                         alt="Peaceful natural landscape representing grounded healing and growth"
                         width="560"
                         height="420"
                         loading="lazy">
                </div>
            </div>

            <div class="pathway-stream" data-reveal="fade-left">
                <article class="pathway-chapter">
                    <div class="pathway-num">01</div>
                    <div class="pathway-body">
                        <h3>Psychotherapy &amp; Counseling</h3>
                        <p>
                            We provide individual, couples, family, and group psychotherapy, child and adolescent services beginning at age 5, trauma-informed support, substance-use counseling, and a confidential place to talk through everyday stress.
                        </p>
                        <a href="<?= e(SITE_BASE_URL) ?>/psychotherapy/" class="pathway-link">
                            <span>Explore Psychotherapy Services</span>
                            <i data-lucide="arrow-right" style="width: 17px; height: 17px;"></i>
                        </a>
                    </div>
                </article>

                <article class="pathway-chapter">
                    <div class="pathway-num">02</div>
                    <div class="pathway-body">
                        <h3>Optional Christian Counseling</h3>
                        <p>
                            Christian counseling is available upon request for clients who want their faith integrated into the counseling process alongside clinical counseling approaches. We welcome clients from all backgrounds and respect each person’s beliefs and preferences.
                        </p>
                        <a href="<?= e(SITE_BASE_URL) ?>/christian-counseling/" class="pathway-link">
                            <span>Learn About Christian Counseling</span>
                            <i data-lucide="arrow-right" style="width: 17px; height: 17px;"></i>
                        </a>
                    </div>
                </article>

                <article class="pathway-chapter">
                    <div class="pathway-num">03</div>
                    <div class="pathway-body">
                        <h3>Whole-Person Wellness &amp; Assessments</h3>
                        <p>
                            Explore integrative wellness services including whole-person wellness, behavioral pain-management counseling, herbalism, juice therapy, weight-management support, weight-loss surgery assessment consultations, pain program eligibility assessments, and clinical supervision.
                        </p>
                        <a href="<?= e(SITE_BASE_URL) ?>/wellness/" class="pathway-link">
                            <span>Explore Wellness &amp; Assessments</span>
                            <i data-lucide="arrow-right" style="width: 17px; height: 17px;"></i>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Editorial Split: Our Approach -->
<section class="section section-white" aria-labelledby="ourApproachHeading">
    <div class="container">
        <div class="editorial-split">
            <div class="editorial-media" data-reveal="fade-right">
                <div class="editorial-img-wrapper">
                    <img src="<?= e(resolve_asset_url('/assets/images/placeholders/counseling-space.jpg')) ?>"
                         alt="Warm, inviting seating area for collaborative therapy and wellness discussions"
                         width="620"
                         height="465"
                         loading="lazy">
                </div>
            </div>
            <div class="editorial-content prose" data-reveal="fade-left">
                <span class="eyebrow-badge eyebrow-light-bg">Our Approach</span>
                <h2 id="ourApproachHeading" class="section-title">Care Centered on the Whole Person</h2>
                <div class="gold-divider"></div>
                <p>
                    We recognize that emotional well-being connects with relationships, physical health, daily habits, personal experiences, and spiritual beliefs. Our approach creates room to explore these connections while developing goals that are realistic and meaningful to you.
                </p>
                <p>
                    You do not have to wait for a crisis to seek support. Sometimes people want a confidential, supportive space to talk through everyday stress, personal questions, relationship concerns, life decisions, or emotional fatigue.
                </p>
                <div style="background: var(--ivory-100); border-left: 4px solid var(--gold-500); padding: 1.2rem 1.4rem; border-radius: 0 12px 12px 0; margin: 1.5rem 0;">
                    <strong style="color: var(--navy-900); display: block; margin-bottom: 0.3rem;">Child &amp; Adolescent Support (Ages 5+)</strong>
                    <span style="font-size: 0.93rem;">
                        We provide child and adolescent services beginning at age 5, supporting ADHD, Autism Spectrum Disorder, behavioral issues, oppositional defiance, self-esteem, coping skills, school issues, family conflict, peer relationships, parenting support, depression, anxiety, trauma and PTSD, grief, and bipolar disorder.
                    </span>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.85rem; margin-top: 1.5rem;">
                    <a href="<?= e(SITE_BASE_URL) ?>/about/" class="btn btn-primary btn-shimmer">
                        <span>About Our Practice &amp; Approach</span>
                        <i data-lucide="arrow-right"></i>
                    </a>
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-outline">
                        <span>Request an Appointment</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Service Spotlight Explorer (Replaces 6 Stacked Boxy Cards) -->
<section class="section section-warm" aria-labelledby="featuredServicesHeading">
    <div class="container">
        <div class="section-header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; max-width: 100%; gap: 1.5rem;" data-reveal="fade-up">
            <div style="max-width: 700px;">
                <span class="eyebrow-badge eyebrow-light-bg">Interactive Service Spotlight</span>
                <h2 id="featuredServicesHeading" class="section-title">Explore How We Can Support You</h2>
                <div class="gold-divider"></div>
                <p class="section-subtitle">
                    Select any care area below to preview our approach, scope of care, and appointment options.
                </p>
            </div>
            <a href="<?= e(SITE_BASE_URL) ?>/services/" class="btn btn-primary">
                <span>View Complete Service Directory</span>
                <i data-lucide="arrow-right"></i>
            </a>
        </div>

        <div class="spotlight-explorer" data-reveal="fade-up">
            <div class="spotlight-nav" role="tablist" aria-label="Practice Service Areas">
                <?php foreach ($featuredServices as $idx => $svc):
                    $paneId = 'spotlight-pane-' . ($idx + 1);
                    $active = ($idx === 0);
                ?>
                    <button type="button"
                            class="spotlight-tab js-spotlight-tab <?= $active ? 'is-active' : '' ?>"
                            role="tab"
                            aria-selected="<?= $active ? 'true' : 'false' ?>"
                            aria-controls="<?= e($paneId) ?>"
                            data-target="<?= e($paneId) ?>">
                        <span class="spotlight-tab-left">
                            <span class="spotlight-tab-num"><?= e($svc['num']) ?></span>
                            <span class="spotlight-tab-title"><?= e($svc['title']) ?></span>
                        </span>
                        <i data-lucide="arrow-right" class="spotlight-tab-arrow" style="width: 18px; height: 18px;"></i>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="spotlight-stage">
                <?php foreach ($featuredServices as $idx => $svc):
                    $paneId = 'spotlight-pane-' . ($idx + 1);
                    $active = ($idx === 0);
                ?>
                    <article class="spotlight-pane js-spotlight-pane <?= $active ? 'is-active' : '' ?>"
                             id="<?= e($paneId) ?>"
                             role="tabpanel">
                        <div class="spotlight-media">
                            <img src="<?= e(resolve_asset_url($svc['image_path'])) ?>"
                                 alt="<?= e($svc['image_alt']) ?>"
                                 width="760"
                                 height="400"
                                 loading="lazy">
                            <span class="spotlight-category-pill"><?= e($svc['category_name']) ?></span>
                        </div>
                        <div class="spotlight-content">
                            <h3><?= e($svc['title']) ?></h3>
                            <div class="spotlight-sub"><?= e($svc['subtitle']) ?></div>
                            <p style="margin: 0; color: var(--text-secondary); font-size: 1rem;">
                                <?= e($svc['short_description']) ?>
                            </p>
                            <?php if (!empty($svc['scope_disclaimer'])): ?>
                                <div class="service-scope-callout">
                                    <strong>Scope Note:</strong> <?= e($svc['scope_disclaimer']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="spotlight-actions">
                                <a href="<?= e(SITE_BASE_URL . $svc['page_route']) ?>" class="pathway-link">
                                    <span>Explore Full Details</span>
                                    <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                                </a>
                                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=<?= urlencode($svc['inquiry_option_label']) ?>#inquiry-form" class="btn btn-primary btn-shimmer">
                                    <i data-lucide="calendar-heart"></i>
                                    <span>Request an Appointment</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic CMS Section: Welcome Video & Educational Resources -->
<?php
$videoItems = [];
if ($welcomeVideo) {
    $videoItems[] = $welcomeVideo;
}
foreach ($homeResources as $hr) {
    if (!$welcomeVideo || (int)$hr['id'] !== (int)$welcomeVideo['id']) {
        $videoItems[] = $hr;
    }
}
if (!empty($videoItems)):
?>
<section class="section section-white" aria-labelledby="welcomeMediaHeading">
    <div class="container" data-reveal="fade-up">
        <div class="section-header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; max-width: 100%; gap: 1.5rem;">
            <div style="max-width: 720px;">
                <span class="eyebrow-badge eyebrow-light-bg">Videos &amp; Practical Education</span>
                <h2 id="welcomeMediaHeading" class="section-title">Practice Introduction &amp; Educational Resources</h2>
                <div class="gold-divider"></div>
                <p class="section-subtitle">
                    Explore conversations, encouragement, and practical education from De AnchoredSelf, LLC. Video content is educational. Individual recommendations and treatment decisions are discussed during your appointments.
                </p>
            </div>
            <a href="<?= e(SITE_BASE_URL) ?>/resources/" class="btn btn-outline">
                <span>Browse All Videos &amp; Resources</span>
                <i data-lucide="play-circle"></i>
            </a>
        </div>

        <?php include __DIR__ . '/includes/video-cards.php'; ?>
    </div>
</section>
<?php endif; ?>

<!-- Dynamic CMS Section: Upcoming Events -->
<section class="section section-ivory" aria-labelledby="homeEventsHeading">
    <div class="container" data-reveal="fade-up">
        <div class="section-header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; max-width: 100%; gap: 1.5rem;">
            <div style="max-width: 700px;">
                <span class="eyebrow-badge eyebrow-light-bg">Community Connection</span>
                <h2 id="homeEventsHeading" class="section-title">Upcoming Workshops &amp; Educational Events</h2>
                <div class="gold-divider"></div>
                <p class="section-subtitle">
                    Stay connected with De AnchoredSelf, LLC. Explore upcoming workshops, wellness sessions, educational presentations, group events, and community gatherings.
                </p>
            </div>
            <a href="<?= e(SITE_BASE_URL) ?>/events/" class="btn btn-outline">
                <span>View Events &amp; Gallery</span>
                <i data-lucide="calendar"></i>
            </a>
        </div>

        <?php if (!empty($upcomingEvents)): ?>
            <?php foreach ($upcomingEvents as $ev):
                $ts = strtotime((string)$ev['event_date']);
            ?>
                <article class="event-card">
                    <div class="event-media">
                        <img src="<?= e(resolve_asset_url($ev['image_path'])) ?>" alt="<?= e($ev['image_alt'] ?: $ev['title']) ?>" width="320" height="220" loading="lazy">
                        <?php if ($ts): ?>
                            <div class="event-date-pill">
                                <span class="month"><?= e(date('M', $ts)) ?></span>
                                <span class="day"><?= e(date('d', $ts)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-content">
                        <div>
                            <span class="eyebrow-badge eyebrow-light-bg" style="margin-bottom: 0.5rem;"><?= e($ev['format']) ?> Event</span>
                            <h3 style="margin: 0 0 0.4rem; font-size: 1.3rem; color: var(--navy-900);"><?= e($ev['title']) ?></h3>
                            <div class="event-meta-list">
                                <span class="event-meta-item">
                                    <i data-lucide="calendar"></i>
                                    <span><?= $ts ? e(date('F j, Y', $ts)) : e($ev['event_date']) ?></span>
                                </span>
                                <span class="event-meta-item">
                                    <i data-lucide="clock"></i>
                                    <span><?= e($ev['event_time']) ?> (<?= e($ev['timezone']) ?>)</span>
                                </span>
                                <span class="event-meta-item">
                                    <i data-lucide="map-pin"></i>
                                    <span><?= e($ev['location']) ?></span>
                                </span>
                            </div>
                            <div class="prose">
                                <?= $ev['description'] ?>
                            </div>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
                            <span style="font-size: 0.88rem; color: var(--text-muted);">
                                <?php if (!empty($ev['cost_info'])): ?>
                                    <strong>Details:</strong> <?= e($ev['cost_info']) ?>
                                <?php endif; ?>
                            </span>
                            <?php if (!empty($ev['external_registration_url'])): ?>
                                <a href="<?= e($ev['external_registration_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="padding: 0.55rem 1.15rem; font-size: 0.88rem;">
                                    <span><?= e($ev['registration_button_label'] ?: 'External Registration') ?></span>
                                    <i data-lucide="external-link"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Group+Psychotherapy#inquiry-form" class="btn btn-outline" style="padding: 0.55rem 1.15rem; font-size: 0.88rem;">
                                    <span>Inquire About Event</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="notice-banner">
                <i data-lucide="calendar"></i>
                <span>Contact our practice at <?= e($telephone) ?> for upcoming workshop and community event schedules.</span>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
