<?php
/**
 * De AnchoredSelf, LLC - Clinical Supervision (/clinical-supervision/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero & Editorial Split
 *  - Flowing Journey Timeline & Focus Pillars
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'clinical-supervision';
$canonicalPath       = '/clinical-supervision/';
$pageMetaTitle       = 'Clinical Supervision for Counselors | De AnchoredSelf, LLC';
$pageMetaDescription = 'Clinical supervision for counselors seeking licensure or professional consultation in Maryland with Dr. Emmanuella Owobu, LCPC-S.';

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="award"></i>
                <span>Professional Development for Counselors</span>
            </span>
            <h1>Clinical Supervision</h1>
            <p class="page-hero-sub">
                Reflective, ethical clinical supervision for counselors pursuing licensure and ongoing professional growth.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/community-workshop.jpg')) ?>"
                 alt="Professional clinical supervision and counselor mentorship at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="editorial-split">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Mentorship &amp; Licensure Support</span>
                <h2 class="section-title">Supporting the Next Generation of Counselors</h2>
                <div class="gold-divider"></div>
                <p>
                    Dr. Emmanuella Owobu, LCPC-S, provides clinical supervision for counselors seeking licensure or professional consultation in accordance with applicable state board requirements.
                </p>
                <p>
                    Supervision focuses on ethical decision-making, clinical skill development, case conceptualization, cultural responsiveness, professional identity, and reflective practice.
                </p>
                <p>
                    Grounded in experience across psychotherapy, substance-use counseling, clinical supervision, and the professional development of counselors, supervision sessions offer a constructive environment where clinicians can sharpen their clinical judgment while maintaining ethical standards of care.
                </p>

                <div style="margin-top: 1.75rem;">
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Clinical+Supervision#inquiry-form" class="btn btn-primary btn-shimmer">
                        <i data-lucide="send"></i>
                        <span>Inquire About Clinical Supervision</span>
                    </a>
                </div>
            </div>

            <div data-reveal="fade-left">
                <span class="eyebrow-badge eyebrow-light-bg">Supervision Pillars</span>
                <h3 style="margin: 0 0 1.5rem; font-size: 1.45rem; color: var(--navy-900);">Core Supervision Focus Areas</h3>
                <div class="journey-timeline">
                    <div class="journey-step">
                        <h4>Ethical Decision-Making &amp; Boundaries</h4>
                        <p>Navigating complex clinical ethics, informed consent, documentation standards, and state board regulations.</p>
                    </div>
                    <div class="journey-step">
                        <h4>Case Conceptualization &amp; Clinical Skill</h4>
                        <p>Strengthening diagnostic formulation, collaborative treatment planning, and evidence-informed therapeutic interventions.</p>
                    </div>
                    <div class="journey-step">
                        <h4>Cultural Responsiveness &amp; Reflective Practice</h4>
                        <p>Cultivating professional identity, self-reflection, and culturally responsive care across diverse client populations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
