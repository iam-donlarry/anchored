<?php
/**
 * De AnchoredSelf, LLC - Reusable Video/Resource Grid Partial
 * Visitor-controlled playback only (no autoplay). Includes titles, descriptions,
 * thumbnails, captions notice, and collapsible transcripts.
 */

declare(strict_types=1);

if (!empty($videoItems) && is_array($videoItems)):
?>
<div class="resource-grid">
    <?php foreach ($videoItems as $vid): ?>
        <article class="resource-card">
            <div class="resource-thumb-wrap">
                <?php if ($vid['media_type'] === 'video_file' && !empty($vid['video_url'])): ?>
                    <video controls preload="none" poster="<?= e(resolve_asset_url($vid['thumbnail_path'])) ?>" aria-label="<?= e($vid['title']) ?>">
                        <source src="<?= e(resolve_asset_url($vid['video_url'])) ?>" type="video/mp4">
                        Your browser does not support HTML5 video playback.
                    </video>
                <?php elseif ($vid['media_type'] === 'video_embed' && !empty($vid['video_url'])): ?>
                    <img src="<?= e(resolve_asset_url($vid['thumbnail_path'])) ?>" alt="<?= e($vid['thumbnail_alt'] ?: $vid['title']) ?>" width="640" height="360" loading="lazy">
                    <a href="<?= e($vid['video_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-gold" style="position: absolute; bottom: 1rem; right: 1rem; padding: 0.45rem 0.95rem; font-size: 0.85rem;">
                        <i data-lucide="play-circle"></i>
                        <span>Watch Video</span>
                    </a>
                <?php else: ?>
                    <img src="<?= e(resolve_asset_url($vid['thumbnail_path'])) ?>" alt="<?= e($vid['thumbnail_alt'] ?: $vid['title']) ?>" width="640" height="360" loading="lazy">
                <?php endif; ?>

                <div class="resource-badge-bar">
                    <span class="resource-tag"><?= e($vid['category']) ?></span>
                    <?php if (!empty($vid['duration_label'])): ?>
                        <span class="resource-tag"><?= e($vid['duration_label']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="resource-body">
                <h3 class="resource-title"><?= e($vid['title']) ?></h3>
                <p class="resource-summary"><?= e($vid['summary']) ?></p>

                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.85rem;">
                    <?php if (!empty($vid['captions_available'])): ?>
                        <span style="display: inline-flex; align-items: center; gap: 0.3rem;">
                            <i data-lucide="subtitles" style="width: 14px; height: 14px; color: var(--gold-600);"></i>
                            Captions &amp; Transcript Available
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($vid['published_date'])): ?>
                        <span>&bull; <?= e(date('F j, Y', strtotime((string)$vid['published_date']))) ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($vid['transcript_text'])): ?>
                    <details class="transcript-details">
                        <summary>
                            <i data-lucide="file-text" style="width: 15px; height: 15px;"></i>
                            <span>Read Full Transcript &amp; Educational Notes</span>
                        </summary>
                        <div class="transcript-box"><?= e($vid['transcript_text']) ?></div>
                    </details>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>

