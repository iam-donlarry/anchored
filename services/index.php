<?php
/**
 * De AnchoredSelf, LLC - Complete Services Overview (/services/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Sticky Chapter Rail Navigation paired with Alternating Editorial Tapestry Streams
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'services';
$canonicalPath       = '/services/';
$pageMetaTitle       = 'Explore Our Services | Psychotherapy, Christian Counseling & Wellness | De AnchoredSelf, LLC';
$pageMetaDescription = 'Explore psychotherapy, Christian counseling, child and adolescent support (ages 5+), whole-person wellness, weight-loss surgery assessment consultations, and clinical supervision at De AnchoredSelf, LLC.';

$serviceChapters = [
    [
        'num'     => '01',
        'id'      => 'individual-psychotherapy',
        'group'   => 'Psychotherapy & Counseling',
        'title'   => 'Individual Psychotherapy',
        'sub'     => 'Anxiety, Depression, Stress, Grief, Trauma & Personal Growth',
        'desc'    => 'A supportive space to address anxiety, depression, stress, grief, trauma-related concerns, life transitions, and personal growth. We work collaboratively with you to understand patterns, strengthen coping skills, and develop practical steps toward meaningful change.',
        'route'   => '/psychotherapy/#individual-psychotherapy',
        'img'     => '/assets/images/placeholders/counseling-space.jpg',
        'inq'     => 'Individual Psychotherapy',
    ],
    [
        'num'     => '02',
        'id'      => 'couples-family-psychotherapy',
        'group'   => 'Psychotherapy & Counseling',
        'title'   => 'Couples, Family & Group Psychotherapy',
        'sub'     => 'Communication, Conflict Resolution, Family Dynamics & Shared Learning',
        'desc'    => 'Support for couples navigating communication or trust concerns, collaborative family therapy to foster healthier family dynamics, and structured group psychotherapy sessions that encourage shared learning and mutual support.',
        'route'   => '/psychotherapy/#couples-psychotherapy',
        'img'     => '/assets/images/placeholders/couples-connection.jpg',
        'inq'     => 'Couples Psychotherapy',
    ],
    [
        'num'     => '03',
        'id'      => 'child-adolescent-services',
        'group'   => 'Psychotherapy & Counseling (Ages 5+)',
        'title'   => 'Child & Adolescent Services (Ages 5+)',
        'sub'     => 'Developmentally Responsive Counseling Beginning at Age 5',
        'desc'    => 'We provide child and adolescent services beginning at age 5, supporting ADHD, Autism Spectrum Disorder, behavioral issues, oppositional defiance, self-esteem, coping skills, school issues, family conflict, peer relationships, parenting support, emotional disturbance, developmental disorders, depression, anxiety, trauma and PTSD, grief, and bipolar disorder.',
        'route'   => '/psychotherapy/#child-and-adolescent-services',
        'img'     => '/assets/images/placeholders/family-support.jpg',
        'inq'     => 'Child & Adolescent Services',
    ],
    [
        'num'     => '04',
        'id'      => 'place-to-talk-trauma-recovery',
        'group'   => 'Psychotherapy & Counseling',
        'title'   => 'Just a Place to Talk, Trauma-Informed & Substance-Use Support',
        'sub'     => 'Everyday Reflection, Safety-Centered Trauma Care & Recovery Counseling',
        'desc'    => 'You do not have to wait for a crisis to seek support. Whether you want a confidential place to talk through everyday stress, trauma-informed support grounded in safety and empowerment, or person-centered substance-use counseling and relapse prevention, we welcome you.',
        'route'   => '/psychotherapy/#just-a-place-to-talk',
        'img'     => '/assets/images/placeholders/calm-nature.jpg',
        'inq'     => 'A Place to Talk',
    ],
    [
        'num'     => '05',
        'id'      => 'christian-counseling',
        'group'   => 'Faith-Integrated Care Option',
        'title'   => 'Christian Counseling (Available Upon Request)',
        'sub'     => 'Christian Principles & Spiritual Reflection Alongside Clinical Care',
        'desc'    => 'Christian counseling is available for clients who want their faith integrated into the counseling process. Sessions may incorporate Christian principles, spiritual reflection, and faith-informed coping practices alongside clinical counseling approaches, always guided by your preferences.',
        'route'   => '/christian-counseling/',
        'img'     => '/assets/images/placeholders/christian-reflection.jpg',
        'inq'     => 'Christian Counseling',
    ],
    [
        'num'     => '06',
        'id'      => 'wellness-herbalism-juice',
        'group'   => 'Whole-Person Wellness',
        'title'   => 'Whole-Person Wellness, Herbalism & Juice Therapy',
        'sub'     => 'Lifestyle Habits, Informed Herbal Consultations & Nutrition-Conscious Juicing',
        'desc'    => 'Integrative wellness guidance focused on lifestyle habits, stress management, self-care routines, educational herbalism consultations, and nutrition-conscious juice therapy education as part of a balanced lifestyle.',
        'route'   => '/wellness/#whole-person-wellness',
        'img'     => '/assets/images/placeholders/wellness-botanical.jpg',
        'inq'     => 'Whole-Person Wellness',
    ],
    [
        'num'     => '07',
        'id'      => 'pain-weight-assessments',
        'group'   => 'Pain, Weight & Pre-Surgical Consultations',
        'title'   => 'Behavioral Pain Support, Weight Management & Surgery Assessments',
        'sub'     => 'Chronic Pain Coping, Sustainable Weight Habits & Pre-Surgical Readiness',
        'desc'    => 'Supportive counseling for the emotional and behavioral impact of chronic pain, psychological eligibility assessments for pain management programs, personalized weight-management support, and behavioral-health consultations for individuals preparing for weight-loss surgery.',
        'route'   => '/weight-loss-surgery-assessments/',
        'img'     => '/assets/images/placeholders/herbal-tea.jpg',
        'inq'     => 'Weight-Loss Surgery Assessment Consultation',
    ],
    [
        'num'     => '08',
        'id'      => 'clinical-supervision',
        'group'   => 'Professional Counselor Development',
        'title'   => 'Clinical Supervision (LCPC-S)',
        'sub'     => 'Ethical Decision-Making, Case Conceptualization & Reflective Practice',
        'desc'    => 'Dr. Emmanuella Owobu, LCPC-S, provides clinical supervision for counselors seeking licensure or professional consultation in accordance with state board requirements.',
        'route'   => '/clinical-supervision/',
        'img'     => '/assets/images/placeholders/community-workshop.jpg',
        'inq'     => 'Clinical Supervision',
    ],
];

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="compass"></i>
                <span>Complete Service Directory</span>
            </span>
            <h1>Explore Our Psychotherapy, Wellness &amp; Supervision Services</h1>
            <p class="page-hero-sub">
                De AnchoredSelf, LLC welcomes children ages 5 and older, adolescents, adults, couples, and families. Navigate our eight care chapters below or request an appointment.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/hero-sanctuary.jpg')) ?>"
                 alt="Welcoming therapy and wellness space at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="notice-banner" data-reveal="fade-up" style="margin-bottom: 3.5rem;">
            <i data-lucide="info" style="color: var(--gold-600); flex-shrink: 0;"></i>
            <div>
                <strong>Scope of Services Notice:</strong> We do not offer psychological testing, neuropsychological evaluations, court-ordered evaluations, custody evaluations, or forensic assessments. Wellness, herbalism, juice therapy, and weight-management services are educational and supportive in nature and do not replace primary medical care.
            </div>
        </div>

        <div class="chapter-layout">
            <!-- Sticky Left Chapter Index Rail -->
            <aside class="chapter-rail" aria-label="Service Chapters Navigation">
                <div class="chapter-rail-title">Service Chapters</div>
                <ul class="chapter-rail-list">
                    <?php foreach ($serviceChapters as $ch): ?>
                        <li>
                            <a href="#<?= e($ch['id']) ?>" class="chapter-rail-link">
                                <span class="num"><?= e($ch['num']) ?></span>
                                <span><?= e($ch['title']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- Flowing Alternating Editorial Tapestry Stream -->
            <div class="tapestry-stream">
                <?php foreach ($serviceChapters as $idx => $ch):
                    $isReverse = ($idx % 2 === 1);
                ?>
                    <article class="tapestry-row <?= $isReverse ? 'reverse' : '' ?>" id="<?= e($ch['id']) ?>" data-reveal="fade-up">
                        <div class="tapestry-text">
                            <div class="tapestry-watermark"><?= e($ch['num']) ?></div>
                            <span class="eyebrow-badge eyebrow-light-bg" style="margin-bottom: 0.65rem;"><?= e($ch['group']) ?></span>
                            <h2 style="margin: 0 0 0.35rem; font-size: 1.65rem; color: var(--navy-900);"><?= e($ch['title']) ?></h2>
                            <div style="font-size: 0.96rem; font-weight: 600; color: var(--gold-600); margin-bottom: 0.9rem;"><?= e($ch['sub']) ?></div>
                            <p style="margin: 0 0 1.5rem; color: var(--text-secondary);">
                                <?= e($ch['desc']) ?>
                            </p>
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;">
                                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=<?= urlencode($ch['inq']) ?>#inquiry-form" class="btn btn-primary btn-shimmer">
                                    <i data-lucide="calendar-heart"></i>
                                    <span>Request an Appointment</span>
                                </a>
                                <a href="<?= e(SITE_BASE_URL . $ch['route']) ?>" class="pathway-link">
                                    <span>Visit Dedicated Page</span>
                                    <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                                </a>
                            </div>
                        </div>

                        <div class="tapestry-visual">
                            <div class="tapestry-img-frame">
                                <img src="<?= e(resolve_asset_url($ch['img'])) ?>"
                                     alt="<?= e($ch['title']) ?>"
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

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
