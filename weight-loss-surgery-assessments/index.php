<?php
/**
 * De AnchoredSelf, LLC - Weight-Loss Surgery Assessments (/weight-loss-surgery-assessments/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero & Editorial Split
 *  - Flowing Journey Timeline for Consultation Focus Areas
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'weight-loss-surgery-assessments';
$canonicalPath       = '/weight-loss-surgery-assessments/';
$pageMetaTitle       = 'Weight-Loss Surgery Assessments & Pain Program Eligibility | De AnchoredSelf, LLC';
$pageMetaDescription = 'Consultations and behavioral-health assessments for individuals preparing for weight-loss surgery or pain management program eligibility at De AnchoredSelf, LLC.';

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="clipboard-check"></i>
                <span>Pre-Surgical &amp; Program Consultations</span>
            </span>
            <h1>Weight-Loss Surgery Assessment Consultations</h1>
            <p class="page-hero-sub">
                Supportive behavioral-health consultations focused on readiness, lifestyle adjustment, support systems, and post-surgical success.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/counseling-space.jpg')) ?>"
                 alt="Consultation room for pre-surgical behavioral health assessments"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="editorial-split">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Behavioral-Health Consultations</span>
                <h2 class="section-title">Preparing for Sustainable Post-Surgical Success</h2>
                <div class="gold-divider"></div>
                <p>
                    Consultations and behavioral-health assessments for individuals preparing for weight-loss surgery, focused on readiness, lifestyle adjustment, support systems, and post-surgical success.
                </p>
                <p>
                    Clients should confirm specific assessment requirements with their surgical program or referring provider.
                </p>

                <h3>Pain Management Program Eligibility Assessments</h3>
                <p>
                    In addition to weight-loss surgery consultations, we provide psychological eligibility assessments for pain management programs when required by a referring provider or program, alongside ongoing behavioral pain-management counseling.
                </p>

                <div class="notice-banner" style="margin-top: 1.5rem;">
                    <i data-lucide="clipboard-list" style="color: var(--gold-600); flex-shrink: 0;"></i>
                    <div>
                        <strong>Confirm Your Program Requirements:</strong> Because surgical centers and pain management programs maintain varying documentation criteria, please verify your specific assessment requirements with your surgical program or referring provider prior to your consultation.
                    </div>
                </div>

                <div style="margin-top: 1.75rem;">
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Weight-Loss+Surgery+Assessment+Consultation#inquiry-form" class="btn btn-primary btn-shimmer">
                        <i data-lucide="calendar-heart"></i>
                        <span>Request an Assessment Consultation</span>
                    </a>
                </div>
            </div>

            <div data-reveal="fade-left">
                <span class="eyebrow-badge eyebrow-light-bg">Consultation Journey</span>
                <h3 style="margin: 0 0 1.5rem; font-size: 1.45rem; color: var(--navy-900);">What Our Consultation Covers</h3>
                <div class="journey-timeline" style="margin-bottom: 2rem;">
                    <div class="journey-step">
                        <h4>01. Readiness &amp; Understanding</h4>
                        <p>Reviewing your goals, expectations, and understanding of pre- and post-surgical behavioral commitments.</p>
                    </div>
                    <div class="journey-step">
                        <h4>02. Lifestyle &amp; Eating Patterns</h4>
                        <p>Exploring daily routines, stress responses, emotional eating patterns, and practical strategies for adjustment.</p>
                    </div>
                    <div class="journey-step">
                        <h4>03. Support Systems &amp; Coping</h4>
                        <p>Identifying emotional support networks and ongoing coping resources to strengthen long-term well-being.</p>
                    </div>
                </div>

                <div class="notice-banner warning" style="margin-bottom: 0;">
                    <i data-lucide="info" style="flex-shrink: 0;"></i>
                    <div>
                        <strong>Scope of Evaluation Notice:</strong> While we provide consultations regarding weight-loss surgery assessments and pain management program psychological eligibility assessments, we do not offer general psychological testing, neuropsychological evaluations, court-ordered evaluations, custody evaluations, or forensic assessments.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
