<?php
/**
 * De AnchoredSelf, LLC - Contact & General Appointment Inquiry (/contact/)
 * Code-defined layout & content; server-validated POST saves routine inquiries to `inquiries`.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'contact';
$canonicalPath       = '/contact/';
$pageMetaTitle       = 'Contact & Request an Appointment | De AnchoredSelf, LLC';
$pageMetaDescription = 'Contact De AnchoredSelf, LLC in Essex, Maryland or submit a routine appointment inquiry for telehealth or in-person psychotherapy, Christian counseling, wellness, or supervision.';

$telephone          = Repository::setting('telephone', '443-912-4612');
$fullAddress        = Repository::setting('full_address', '403 Eastern Boulevard, Essex, Maryland 21221');
$officeHours        = Repository::setting('office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time');
$availabilityNotice = Repository::setting('availability_notice', 'Telehealth and in-person services are available by appointment. Contact us to confirm availability in your area.');
$formSafetyNotice   = Repository::setting('inquiry_form_notice', 'Please keep your message brief and avoid including sensitive medical or mental-health information. This form is for routine inquiries and is not monitored continuously. For an immediate emergency, call 911.');
$confirmationText   = Repository::setting('inquiry_confirmation_message', 'Thank you for contacting De AnchoredSelf, LLC. We have received your inquiry. Our practice will communicate with you directly to discuss next steps. Submitting this form does not establish a therapeutic relationship or confirm an appointment.');

$approvedServices  = get_approved_inquiry_services();
$approvedAgeGroups = get_approved_age_groups();
$usStates          = get_us_states();

$errors        = [];
$formSubmitted = false;

$formData = [
    'full_name'             => trim((string)($_POST['full_name'] ?? '')),
    'email'                 => trim((string)($_POST['email'] ?? '')),
    'phone'                 => trim((string)($_POST['phone'] ?? '')),
    'preferred_contact'     => trim((string)($_POST['preferred_contact'] ?? 'Telephone')),
    'age_group'             => trim((string)($_POST['age_group'] ?? ($_GET['age_group'] ?? ''))),
    'guardian_name'         => trim((string)($_POST['guardian_name'] ?? '')),
    'guardian_contact'      => trim((string)($_POST['guardian_contact'] ?? '')),
    'guardian_relationship' => trim((string)($_POST['guardian_relationship'] ?? '')),
    'service_state'         => trim((string)($_POST['service_state'] ?? 'Maryland')),
    'service_of_interest'   => trim((string)($_POST['service_of_interest'] ?? ($_GET['service'] ?? ''))),
    'preferred_format'      => trim((string)($_POST['preferred_format'] ?? 'Either / Undecided')),
    'message'               => trim((string)($_POST['message'] ?? '')),
    'privacy_acknowledged'  => isset($_POST['privacy_acknowledged']) ? 1 : 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $errors[] = 'Your session security token expired. Please review the form and submit again.';
    }

    if (!empty($_POST['website_url_hp'])) {
        $errors[] = 'Invalid submission detected.';
    }

    if ($formData['full_name'] === '' || mb_strlen($formData['full_name']) < 2) {
        $errors[] = 'Please enter your name (or the prospective client’s name).';
    }

    if ($formData['email'] === '' && $formData['phone'] === '') {
        $errors[] = 'Please provide a telephone number or an email address so our practice can reach you.';
    }

    if ($formData['email'] !== '' && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($formData['phone'] !== '' && !preg_match('/^[0-9+\-\(\)\s\.]{7,25}$/', $formData['phone'])) {
        $errors[] = 'Please enter a valid telephone number.';
    }

    $validContactMethods = ['Telephone', 'Email', 'Either Telephone or Email'];
    if (!in_array($formData['preferred_contact'], $validContactMethods, true)) {
        $errors[] = 'Please select a valid preferred contact method.';
    }

    if ($formData['preferred_contact'] === 'Email' && $formData['email'] === '') {
        $errors[] = 'Please provide an email address when selecting Email as your preferred contact method.';
    }

    if ($formData['preferred_contact'] === 'Telephone' && $formData['phone'] === '') {
        $errors[] = 'Please provide a telephone number when selecting Telephone as your preferred contact method.';
    }

    if (!in_array($formData['age_group'], $approvedAgeGroups, true)) {
        $errors[] = 'Please select the applicable age group.';
    }

    $isMinor = (strpos($formData['age_group'], 'Child') !== false || strpos($formData['age_group'], 'Adolescent') !== false);
    if ($isMinor) {
        if ($formData['guardian_name'] === '') {
            $errors[] = 'For inquiries concerning a child or adolescent, please provide the parent or legal guardian’s name.';
        }
        if ($formData['guardian_contact'] === '') {
            $errors[] = 'For inquiries concerning a child or adolescent, please provide the parent or legal guardian’s telephone number or email.';
        }
    }

    if (!in_array($formData['service_state'], $usStates, true)) {
        $errors[] = 'Please select the state where services will be received.';
    }

    if (!in_array($formData['service_of_interest'], $approvedServices, true)) {
        $errors[] = 'Please select a service of interest from the list.';
    }

    $validFormats = ['Telehealth', 'In-Person (Essex, MD)', 'Either / Undecided'];
    if (!in_array($formData['preferred_format'], $validFormats, true)) {
        $errors[] = 'Please select a preferred appointment format.';
    }

    if (mb_strlen($formData['message']) > 600) {
        $errors[] = 'Please keep your optional message brief (600 characters or fewer) and avoid sensitive clinical details.';
    }

    if ($formData['privacy_acknowledged'] !== 1) {
        $errors[] = 'Please acknowledge the inquiry notice and policy terms before submitting.';
    }

    if (empty($errors)) {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO inquiries (
                    full_name, email, phone, preferred_contact, age_group,
                    is_child_inquiry, guardian_name, guardian_contact, guardian_relationship,
                    service_state, service_of_interest, preferred_format, message,
                    privacy_acknowledged, status
                ) VALUES (
                    :full_name, :email, :phone, :preferred_contact, :age_group,
                    :is_child_inquiry, :guardian_name, :guardian_contact, :guardian_relationship,
                    :service_state, :service_of_interest, :preferred_format, :message,
                    1, 'new'
                )
            ");
            $stmt->execute([
                ':full_name'             => mb_substr($formData['full_name'], 0, 140),
                ':email'                 => $formData['email'] !== '' ? mb_substr($formData['email'], 0, 160) : null,
                ':phone'                 => $formData['phone'] !== '' ? mb_substr($formData['phone'], 0, 50) : null,
                ':preferred_contact'     => $formData['preferred_contact'],
                ':age_group'             => $formData['age_group'],
                ':is_child_inquiry'      => $isMinor ? 1 : 0,
                ':guardian_name'         => $isMinor && $formData['guardian_name'] !== '' ? mb_substr($formData['guardian_name'], 0, 140) : null,
                ':guardian_contact'      => $isMinor && $formData['guardian_contact'] !== '' ? mb_substr($formData['guardian_contact'], 0, 160) : null,
                ':guardian_relationship' => $isMinor && $formData['guardian_relationship'] !== '' ? mb_substr($formData['guardian_relationship'], 0, 80) : null,
                ':service_state'         => $formData['service_state'],
                ':service_of_interest'   => $formData['service_of_interest'],
                ':preferred_format'      => $formData['preferred_format'],
                ':message'               => $formData['message'] !== '' ? mb_substr($formData['message'], 0, 600) : null,
            ]);

            $formSubmitted = true;
            $formData = [
                'full_name' => '', 'email' => '', 'phone' => '',
                'preferred_contact' => 'Telephone', 'age_group' => '',
                'guardian_name' => '', 'guardian_contact' => '', 'guardian_relationship' => '',
                'service_state' => 'Maryland', 'service_of_interest' => '',
                'preferred_format' => 'Either / Undecided', 'message' => '',
                'privacy_acknowledged' => 0,
            ];
        } catch (Throwable $e) {
            error_log('Inquiry save error: ' . $e->getMessage());
            $errors[] = 'We encountered a temporary problem saving your inquiry. Please call us directly at ' . $telephone . '.';
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div>
            <span class="eyebrow-badge">
                <i data-lucide="phone-call"></i>
                <span>Connect With Our Practice</span>
            </span>
            <h1>Let’s begin with a conversation.</h1>
            <p class="page-hero-sub">
                Whether you are looking for psychotherapy, Christian counseling, wellness support, an assessment consultation, or professional supervision, we welcome your inquiry.
            </p>
        </div>
        <div class="page-hero-img">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/counseling-space.jpg')) ?>"
                 alt="Welcoming consultation room at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-ivory" id="inquiry-form">
    <div class="container">
        <div style="display: grid; grid-template-columns: 0.9fr 1.4fr; gap: 3rem; align-items: start;" class="editorial-split">
            <!-- Left Column: Direct Practice Contact Details & Policies -->
            <div>
                <div style="background: var(--navy-900); color: #FFFFFF; border-radius: var(--radius-lg); padding: 2.25rem; border-top: 4px solid var(--gold-500); box-shadow: var(--shadow-md); margin-bottom: 1.75rem;">
                    <span class="eyebrow-badge">Practice Contact Information</span>
                    <h2 style="margin: 0 0 1rem; font-size: 1.6rem; color: #FFFFFF;">De AnchoredSelf, LLC</h2>
                    <p style="color: #D5E2EE; margin: 0 0 1.5rem;">
                        Submit an appointment inquiry and our practice will discuss your needs, available services, and next steps with you.
                    </p>

                    <ul class="footer-contact-list" style="font-size: 0.98rem;">
                        <li class="footer-contact-item">
                            <i data-lucide="map-pin"></i>
                            <div>
                                <strong style="color: var(--gold-400); display: block;">Office Location</strong>
                                403 Eastern Boulevard<br>Essex, Maryland 21221
                            </div>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="phone"></i>
                            <div>
                                <strong style="color: var(--gold-400); display: block;">Telephone</strong>
                                <a href="<?= e(format_tel_href($telephone)) ?>" style="color: #FFFFFF; font-size: 1.15rem; font-weight: 700; text-decoration: none;"><?= e($telephone) ?></a>
                            </div>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="clock"></i>
                            <div>
                                <strong style="color: var(--gold-400); display: block;">Office Hours</strong>
                                Monday through Saturday<br>9:00 a.m.–6:00 p.m., Eastern Time
                            </div>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="video"></i>
                            <div>
                                <strong style="color: var(--gold-400); display: block;">Appointment Formats</strong>
                                Telehealth and in-person appointments available.
                            </div>
                        </li>
                    </ul>

                    <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(255, 255, 255, 0.14); font-size: 0.9rem; color: var(--gold-400);">
                        “<?= e($availabilityNotice) ?>”
                    </div>
                </div>

                <!-- Policy Summary Card -->
                <div style="background: var(--surface-white); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem;">
                    <h3 style="margin: 0 0 0.65rem; font-size: 1.15rem; color: var(--navy-900);">
                        <i data-lucide="shield-alert" style="width: 18px; height: 18px; color: var(--gold-600); vertical-align: text-bottom; margin-right: 4px;"></i>
                        Fees, Memberships &amp; Cancellation Policy
                    </h3>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0 0 0.75rem;">
                        Membership options are available. Contact our practice for information about service fees, membership benefits, and payment arrangements. Fees and included services are explained before enrollment.
                    </p>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0;">
                        <strong>Cancellation Policy:</strong> At least 72 hours’ notice is required to cancel or reschedule an appointment. Membership payments and service fees are nonrefundable, except where required by law.
                    </p>
                </div>
            </div>

            <!-- Right Column: General Routine Appointment Inquiry Form -->
            <div class="inquiry-card">
                <span class="eyebrow-badge eyebrow-light-bg">Routine Appointment Inquiry</span>
                <h2 style="margin: 0 0 0.5rem; font-size: 1.75rem; color: var(--navy-900);">Request an Appointment</h2>
                <p style="margin: 0 0 1.5rem; color: var(--text-secondary);">
                    Submit an appointment inquiry and our practice will discuss your needs, available services, and next steps with you.
                </p>

                <div class="notice-banner warning" role="note">
                    <i data-lucide="alert-triangle" style="flex-shrink: 0;"></i>
                    <div>
                        <strong>Important Notice:</strong> <?= e($formSafetyNotice) ?>
                    </div>
                </div>

                <?php if ($formSubmitted): ?>
                    <div class="notice-banner success" role="status" aria-live="polite" style="padding: 1.5rem;">
                        <i data-lucide="check-circle-2" style="width: 24px; height: 24px; flex-shrink: 0;"></i>
                        <div>
                            <strong style="display: block; font-size: 1.08rem; margin-bottom: 0.35rem;">Inquiry Received</strong>
                            <p style="margin: 0;"><?= e($confirmationText) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="notice-banner danger" role="alert">
                        <i data-lucide="x-circle" style="flex-shrink: 0;"></i>
                        <div>
                            <strong style="display: block; margin-bottom: 0.35rem;">Please review the following items:</strong>
                            <ul style="margin: 0; padding-left: 1.2rem;">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" id="appointmentInquiryForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                        <label for="websiteUrlHp">Leave this field blank</label>
                        <input type="text" name="website_url_hp" id="websiteUrlHp" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="inquiryFullName" class="form-label">Your Name (or Prospective Client Name) <span class="req">*</span></label>
                            <input type="text" id="inquiryFullName" name="full_name" class="form-control" required maxlength="140" value="<?= e($formData['full_name']) ?>" placeholder="Enter full name">
                        </div>

                        <div class="form-group">
                            <label for="inquiryPhone" class="form-label">Telephone Number <span class="req">*</span></label>
                            <input type="tel" id="inquiryPhone" name="phone" class="form-control" maxlength="50" value="<?= e($formData['phone']) ?>" placeholder="e.g., 443-555-0199">
                            <span class="form-help">Provide telephone, email, or both.</span>
                        </div>

                        <div class="form-group">
                            <label for="inquiryEmail" class="form-label">Email Address</label>
                            <input type="email" id="inquiryEmail" name="email" class="form-control" maxlength="160" value="<?= e($formData['email']) ?>" placeholder="you@example.com">
                        </div>

                        <div class="form-group">
                            <label for="inquiryPreferredContact" class="form-label">Preferred Contact Method <span class="req">*</span></label>
                            <select id="inquiryPreferredContact" name="preferred_contact" class="form-select" required>
                                <?php foreach (['Telephone', 'Email', 'Either Telephone or Email'] as $method): ?>
                                    <option value="<?= e($method) ?>" <?= $formData['preferred_contact'] === $method ? 'selected' : '' ?>><?= e($method) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inquiryAgeGroup" class="form-label">Age Group <span class="req">*</span></label>
                            <select id="inquiryAgeGroup" name="age_group" class="form-select" required>
                                <option value="">Select age group...</option>
                                <?php foreach ($approvedAgeGroups as $ag): ?>
                                    <option value="<?= e($ag) ?>" <?= $formData['age_group'] === $ag ? 'selected' : '' ?>><?= e($ag) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <fieldset class="guardian-fieldset" id="guardianFieldsSection" style="display: none;">
                            <legend>Parent or Legal Guardian Information (For Child / Adolescent Inquiries)</legend>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="guardianName" class="form-label">Parent / Guardian Full Name <span class="req">*</span></label>
                                    <input type="text" id="guardianName" name="guardian_name" class="form-control" maxlength="140" value="<?= e($formData['guardian_name']) ?>" placeholder="Parent or legal guardian name">
                                </div>
                                <div class="form-group">
                                    <label for="guardianContact" class="form-label">Parent / Guardian Phone or Email <span class="req">*</span></label>
                                    <input type="text" id="guardianContact" name="guardian_contact" class="form-control" maxlength="160" value="<?= e($formData['guardian_contact']) ?>" placeholder="Direct phone or email">
                                </div>
                                <div class="form-group full-width">
                                    <label for="guardianRelationship" class="form-label">Relationship to Child / Adolescent</label>
                                    <input type="text" id="guardianRelationship" name="guardian_relationship" class="form-control" maxlength="80" value="<?= e($formData['guardian_relationship']) ?>" placeholder="e.g., Mother, Father, Legal Guardian">
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group">
                            <label for="inquiryState" class="form-label">State Where Services Will Be Received <span class="req">*</span></label>
                            <select id="inquiryState" name="service_state" class="form-select" required>
                                <?php foreach ($usStates as $st): ?>
                                    <option value="<?= e($st) ?>" <?= $formData['service_state'] === $st ? 'selected' : '' ?>><?= e($st) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inquiryFormat" class="form-label">Preferred Appointment Format <span class="req">*</span></label>
                            <select id="inquiryFormat" name="preferred_format" class="form-select" required>
                                <?php foreach (['Telehealth', 'In-Person (Essex, MD)', 'Either / Undecided'] as $fmt): ?>
                                    <option value="<?= e($fmt) ?>" <?= $formData['preferred_format'] === $fmt ? 'selected' : '' ?>><?= e($fmt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="inquiryService" class="form-label">Service of Interest <span class="req">*</span></label>
                            <select id="inquiryService" name="service_of_interest" class="form-select" required>
                                <option value="">Select a service of interest...</option>
                                <?php foreach ($approvedServices as $svcOpt): ?>
                                    <option value="<?= e($svcOpt) ?>" <?= strcasecmp($formData['service_of_interest'], $svcOpt) === 0 ? 'selected' : '' ?>><?= e($svcOpt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="inquiryMessage" class="form-label">Brief Optional Message</label>
                            <textarea id="inquiryMessage" name="message" class="form-textarea" rows="4" maxlength="600" placeholder="Share general availability or routine questions. Please do not include sensitive clinical or medical history."><?= e($formData['message']) ?></textarea>
                            <span class="form-help">Maximum 600 characters. Do not include diagnoses, medications, or detailed clinical history.</span>
                        </div>

                        <div class="form-group full-width">
                            <label style="display: flex; align-items: flex-start; gap: 0.65rem; font-size: 0.88rem; color: var(--text-secondary); cursor: pointer;">
                                <input type="checkbox" name="privacy_acknowledged" value="1" required style="margin-top: 4px; width: 18px; height: 18px;" <?= $formData['privacy_acknowledged'] === 1 ? 'checked' : '' ?>>
                                <span>
                                    I understand that this form is for routine inquiries only, is not monitored continuously, and that submitting this form does not establish a therapeutic relationship or confirm an appointment. I have reviewed the <a href="<?= e(SITE_BASE_URL) ?>/privacy-policy/" target="_blank">Privacy Information &amp; Practice Policies</a>. <span class="req">*</span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group full-width" style="margin-top: 0.5rem;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.95rem 1.5rem; font-size: 1rem;">
                                <i data-lucide="send"></i>
                                <span>Request an Appointment</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
