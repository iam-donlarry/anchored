<?php
/**
 * De AnchoredSelf, LLC - About Our Practice (/about/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero with Scroll Reveal
 *  - Editorial Split Narrative
 *  - Continuous Architectural Manifesto Ribbon (Mission, Vision, Values) instead of 3 boxy cards
 *  - Editorial Monograph Layout for Our Approach & Provider Experience
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'about';
$canonicalPath       = '/about/';
$pageMetaTitle       = 'About Our Practice & Our Approach | De AnchoredSelf, LLC';
$pageMetaDescription = 'Learn about our mission, vision, values, and whole-person approach to psychotherapy, Christian counseling, and wellness at De AnchoredSelf, LLC in Essex, Maryland.';

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="anchor"></i>
                <span>About De AnchoredSelf, LLC</span>
            </span>
            <h1>Compassionate Care Centered on the Needs of the Whole Person</h1>
            <p class="page-hero-sub">
                We recognize that emotional well-being connects with relationships, physical health, daily habits, personal experiences, and spiritual beliefs.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/counseling-space.jpg')) ?>"
                 alt="Calm, welcoming practice space at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<!-- Practice Overview & Whole-Person Philosophy -->
<section class="section section-white">
    <div class="container">
        <div class="editorial-split">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">About Our Practice</span>
                <h2 class="section-title">Psychotherapy &amp; Wellness Centered on the Whole Person</h2>
                <div class="gold-divider"></div>
                <p>
                    De AnchoredSelf, LLC provides psychotherapy and wellness services centered on the needs of the whole person.
                </p>
                <p>
                    We recognize that emotional well-being connects with relationships, physical health, daily habits, personal experiences, and spiritual beliefs. Our approach creates room to explore these connections while developing goals that are realistic and meaningful to you.
                </p>
                <p>
                    We welcome individuals, couples, and families seeking support, as well as people interested in the connection and shared learning available through group therapy.
                </p>
                <p>
                    Christian counseling is available for those who wish to incorporate faith into their care.
                </p>

                <div class="notice-banner" style="margin-top: 1.5rem;">
                    <i data-lucide="heart-handshake" style="color: var(--gold-600); flex-shrink: 0;"></i>
                    <div>
                        <strong>Respecting Your Beliefs &amp; Preferences:</strong>
                        “Christian counseling is available upon request. We respect each client’s beliefs and preferences when planning care.”
                    </div>
                </div>
            </div>

            <div class="editorial-media" data-reveal="fade-left">
                <div class="editorial-img-wrapper">
                    <img src="<?= e(resolve_asset_url('/assets/images/placeholders/hero-sanctuary.jpg')) ?>"
                         alt="Welcoming therapeutic environment designed for comfort and reflection"
                         width="600"
                         height="450"
                         loading="lazy">
                </div>
                <div style="background: var(--navy-900); color: #FFFFFF; padding: 1.65rem; border-radius: var(--radius-md); margin-top: 1.35rem; border-left: 4px solid var(--gold-500);">
                    <h3 style="margin: 0 0 0.4rem; font-size: 1.12rem; color: var(--gold-400);">Populations Served</h3>
                    <p style="margin: 0; font-size: 0.94rem; color: #E2EBF3;">
                        De AnchoredSelf, LLC serves <strong>children ages 5 and older, adolescents, adults, and older adults through late adulthood</strong>—providing individual, couples, family, and group psychotherapy alongside whole-person wellness services.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Continuous Architectural Manifesto Ribbon: Mission, Vision, Values -->
<section class="section section-ivory" aria-labelledby="mvvHeading">
    <div class="container">
        <div class="section-header centered" data-reveal="fade-up">
            <span class="eyebrow-badge eyebrow-light-bg">Practice Foundations</span>
            <h2 id="mvvHeading" class="section-title">Our Mission, Vision &amp; Values</h2>
            <div class="gold-divider"></div>
        </div>

        <div class="manifesto-ribbon" data-reveal="fade-up">
            <div class="manifesto-col">
                <div class="manifesto-roman">Pillar I</div>
                <h3><i data-lucide="compass"></i> Mission</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    To provide compassionate, culturally responsive psychotherapy and wellness services that support emotional healing, strengthen resilience, and help people move toward greater well-being.
                </p>
            </div>

            <div class="manifesto-col">
                <div class="manifesto-roman">Pillar II</div>
                <h3><i data-lucide="eye"></i> Vision</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    To create a welcoming practice where people feel heard, supported, and empowered to pursue meaningful growth in their lives and relationships.
                </p>
            </div>

            <div class="manifesto-col">
                <div class="manifesto-roman">Pillar III</div>
                <h3><i data-lucide="shield-check"></i> Values</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    Compassion, respect, integrity, collaboration, cultural responsiveness, and hope guide our work. We honor each person’s dignity and support care that reflects their individual needs and values.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Editorial Monograph: Our Approach & Provider Experience -->
<section class="section section-white" aria-labelledby="approachProviderHeading">
    <div class="container">
        <div class="care-pathways-split" data-reveal="fade-up">
            <div>
                <span class="eyebrow-badge eyebrow-light-bg">Our Approach &amp; Provider Experience</span>
                <h2 id="approachProviderHeading" class="section-title" style="margin-bottom: 0.45rem;">
                    Dr. Emmanuella Owobu, PhD, DFM, LCPC-S, NCC, CCMHC, CAC-AD
                </h2>
                <p style="margin: 0 0 1.25rem; font-weight: 600; color: var(--gold-600); font-size: 1.05rem;">
                    Psychotherapy, Whole-Person Wellness &amp; Clinical Supervision
                </p>
                <div class="gold-divider"></div>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Guiding the practice’s commitment to compassionate, individualized, and collaborative care across emotional health, relationships, and whole-person balance.
                </p>
                <div class="populations-pills">
                    <span class="pop-pill"><i data-lucide="check"></i> Psychotherapy &amp; Counseling</span>
                    <span class="pop-pill"><i data-lucide="check"></i> Substance-Use Recovery Support</span>
                    <span class="pop-pill"><i data-lucide="check"></i> LCPC-S Clinical Supervision</span>
                    <span class="pop-pill"><i data-lucide="check"></i> Functional Medicine &amp; Herbalism</span>
                    <span class="pop-pill"><i data-lucide="check"></i> Juice Therapy &amp; Wellness</span>
                    <span class="pop-pill"><i data-lucide="check"></i> Optional Christian Counseling</span>
                </div>
            </div>

            <div class="prose" style="border-left: 2px solid var(--gold-500); padding-left: 2.25rem;">
                <p>
                    Dr. Emmanuella Owobu, PhD, DFM, LCPC-S, NCC, CCMHC, CAC-AD, brings experience across psychotherapy, substance-use counseling, clinical supervision, and the professional development of counselors.
                </p>
                <p>
                    Her clinical experience includes supporting people experiencing anxiety, depression, trauma-related concerns, grief, relationship difficulties, and significant life transitions. Her background in clinical supervision and professional education informs her commitment to thoughtful care, ethical practice, and continued growth.
                </p>
                <p>
                    Dr. Emma’s training also includes functional medicine, herbalism, and juice therapy, reflecting her interest in the relationship between emotional health and whole-person wellness.
                </p>
                <p>
                    As a licensed minister, she offers Christian counseling for individuals who want faith integrated into their therapeutic work. Her approach respects each client’s preferences, cultural background, and personal beliefs, guiding our practice’s commitment to compassionate, individualized, and collaborative services.
                </p>

                <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 2rem;">
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-primary btn-shimmer">
                        <i data-lucide="calendar-heart"></i>
                        <span>Request an Appointment</span>
                    </a>
                    <a href="<?= e(SITE_BASE_URL) ?>/services/" class="btn btn-outline">
                        <i data-lucide="compass"></i>
                        <span>Explore Our Services</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
