<?php
/**
 * De AnchoredSelf, LLC - Appointments, Membership & FAQ (/membership-faq/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Continuous Architectural Manifesto Ribbon for Practice Essentials (Appointments, Fees/Membership, 72-Hour Policy)
 *  - Interactive Accessible FAQ Reader with Scroll Reveal
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'membership-faq';
$canonicalPath       = '/membership-faq/';
$pageMetaTitle       = 'Appointments, Membership & FAQ | De AnchoredSelf, LLC';
$pageMetaDescription = 'Appointment availability, membership options, insurance, self-pay, 72-hour cancellation policy, and frequently asked questions at De AnchoredSelf, LLC.';

$telephone   = Repository::setting('telephone', '443-912-4612');
$fullAddress = Repository::setting('full_address', '403 Eastern Boulevard, Essex, Maryland 21221');
$officeHours = Repository::setting('office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time');

$faqs = [
    [
        'question' => 'What services does De AnchoredSelf, LLC offer?',
        'answer'   => '<p>We offer individual, couples, family, and group psychotherapy, Christian counseling upon request, behavioral pain-management counseling, herbalism, juice therapy, weight-management support, consultations for weight-loss surgery assessments, pain management program psychological eligibility assessments, and clinical supervision.</p>',
    ],
    [
        'question' => 'Do you offer services for children and adolescents?',
        'answer'   => '<p>Yes. We provide child and adolescent services beginning at age 5. Conditions supported include ADHD, Autism Spectrum Disorder, behavioral issues, oppositional defiance, self-esteem, coping skills, school issues, family conflict, peer relationships, parenting support, emotional disturbance, developmental disorders, depression, anxiety, trauma and PTSD, grief, and bipolar disorder.</p>',
    ],
    [
        'question' => 'Do I need to be in crisis to schedule an appointment?',
        'answer'   => '<p>No. While we support clients through significant challenges, we also welcome individuals who simply want a confidential, supportive place to talk through everyday stress, life transitions, relationship concerns, or personal goals.</p>',
    ],
    [
        'question' => 'Is Christian counseling required?',
        'answer'   => '<p>No. Christian counseling is available for clients who request it. We respect each client’s beliefs, values, and preferences.</p>',
    ],
    [
        'question' => 'Do you offer telehealth and in-person appointments?',
        'answer'   => '<p>Yes. We offer both telehealth and in-person appointments.</p>',
    ],
    [
        'question' => 'What are your hours of operation?',
        'answer'   => '<p>Appointments are available Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time.</p>',
    ],
    [
        'question' => 'Do you accept insurance or self-pay?',
        'answer'   => '<p>We accept select insurance plans and self-pay. Please contact our office to verify your coverage or discuss payment options.</p>',
    ],
    [
        'question' => 'Where can I find pricing or membership rates?',
        'answer'   => '<p>Service rates and membership options are shared directly during your inquiry or intake process so we can provide accurate information based on the services you need.</p>',
    ],
    [
        'question' => 'Do you provide weight-loss surgery assessments and pain management program eligibility assessments?',
        'answer'   => '<p>Yes. We offer consultations and behavioral-health assessments for individuals preparing for weight-loss surgery and psychological eligibility assessments for pain management programs. Please check with your surgical program or referring provider regarding any specific documentation requirements.</p>',
    ],
    [
        'question' => 'Do you offer psychological testing or court-ordered evaluations?',
        'answer'   => '<p>No. We do not provide psychological testing, neuropsychological evaluations, court-ordered evaluations, custody evaluations, or forensic assessments.</p>',
    ],
    [
        'question' => 'What is your cancellation and refund policy?',
        'answer'   => '<p>We require at least 72 hours’ notice for appointment cancellations or rescheduling. Payments are nonrefundable. Approved changes made within policy guidelines may be applied as a credit toward a future rescheduled appointment.</p>',
    ],
    [
        'question' => 'What should I do in an emergency?',
        'answer'   => '<p>If you are experiencing a medical or mental health emergency, call 911, go to the nearest emergency room, or call or text 988 to reach the Suicide &amp; Crisis Lifeline.</p>',
    ],
];

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="help-circle"></i>
                <span>Practice Policies &amp; Client Information</span>
            </span>
            <h1>Appointments, Membership, and Frequently Asked Questions</h1>
            <p class="page-hero-sub">
                Everything you need to know about scheduling, telehealth and in-person availability, insurance and membership options, and practice policies.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/hero-sanctuary.jpg')) ?>"
                 alt="Welcoming therapy and wellness environment at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<!-- Continuous Architectural Manifesto Ribbon instead of 3 Boxy Cards -->
<section class="section section-white">
    <div class="container">
        <div class="manifesto-ribbon" data-reveal="fade-up">
            <div class="manifesto-col">
                <div class="manifesto-roman">01 / Scheduling</div>
                <h3><i data-lucide="calendar-clock"></i> Appointments &amp; Location</h3>
                <p style="color: var(--text-secondary);">We offer both telehealth and in-person appointments.</p>
                <p style="font-size: 0.92rem; margin-bottom: 0.45rem; color: var(--text-secondary);"><strong>Hours:</strong> <?= e($officeHours) ?></p>
                <p style="font-size: 0.92rem; margin-bottom: 0.45rem; color: var(--text-secondary);"><strong>Office:</strong> <?= e($fullAddress) ?></p>
                <p style="font-size: 0.92rem; margin-bottom: 0; color: var(--text-secondary);"><strong>Phone:</strong> <a href="<?= e(format_tel_href($telephone)) ?>" style="font-weight: 600;"><?= e($telephone) ?></a></p>
            </div>

            <div class="manifesto-col">
                <div class="manifesto-roman">02 / Financial Care</div>
                <h3><i data-lucide="shield-check"></i> Fees &amp; Membership Options</h3>
                <p style="color: var(--text-secondary);">De AnchoredSelf, LLC offers flexible care options, including individual service sessions and wellness or support membership options.</p>
                <p style="font-size: 0.92rem; color: var(--text-secondary);">Because needs vary from person to person, service rates and membership details are shared during your consultation or inquiry process.</p>
                <p style="font-size: 0.92rem; margin-bottom: 0; color: var(--text-secondary);"><strong>Insurance &amp; Self-Pay:</strong> We accept select insurance plans as well as self-pay. Contact us for current rate information, membership details, and insurance verification.</p>
            </div>

            <div class="manifesto-col">
                <div class="manifesto-roman">03 / Practice Policy</div>
                <h3><i data-lucide="file-check-2"></i> Cancellation &amp; Refund Policy</h3>
                <p style="color: var(--text-secondary);">We require at least <strong>72 hours’ notice</strong> for appointment cancellations or rescheduling.</p>
                <p style="font-size: 0.92rem; color: var(--text-secondary);">Payments are nonrefundable. When notice is provided in accordance with practice policy, payments may be applied as a credit toward a future rescheduled service.</p>
                <p style="font-size: 0.92rem; margin-bottom: 0; color: var(--text-secondary);">Missed appointments or late cancellations with less than 72 hours’ notice may forfeit the session fee or credit, as permitted by applicable law and payer rules.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-ivory">
    <div class="container">
        <div class="section-header centered" data-reveal="fade-up">
            <span class="eyebrow-badge eyebrow-light-bg">Common Questions</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="gold-divider"></div>
            <p class="section-subtitle">
                Find clear answers about our psychotherapy services, child and adolescent care, faith-integrated counseling, wellness consultations, and practice policies.
            </p>
        </div>

        <div class="faq-accordion" data-reveal="fade-up">
            <?php foreach ($faqs as $idx => $faq):
                $panelId = 'faq-panel-' . ($idx + 1);
                $isOpen  = ($idx === 0);
            ?>
                <div class="faq-item <?= $isOpen ? 'is-open' : '' ?>">
                    <button type="button"
                            class="faq-trigger"
                            aria-expanded="<?= $isOpen ? 'true' : 'false' ?>"
                            aria-controls="<?= e($panelId) ?>">
                        <span><?= e($faq['question']) ?></span>
                        <span class="faq-icon"><i data-lucide="chevron-down" style="width: 18px; height: 18px;"></i></span>
                    </button>
                    <div class="faq-panel prose" id="<?= e($panelId) ?>" <?= $isOpen ? '' : 'hidden' ?>>
                        <?= $faq['answer'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
