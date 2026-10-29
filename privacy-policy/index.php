<?php
/**
 * De AnchoredSelf, LLC - Website Privacy Information & Practice Policies (/privacy-policy/)
 * Code-defined layout, hierarchy, and approved content.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'privacy-policy';
$canonicalPath       = '/privacy-policy/';
$pageMetaTitle       = 'Website Privacy Information & Practice Policies | De AnchoredSelf, LLC';
$pageMetaDescription = 'How De AnchoredSelf, LLC handles routine website inquiries, privacy expectations, cancellation policies, and informed consent prior to beginning care.';

$telephone   = Repository::setting('telephone', '443-912-4612');
$officeHours = Repository::setting('office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time');

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div>
            <span class="eyebrow-badge">
                <i data-lucide="shield-check"></i>
                <span>Privacy, Consent &amp; Terms</span>
            </span>
            <h1>Website Privacy Information &amp; Practice Notices</h1>
            <p class="page-hero-sub">
                How De AnchoredSelf, LLC handles routine website inquiries, privacy expectations, and informed consent prior to beginning care.
            </p>
        </div>
        <div class="page-hero-img">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/calm-nature.jpg')) ?>"
                 alt="Calm natural landscape representing trust and privacy"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="inquiry-card" style="max-width: 900px; margin: 0 auto;">
            <div class="prose">
                <h3>1. Routine Website Inquiries &amp; Data Minimization</h3>
                <p>
                    This website provides general information about De AnchoredSelf, LLC and offers a general contact and appointment inquiry form. Please keep any message submitted through this website brief and avoid including sensitive medical, diagnostic, or mental-health history.
                </p>
                <p>
                    Submitting an inquiry through this website does not establish a therapist-client or clinical relationship, nor does it confirm an appointment. Formal privacy notices (including HIPAA Notice of Privacy Practices where applicable), service agreements, and informed consent documents are provided during the formal intake process before clinical or wellness services begin.
                </p>

                <h3>2. Emergency &amp; Crisis Disclaimer</h3>
                <p>
                    Website inquiry forms, email, and voicemail are for routine communication only and are not monitored continuously. If you are experiencing a medical or mental-health emergency, call <strong>911</strong>, go to the nearest hospital emergency department, or call or text <strong>988</strong> to reach the Suicide &amp; Crisis Lifeline.
                </p>

                <h3>3. Appointment Cancellation &amp; Nonrefund Policy</h3>
                <p>
                    We require at least <strong>72 hours’ notice</strong> for appointment cancellations or rescheduling. Payments are nonrefundable. When notice is provided in accordance with practice policy, payments may be applied as a credit toward a future rescheduled service. Missed appointments or late cancellations with less than 72 hours’ notice may forfeit the session fee or credit, as permitted by applicable law and payer rules.
                </p>

                <h3>4. Scope of Services &amp; Educational Content</h3>
                <p>
                    Videos, articles, and wellness resources published on this website are for educational purposes only and do not replace individualized psychotherapy, medical evaluation, or physician care. De AnchoredSelf, LLC does not provide psychological testing, neuropsychological evaluations, court-ordered evaluations, custody evaluations, or forensic assessments.
                </p>
            </div>

            <div class="notice-banner warning" style="margin-top: 2rem; margin-bottom: 0;">
                <i data-lucide="phone-call" style="flex-shrink: 0;"></i>
                <div>
                    <strong>Questions About Privacy or Practice Agreements?</strong>
                    Contact De AnchoredSelf, LLC directly at <strong><?= e($telephone) ?></strong> during office hours (<?= e($officeHours) ?>).
                </div>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
