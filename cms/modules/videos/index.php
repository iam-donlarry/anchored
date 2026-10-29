<?php
/**
 * De AnchoredSelf CMS - Videos & Educational Resources Module
 */

declare(strict_types=1);

$cmsActiveModule = 'videos';
$cmsPageTitle    = 'Videos & Educational Resources';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();
$videos = $pdo->query("SELECT * FROM videos_resources ORDER BY sort_order ASC, id DESC")->fetchAll();
$mediaItems = $pdo->query("SELECT file_path, original_name, mime_type FROM media_library ORDER BY id DESC")->fetchAll();
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Videos &amp; Educational Resources</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Manage the homepage welcome video, service-page educational resources, thumbnails, captions, and transcripts. Only publish content approved for public viewing without identifiable client information.
            </p>
        </div>
        <button type="button" class="cms-btn cms-btn-primary" id="btnNewVideo">
            <i data-lucide="plus"></i>
            <span>Add Video / Resource</span>
        </button>
    </div>

    <div class="cms-table-wrap">
        <table class="cms-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Title &amp; Category</th>
                    <th>Page Placement</th>
                    <th>Type</th>
                    <th>Transcript / Captions</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($videos as $v): ?>
                    <tr>
                        <td><?= (int)$v['sort_order'] ?></td>
                        <td>
                            <strong><?= e($v['title']) ?></strong>
                            <?php if ((int)$v['is_welcome_video'] === 1): ?>
                                <span class="cms-badge-pill" style="margin-left: 6px;">Home Welcome Video</span>
                            <?php endif; ?>
                            <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($v['category']) ?> &bull; <?= e($v['duration_label']) ?></div>
                        </td>
                        <td><code><?= e($v['page_placement']) ?></code></td>
                        <td><?= e($v['media_type']) ?></td>
                        <td>
                            <?= !empty($v['transcript_text']) ? '<span style="color:var(--cms-success);font-weight:600;">Included</span>' : '<span style="color:var(--cms-muted);">None</span>' ?>
                        </td>
                        <td>
                            <span class="status-pill status-<?= e($v['status']) ?>"><?= e(ucfirst($v['status'])) ?></span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <button type="button"
                                    class="cms-btn cms-btn-outline cms-btn-sm js-edit-video"
                                    data-video="<?= e(json_encode($v, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>">
                                <i data-lucide="edit-3"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button"
                                    class="cms-btn cms-btn-danger cms-btn-sm"
                                    onclick="CMS.actionRequest({action:'delete_video', id:<?= (int)$v['id'] ?>}, 'Delete this educational resource?')">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Video / Resource Modal -->
