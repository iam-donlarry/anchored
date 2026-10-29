<?php
/**
 * De AnchoredSelf, LLC - Christian Counseling (/christian-counseling/)
 * Redesigned Sanctuary Editorial Concept:
 *  - Asymmetric Hero & Editorial Split
 *  - Continuous Architectural Manifesto Ribbon (Pillars I, II, III) instead of 3 boxy cards
 *  - Flowing Journey Timeline for What to Expect
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'christian-counseling';
$canonicalPath       = '/christian-counseling/';
$pageMetaTitle       = 'Christian Counseling | Optional Faith-Integrated Care | De AnchoredSelf, LLC';
$pageMetaDescription = 'Optional faith-integrated Christian counseling at De AnchoredSelf, LLC, honoring each client’s spiritual beliefs and clinical goals.';

$videoItems = Repository::getVideosByPlacement('christian-counseling', 3);

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div data-reveal="fade-right">
            <span class="eyebrow-badge">
                <i data-lucide="book-open-check"></i>
                <span>Faith-Integrated Care Option</span>
            </span>
            <h1>Christian Counseling</h1>
            <p class="page-hero-sub">
                Thoughtful psychotherapy that integrates Christian faith for clients who desire spiritual perspective alongside clinical support.
            </p>
        </div>
        <div class="page-hero-img" data-reveal="scale-in">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/christian-reflection.jpg')) ?>"
                 alt="Peaceful setting for faith-integrated reflection and Christian counseling"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="editorial-split">
            <div class="editorial-content prose" data-reveal="fade-right">
                <span class="eyebrow-badge eyebrow-light-bg">Faith &amp; Clinical Care</span>
                <h2 class="section-title">Integrating Faith at Your Request</h2>
                <div class="gold-divider"></div>
                <p>
                    Christian counseling is available for clients who want their faith integrated into the counseling process. Sessions may incorporate Christian principles, spiritual reflection, and faith-informed coping practices alongside clinical counseling approaches, always guided by the client’s preferences and goals.
                </p>
                <p>
                    We welcome clients from all backgrounds and respect each person’s beliefs, values, and preferences in care.
                </p>

                <div class="notice-banner" style="margin: 1.5rem 0;">
                    <i data-lucide="heart-handshake" style="color: var(--gold-600); flex-shrink: 0;"></i>
                    <div>
                        <strong>Respectful, Client-Led Integration:</strong> “Christian counseling is available upon request. We respect each client’s beliefs and preferences when planning care.”
                    </div>
                </div>

                <div style="margin-top: 1.75rem;">
                    <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Christian+Counseling#inquiry-form" class="btn btn-primary btn-shimmer">
                        <i data-lucide="calendar-heart"></i>
                        <span>Request Christian Counseling</span>
                    </a>
                </div>
            </div>

            <div data-reveal="fade-left">
                <span class="eyebrow-badge eyebrow-light-bg">What to Expect</span>
                <h3 style="margin: 0 0 1.5rem; font-size: 1.45rem; color: var(--navy-900);">How Faith Integration Is Guided by You</h3>
                <div class="journey-timeline">
                    <div class="journey-step">
                        <h4>Client-Guided Integration</h4>
                        <p>You decide whether and how scripture, prayer, or Christian worldview perspectives are brought into your sessions.</p>
                    </div>
                    <div class="journey-step">
                        <h4>Sound Clinical Foundations</h4>
                        <p>Faith integration is paired with evidence-informed psychotherapy practices for anxiety, grief, trauma, relationships, and life transitions.</p>
                    </div>
                    <div class="journey-step">
                        <h4>Always Optional &amp; Respectful</h4>
                        <p>While Christian counseling is offered as a dedicated option, our practice serves individuals, couples, and families of all faiths and backgrounds. Spiritual integration is never imposed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Continuous Architectural Manifesto Ribbon instead of 3 Boxy Cards -->
<section class="section section-ivory">
    <div class="container">
        <div class="section-header centered" data-reveal="fade-up">
            <span class="eyebrow-badge eyebrow-light-bg">Key Pillars</span>
            <h2 class="section-title">How Faith and Counseling Work Together</h2>
            <div class="gold-divider"></div>
        </div>

        <div class="manifesto-ribbon" data-reveal="fade-up">
            <div class="manifesto-col">
                <div class="manifesto-roman">Dimension I</div>
                <h3><i data-lucide="compass"></i> Values-Aligned Goals</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    Explore life decisions, relationship commitments, and personal healing in ways that honor your spiritual convictions and core values.
                </p>
            </div>
            <div class="manifesto-col">
                <div class="manifesto-roman">Dimension II</div>
                <h3><i data-lucide="heart"></i> Grace &amp; Emotional Healing</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    Address grief, forgiveness, anxiety, emotional exhaustion, and past wounds within an environment of compassion and clinical safety.
                </p>
            </div>
            <div class="manifesto-col">
                <div class="manifesto-roman">Dimension III</div>
                <h3><i data-lucide="users"></i> Individual, Couples &amp; Family</h3>
                <p style="margin: 0; color: var(--text-secondary);">
                    Available for individuals, couples preparing for or strengthening marriage, and families seeking faith-informed communication support.
                </p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($videoItems)): ?>
<section class="section section-white">
    <div class="container" data-reveal="fade-up">
        <div class="section-header">
            <span class="eyebrow-badge eyebrow-light-bg">Reflections &amp; Media</span>
            <h2 class="section-title">Related Videos &amp; Resources</h2>
            <div class="gold-divider"></div>
        </div>
        <?php include dirname(__DIR__) . '/includes/video-cards.php'; ?>
    </div>
</section>
<?php endif; ?>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
