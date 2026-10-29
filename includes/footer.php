<?php
/**
 * De AnchoredSelf, LLC - Public Website Master Footer Component (includes/footer.php)
 * Includes Pre-Footer Sanctuary Horizon CTA, Footer Navigation, Policy Notices,
 * and the universal Back-to-Top Floating Button with SVG Scroll Progress Ring.
 */

declare(strict_types=1);

$practiceName        = Repository::setting('practice_name', 'De AnchoredSelf, LLC');
$tagline             = Repository::setting('tagline', 'Grounded in Healing. Growing in Strength. Delivering Hope.');
$telephone           = Repository::setting('telephone', '443-912-4612');
$fullAddress         = Repository::setting('full_address', '403 Eastern Boulevard, Essex, Maryland 21221');
$officeHours         = Repository::setting('office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time');
$availabilityNotice  = Repository::setting('availability_notice', 'Telehealth and in-person services are available by appointment. Contact us to confirm availability in your area.');
$logoLight           = resolve_asset_url(Repository::setting('logo_light', '/assets/images/logo-light.svg'));
$footerAbout         = Repository::setting('footer_about_text', 'Compassionate, culturally responsive psychotherapy and whole-person wellness services supporting children ages 5 and older, adolescents, adults, couples, and families.');
$cancellationSummary = Repository::setting('cancellation_policy_summary', 'At least 72 hours’ notice is required to cancel or reschedule an appointment. Membership payments and service fees are nonrefundable, except where required by law.');
?>
    </main>

    <!-- Pre-Footer Architectural Call-to-Action Horizon -->
    <section class="cta-horizon" aria-label="Request an Appointment">
        <div class="cta-horizon-glow" aria-hidden="true"></div>
        <div class="container">
            <div class="cta-horizon-panel" data-reveal="fade-up">
                <div class="cta-horizon-copy">
                    <span class="eyebrow-badge">
                        <i data-lucide="anchor"></i>
                        <span><?= e($tagline) ?></span>
                    </span>
                    <h2>Sometimes you need structured support. Sometimes you simply need a place to talk.</h2>
                    <p>
                        Submit an appointment inquiry and our practice will discuss your needs, available services, and next steps with you. Telehealth and in-person appointments are available Monday through Saturday.
                    </p>
                </div>
                <div class="cta-horizon-actions">
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" class="btn btn-gold btn-shimmer">
                        <i data-lucide="calendar-heart"></i>
                        <span>Request an Appointment</span>
                    </a>
                    <a href="<?= e(SITE_BASE_URL) ?>/services/" class="btn btn-outline-light">
                        <i data-lucide="compass"></i>
                        <span>Explore Our Services</span>
                    </a>
                    <a href="<?= e(format_tel_href($telephone)) ?>" class="cta-phone-link">
                        <i data-lucide="phone-call"></i>
                        <span>Or call <?= e($telephone) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Footer Component -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="<?= e(SITE_BASE_URL) ?>/" style="display: inline-block; margin-bottom: 1rem;">
                        <img src="<?= e($logoLight) ?>" alt="<?= e($practiceName) ?>" width="260" height="52" loading="lazy">
                    </a>
                    <p style="margin: 0 0 1rem; color: #CBD5E1; font-size: 0.93rem;">
                        <?= e($footerAbout) ?>
                    </p>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--gold-400); font-weight: 500;">
                        <?= e($availabilityNotice) ?>
                    </p>
                </div>

                <div>
                    <h3 class="footer-heading">Practice Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="<?= e(SITE_BASE_URL) ?>/">Home</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/about/">About Our Practice</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/services/">Explore All Services</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/psychotherapy/">Psychotherapy</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/christian-counseling/">Christian Counseling</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/wellness/">Wellness &amp; Pain Support</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-heading">Specialized &amp; Policies</h3>
                    <ul class="footer-links">
                        <li><a href="<?= e(SITE_BASE_URL) ?>/weight-loss-surgery-assessments/">Weight-Loss Surgery Assessments</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/clinical-supervision/">Clinical Supervision</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/membership-faq/">Membership and FAQ</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/resources/">Videos and Resources</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/events/">Events and Gallery</a></li>
                        <li><a href="<?= e(SITE_BASE_URL) ?>/privacy-policy/">Privacy Information &amp; Policies</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-heading">Contact &amp; Office Hours</h3>
                    <ul class="footer-contact-list">
                        <li class="footer-contact-item">
                            <i data-lucide="map-pin"></i>
                            <span>
                                <strong style="color: #FFFFFF; display: block;"><?= e($practiceName) ?></strong>
                                403 Eastern Boulevard<br>Essex, Maryland 21221
                            </span>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="phone"></i>
                            <span>
                                <a href="<?= e(format_tel_href($telephone)) ?>" style="color: #FFFFFF; font-weight: 600; text-decoration: none;"><?= e($telephone) ?></a>
                            </span>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="clock"></i>
                            <span><?= e($officeHours) ?></span>
                        </li>
                        <li class="footer-contact-item">
                            <i data-lucide="video"></i>
                            <span>Telehealth and in-person appointments available.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Policy & Emergency Notice Strip -->
            <div class="footer-policy-strip">
                <p style="margin: 0 0 0.35rem;">
                    <strong style="color: var(--gold-400);">Appointment &amp; Cancellation Policy:</strong> <?= e($cancellationSummary) ?>
                </p>
                <p style="margin: 0;">
                    <strong style="color: var(--gold-400);">Important Notice:</strong> Website inquiries are for routine contact only and are not monitored continuously. Submitting an inquiry does not establish a therapeutic relationship or confirm an appointment. <strong>For an immediate emergency, call 911 or call/text 988.</strong>
                </p>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; <?= date('Y') ?> <strong><?= e($practiceName) ?></strong>. All rights reserved.
                </div>
                <div style="display: flex; gap: 1.25rem; flex-wrap: wrap;">
                    <a href="<?= e(SITE_BASE_URL) ?>/privacy-policy/" style="color: #94A3B8; text-decoration: none;">Privacy &amp; Consent Notice</a>
                    <a href="<?= e(SITE_BASE_URL) ?>/membership-faq/" style="color: #94A3B8; text-decoration: none;">Policies &amp; FAQ</a>
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/#inquiry-form" style="color: var(--gold-400); text-decoration: none; font-weight: 600;">Request an Appointment</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Universal Back-to-Top Floating Button with Scroll Progress Ring -->
    <button type="button"
            class="back-to-top-btn"
            id="backToTopBtn"
            aria-label="Scroll back to top of page"
            title="Back to top">
        <svg class="back-to-top-ring" width="52" height="52" viewBox="0 0 52 52" aria-hidden="true">
            <circle class="ring-bg" cx="26" cy="26" r="23" fill="none" stroke-width="2"></circle>
            <circle class="ring-progress" id="backToTopProgressCircle" cx="26" cy="26" r="23" fill="none" stroke-width="2.5" stroke-dasharray="144.51" stroke-dashoffset="144.51"></circle>
        </svg>
        <span class="back-to-top-icon">
            <i data-lucide="arrow-up"></i>
        </span>
    </button>

    <script src="<?= e(SITE_BASE_URL) ?>/assets/js/lucide.min.js"></script>
    <script src="<?= e(SITE_BASE_URL) ?>/assets/js/main.js?v=<?= @filemtime(ROOT_PATH . '/assets/js/main.js') ?: '2' ?>"></script>
</body>
</html>