<div class="cms-modal-backdrop" id="videoModal" role="dialog" aria-modal="true" aria-labelledby="videoModalTitle">
    <div class="cms-modal">
        <div class="cms-modal-header">
            <h3 id="videoModalTitle" style="margin: 0; color: var(--cms-navy-900);">Add / Edit Video or Resource</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="videoModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="videoModal" id="videoForm">
            <input type="hidden" name="action" value="save_video">
            <input type="hidden" name="id" id="vid_id" value="0">
            <div class="cms-modal-body">
                <div class="cms-form-grid">
                    <div class="cms-form-group full">
                        <label class="cms-label" for="vid_title">Resource Title *</label>
                        <input type="text" name="title" id="vid_title" class="cms-input" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_category">Topic / Category *</label>
                        <input type="text" name="category" id="vid_category" class="cms-input" value="Whole-Person Wellness" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_page_placement">Page Placement</label>
                        <select name="page_placement" id="vid_page_placement" class="cms-select">
                            <option value="resources">Videos &amp; Resources Page Only</option>
                            <option value="home">Home Page &amp; Resources</option>
                            <option value="about">About Page &amp; Resources</option>
                            <option value="psychotherapy">Psychotherapy Page &amp; Resources</option>
                            <option value="christian-counseling">Christian Counseling Page &amp; Resources</option>
                            <option value="wellness">Wellness Page &amp; Resources</option>
                            <option value="all">All Service Pages &amp; Resources</option>
                        </select>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_media_type">Resource Format</label>
                        <select name="media_type" id="vid_media_type" class="cms-select">
                            <option value="article_guide">Educational Overview &amp; Transcript</option>
                            <option value="video_file">Uploaded Video File (MP4/WEBM)</option>
                            <option value="video_embed">External Video Link</option>
                        </select>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_duration_label">Duration / Format Label</label>
                        <input type="text" name="duration_label" id="vid_duration_label" class="cms-input" placeholder="e.g., 4 min video & transcript">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="vid_video_url">Video File Path or Approved External Video URL (Optional)</label>
                        <input type="text" name="video_url" id="vid_video_url" class="cms-input" placeholder="/cms/uploads/media/welcome.mp4 or https://...">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="vid_thumbnail_path">Thumbnail Image Path</label>
                        <select id="vid_thumb_picker" class="cms-select" style="margin-bottom: 0.4rem;">
                            <option value="">-- Choose Image from Media Library --</option>
                            <?php foreach ($mediaItems as $mi): if (strpos((string)$mi['mime_type'], 'image/') === 0): ?>
                                <option value="<?= e($mi['file_path']) ?>"><?= e($mi['original_name']) ?> (<?= e($mi['file_path']) ?>)</option>
                            <?php endif; endforeach; ?>
                        </select>
                        <input type="text" name="thumbnail_path" id="vid_thumbnail_path" class="cms-input" value="/assets/images/placeholders/hero-sanctuary.jpg">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_thumbnail_alt">Thumbnail Alt Text</label>
                        <input type="text" name="thumbnail_alt" id="vid_thumbnail_alt" class="cms-input">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_published_date">Publication Date</label>
                        <input type="date" name="published_date" id="vid_published_date" class="cms-input" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="vid_summary">Brief Summary Description *</label>
                        <textarea name="summary" id="vid_summary" class="cms-textarea" rows="3" required></textarea>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="vid_transcript_text">Captions &amp; Full Transcript Text</label>
                        <textarea name="transcript_text" id="vid_transcript_text" class="cms-textarea" rows="5"></textarea>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_status">Publication Status</label>
                        <select name="status" id="vid_status" class="cms-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="vid_sort_order">Display Order</label>
                        <input type="number" name="sort_order" id="vid_sort_order" class="cms-input" value="1">
                    </div>
                    <div class="cms-form-group full" style="display: flex; flex-direction: row; gap: 1.5rem; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="captions_available" id="vid_captions_available" value="1" checked>
                            <span>Captions / Transcript Available</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="is_welcome_video" id="vid_is_welcome_video" value="1">
                            <span>Set as Primary Homepage Welcome Video</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="videoModal">Cancel</button>
                <button type="submit" class="cms-btn cms-btn-primary">Save Resource</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.getElementById('vid_thumb_picker');
    const thumbInput = document.getElementById('vid_thumbnail_path');
    if (picker && thumbInput) {
        picker.addEventListener('change', function () {
            if (picker.value) thumbInput.value = picker.value;
        });
    }

    document.getElementById('btnNewVideo').addEventListener('click', function () {
        document.getElementById('videoForm').reset();
        document.getElementById('vid_id').value = '0';
        document.getElementById('videoModalTitle').textContent = 'Add Video or Educational Resource';
        CMS.openModal('videoModal');
    });

    document.querySelectorAll('.js-edit-video').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const data = JSON.parse(btn.getAttribute('data-video'));
            document.getElementById('vid_id').value = data.id;
            document.getElementById('vid_title').value = data.title || '';
            document.getElementById('vid_category').value = data.category || '';
            document.getElementById('vid_page_placement').value = data.page_placement || 'resources';
            document.getElementById('vid_media_type').value = data.media_type || 'article_guide';
            document.getElementById('vid_duration_label').value = data.duration_label || '';
            document.getElementById('vid_video_url').value = data.video_url || '';
            document.getElementById('vid_thumbnail_path').value = data.thumbnail_path || '';
            document.getElementById('vid_thumbnail_alt').value = data.thumbnail_alt || '';
            document.getElementById('vid_published_date').value = data.published_date || '';
            document.getElementById('vid_summary').value = data.summary || '';
            document.getElementById('vid_transcript_text').value = data.transcript_text || '';
            document.getElementById('vid_status').value = data.status || 'published';
            document.getElementById('vid_sort_order').value = data.sort_order || 1;
            document.getElementById('vid_captions_available').checked = parseInt(data.captions_available, 10) === 1;
            document.getElementById('vid_is_welcome_video').checked = parseInt(data.is_welcome_video, 10) === 1;
            document.getElementById('videoModalTitle').textContent = 'Edit Resource: ' + data.title;
            CMS.openModal('videoModal');
        });
    });
});
</script>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

