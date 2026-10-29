<?php
/**
 * De AnchoredSelf, LLC - Upcoming Events & Past Event Gallery (/events/)
 * Code-defined layout with CMS-managed events (`events`) and albums (`gallery_albums`, `gallery_media`).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/Repository.php';

$activeNav           = 'events';
$canonicalPath       = '/events/';
$pageMetaTitle       = 'Upcoming Events & Past Event Gallery | De AnchoredSelf, LLC';
$pageMetaDescription = 'Workshops, wellness seminars, group series, and past event highlights hosted by De AnchoredSelf, LLC in Essex, Maryland and online.';

$upcomingEvents = Repository::getUpcomingEvents(20);
$pastEvents     = Repository::getPastEvents(20);
$albums         = Repository::getPublishedAlbumsWithMedia();

include dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-grid">
        <div>
            <span class="eyebrow-badge">
                <i data-lucide="calendar"></i>
                <span>Workshops, Seminars &amp; Community Connection</span>
            </span>
            <h1>Upcoming Events and Past Event Gallery</h1>
            <p class="page-hero-sub">
                Join De AnchoredSelf, LLC for educational workshops, whole-person wellness conversations, and supportive community events.
            </p>
        </div>
        <div class="page-hero-img">
            <img src="<?= e(resolve_asset_url('/assets/images/placeholders/community-workshop.jpg')) ?>"
                 alt="Community workshop and educational event at De AnchoredSelf, LLC"
                 width="560"
                 height="350">
        </div>
    </div>
</section>

<!-- UPCOMING EVENTS -->
<section class="section section-white" aria-labelledby="upcomingEventsHeading">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow-badge eyebrow-light-bg">Mark Your Calendar</span>
            <h2 id="upcomingEventsHeading" class="section-title">Upcoming Workshops &amp; Events</h2>
            <div class="gold-divider"></div>
            <p class="section-subtitle">
                Explore upcoming in-person and virtual events hosted by our practice.
            </p>
        </div>

        <?php if (empty($upcomingEvents)): ?>
            <div class="inquiry-card" style="text-align: center; max-width: 680px; margin: 0 auto;">
                <div class="pillar-icon" style="margin: 0 auto 1rem;"><i data-lucide="calendar"></i></div>
                <h3 style="margin: 0 0 0.5rem; font-size: 1.4rem; color: var(--navy-900);">New Events Announced Soon</h3>
                <p style="margin: 0 0 1.5rem; color: var(--text-secondary);">
                    We are currently planning our next series of workshops and community seminars. Contact our office if you would like to inquire about upcoming group sessions or workshops.
                </p>
                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Group+Psychotherapy#inquiry-form" class="btn btn-outline">
                    <span>Inquire About Upcoming Events</span>
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($upcomingEvents as $ev):
                $ts = strtotime((string)$ev['event_date']);
            ?>
                <article class="event-card" id="event-<?= (int)$ev['id'] ?>">
                    <div class="event-media">
                        <img src="<?= e(resolve_asset_url($ev['image_path'] ?? '/assets/images/placeholders/community-workshop.jpg')) ?>"
                             alt="<?= e(($ev['image_alt'] ?? '') !== '' ? $ev['image_alt'] : $ev['title']) ?>"
                             width="320"
                             height="220"
                             loading="lazy">
                        <?php if ($ts): ?>
                            <div class="event-date-pill">
                                <span class="month"><?= e(date('M', $ts)) ?></span>
                                <span class="day"><?= e(date('d', $ts)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-content">
                        <div>
                            <span class="eyebrow-badge eyebrow-light-bg" style="margin-bottom: 0.5rem;"><?= e($ev['format'] ?? 'In-Person / Virtual') ?> Event</span>
                            <h3 style="margin: 0 0 0.4rem; font-size: 1.35rem; color: var(--navy-900);"><?= e($ev['title']) ?></h3>
                            <div class="event-meta-list">
                                <span class="event-meta-item">
                                    <i data-lucide="calendar"></i>
                                    <span><?= $ts ? e(date('F j, Y', $ts)) : e((string)$ev['event_date']) ?></span>
                                </span>
                                <?php if (!empty($ev['event_time'])): ?>
                                    <span class="event-meta-item">
                                        <i data-lucide="clock"></i>
                                        <span><?= e($ev['event_time']) ?><?= !empty($ev['timezone']) ? ' (' . e($ev['timezone']) . ')' : '' ?></span>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($ev['location'])): ?>
                                    <span class="event-meta-item">
                                        <i data-lucide="map-pin"></i>
                                        <span><?= e($ev['location']) ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="prose">
                                <?= !empty($ev['description']) ? $ev['description'] : '<p>' . e((string)($ev['summary'] ?? '')) . '</p>' ?>
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
                                    <span><?= e($ev['registration_button_label'] ?: 'Register / Learn More') ?></span>
                                    <i data-lucide="external-link"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= e(SITE_BASE_URL) ?>/contact/?service=Group+Psychotherapy#inquiry-form" class="btn btn-outline" style="padding: 0.55rem 1.15rem; font-size: 0.88rem;">
                                    <span>Inquire About This Event</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- PAST EVENTS & GALLERY -->
<section class="section section-ivory" aria-labelledby="pastEventsGalleryHeading">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow-badge eyebrow-light-bg">Community Moments</span>
            <h2 id="pastEventsGalleryHeading" class="section-title">Past Events &amp; Photo Gallery</h2>
            <div class="gold-divider"></div>
            <p class="section-subtitle">
                Highlights and reflections from our previous workshops, educational gatherings, and practice spaces.
            </p>
        </div>

        <?php if (!empty($pastEvents)): ?>
            <div style="margin-bottom: 3rem;">
                <?php foreach ($pastEvents as $pev):
                    $pts = strtotime((string)$pev['event_date']);
                ?>
                    <article class="event-card">
                        <div class="event-media">
                            <img src="<?= e(resolve_asset_url($pev['image_path'] ?? '/assets/images/placeholders/community-workshop.jpg')) ?>"
                                 alt="<?= e(($pev['image_alt'] ?? '') !== '' ? $pev['image_alt'] : $pev['title']) ?>"
                                 width="320"
                                 height="220"
                                 loading="lazy">
                        </div>
                        <div class="event-content">
                            <div>
                                <span class="eyebrow-badge eyebrow-light-bg" style="margin-bottom: 0.5rem;">
                                    Past Event &bull; <?= $pts ? e(date('F j, Y', $pts)) : e((string)$pev['event_date']) ?>
                                </span>
                                <h3 style="margin: 0 0 0.5rem; font-size: 1.3rem; color: var(--navy-900);"><?= e($pev['title']) ?></h3>
                                <div class="prose">
                                    <?= !empty($pev['description']) ? $pev['description'] : '<p>' . e((string)($pev['summary'] ?? '')) . '</p>' ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($albums)): ?>
            <?php foreach ($albums as $album): ?>
                <div class="album-block">
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                        <div>
                            <h3 style="margin: 0 0 0.35rem; font-size: 1.35rem; color: var(--navy-900);"><?= e($album['title']) ?></h3>
                            <?php if (!empty($album['summary'])): ?>
                                <p style="margin: 0; color: var(--text-secondary); font-size: 0.95rem;"><?= e($album['summary']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($album['event_date'])): ?>
                            <span class="eyebrow-badge eyebrow-light-bg" style="margin: 0;">
                                <?= e(date('F Y', strtotime((string)$album['event_date']))) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($album['media_items'])): ?>
                        <div class="album-media-grid">
                            <?php foreach ($album['media_items'] as $item): ?>
                                <figure class="gallery-figure">
                                    <?php if (($item['media_type'] ?? 'image') === 'video_file' && !empty($item['file_path'])): ?>
                                        <video controls preload="metadata" poster="<?= e(resolve_asset_url($album['cover_image'] ?? '/assets/images/placeholders/community-workshop.jpg')) ?>">
                                            <source src="<?= e(resolve_asset_url($item['file_path'])) ?>" type="video/mp4">
                                        </video>
                                    <?php else: ?>
                                        <img src="<?= e(resolve_asset_url($item['file_path'])) ?>"
                                             alt="<?= e(($item['alt_text'] ?? '') !== '' ? $item['alt_text'] : ($item['caption'] ?? $album['title'])) ?>"
                                             loading="lazy">
                                    <?php endif; ?>
                                    <?php if (!empty($item['caption'])): ?>
                                        <figcaption><?= e($item['caption']) ?></figcaption>
                                    <?php endif; ?>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
