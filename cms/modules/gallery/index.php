<?php
/**
 * De AnchoredSelf CMS - Past Events Gallery Module (Albums & Media)
 */

declare(strict_types=1);

$cmsActiveModule = 'gallery';
$cmsPageTitle    = 'Past Events Gallery Management';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();
$albums = $pdo->query("SELECT * FROM gallery_albums ORDER BY sort_order ASC, event_date DESC, id DESC")->fetchAll();
$mediaStmt = $pdo->prepare("SELECT * FROM gallery_media WHERE album_id = :aid ORDER BY sort_order ASC, id ASC");
foreach ($albums as &$al) {
    $mediaStmt->execute([':aid' => $al['id']]);
    $al['items'] = $mediaStmt->fetchAll();
}
unset($al);

$libraryImages = $pdo->query("SELECT file_path, original_name FROM media_library WHERE mime_type LIKE 'image/%' ORDER BY id DESC")->fetchAll();
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Past Events Gallery Albums</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Organize photographs and videos from past workshops and community gatherings into albums. Publish identifiable participant images only with appropriate permission.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button type="button" class="cms-btn cms-btn-outline" id="btnAddGalleryMedia">
                <i data-lucide="image-plus"></i>
                <span>Add Photo to Album</span>
            </button>
            <button type="button" class="cms-btn cms-btn-primary" id="btnNewAlbum">
                <i data-lucide="folder-plus"></i>
                <span>Create Album</span>
            </button>
        </div>
    </div>

    <div class="cms-card-body">
        <?php foreach ($albums as $album): ?>
            <div style="border: 1px solid var(--cms-border); border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; background: #FAFBFD;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 0.75rem;">
                    <div>
                        <h3 style="margin: 0 0 0.25rem; color: var(--cms-navy-900); font-size: 1.12rem;">
                            <?= e($album['title']) ?>
                            <span class="status-pill status-<?= e($album['status']) ?>" style="margin-left: 8px;"><?= e(ucfirst($album['status'])) ?></span>
                        </h3>
                        <div style="font-size: 0.82rem; color: var(--cms-muted);">
                            Event Date: <?= e($album['event_date'] ?: 'N/A') ?> &bull; Permission Confirmed: <?= (int)$album['permission_confirmed'] === 1 ? 'Yes' : 'No' ?>
                        </div>
                        <p style="margin: 0.45rem 0 0; font-size: 0.9rem; color: var(--cms-text);"><?= e($album['description']) ?></p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button"
                                class="cms-btn cms-btn-outline cms-btn-sm js-edit-album"
                                data-album="<?= e(json_encode($album, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>">
                            <i data-lucide="edit-3"></i>
                            <span>Edit Album</span>
                        </button>
                        <button type="button"
                                class="cms-btn cms-btn-danger cms-btn-sm"
                                onclick="CMS.actionRequest({action:'delete_album', id:<?= (int)$album['id'] ?>}, 'Delete this album and all its photos?')">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Album Media Items -->
                <?php if (!empty($album['items'])): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <?php foreach ($album['items'] as $item): ?>
                            <div style="background: #FFFFFF; border: 1px solid var(--cms-border); border-radius: 8px; overflow: hidden;">
                                <img src="<?= e(resolve_asset_url($item['file_path'])) ?>" alt="<?= e($item['alt_text']) ?>" style="width: 100%; height: 130px; object-fit: cover;">
                                <div style="padding: 0.65rem 0.8rem;">
                                    <div style="font-weight: 600; font-size: 0.85rem; color: var(--cms-navy-900);"><?= e($item['title']) ?></div>
                                    <div style="font-size: 0.76rem; color: var(--cms-muted); margin-top: 0.15rem;"><?= e($item['caption']) ?></div>
                                    <button type="button"
                                            class="cms-btn cms-btn-danger cms-btn-sm"
                                            style="margin-top: 0.5rem; width: 100%;"
                                            onclick="CMS.actionRequest({action:'delete_gallery_media', id:<?= (int)$item['id'] ?>}, 'Remove this photo from the album?')">
                                        <span>Remove Photo</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="font-size: 0.84rem; color: var(--cms-muted); margin-top: 0.5rem;">No media items added to this album yet.</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Album Modal -->
<div class="cms-modal-backdrop" id="albumModal" role="dialog" aria-modal="true">
    <div class="cms-modal">
        <div class="cms-modal-header">
            <h3 id="albumModalTitle" style="margin: 0; color: var(--cms-navy-900);">Create / Edit Gallery Album</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="albumModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="albumModal" id="albumForm">
            <input type="hidden" name="action" value="save_album">
            <input type="hidden" name="id" id="alb_id" value="0">
            <div class="cms-modal-body">
                <div class="cms-form-grid">
                    <div class="cms-form-group full">
                        <label class="cms-label" for="alb_title">Album Title *</label>
                        <input type="text" name="title" id="alb_title" class="cms-input" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="alb_event_date">Event Date</label>
                        <input type="date" name="event_date" id="alb_event_date" class="cms-input">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="alb_status">Publication Status</label>
                        <select name="status" id="alb_status" class="cms-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="alb_description">Album Description *</label>
                        <textarea name="description" id="alb_description" class="cms-textarea" rows="3" required></textarea>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="alb_cover_image">Cover Image Path</label>
                        <input type="text" name="cover_image" id="alb_cover_image" class="cms-input" value="/assets/images/placeholders/community-workshop.jpg">
                    </div>
                    <div class="cms-form-group full">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="permission_confirmed" id="alb_permission_confirmed" value="1" checked required>
                            <span>I confirm that any identifiable participant photographs in this album have appropriate permission for public display (and contain no client session information).</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="albumModal">Cancel</button>
                <button type="submit" class="cms-btn cms-btn-primary">Save Album</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Media to Album Modal -->
<div class="cms-modal-backdrop" id="galleryMediaModal" role="dialog" aria-modal="true">
    <div class="cms-modal">
        <div class="cms-modal-header">
            <h3 style="margin: 0; color: var(--cms-navy-900);">Add Photograph to Gallery Album</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="galleryMediaModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="galleryMediaModal" id="galleryMediaForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_gallery_media">
            <div class="cms-modal-body">
                <div class="cms-form-grid">
                    <div class="cms-form-group full">
                        <label class="cms-label" for="gm_album_id">Select Album *</label>
                        <select name="album_id" id="gm_album_id" class="cms-select" required>
                            <?php foreach ($albums as $al): ?>
                                <option value="<?= (int)$al['id'] ?>"><?= e($al['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="gm_title">Photo Title *</label>
                        <input type="text" name="title" id="gm_title" class="cms-input" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="gm_alt_text">Descriptive Alt Text *</label>
                        <input type="text" name="alt_text" id="gm_alt_text" class="cms-input" required>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="gm_upload_file">Upload New Image File (JPG/PNG/WEBP)</label>
                        <input type="file" name="upload_file" id="gm_upload_file" class="cms-input" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="gm_file_path">Or Select Existing Image from Media Library</label>
                        <select name="file_path" id="gm_file_path" class="cms-select">
                            <option value="">-- Select from Media Library if not uploading --</option>
                            <?php foreach ($libraryImages as $li): ?>
                                <option value="<?= e($li['file_path']) ?>"><?= e($li['original_name']) ?> (<?= e($li['file_path']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="gm_caption">Caption</label>
                        <input type="text" name="caption" id="gm_caption" class="cms-input">
                    </div>
                    <div class="cms-form-group full">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="permission_verified" value="1" checked required>
                            <span>Permission verified for public publication.</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="galleryMediaModal">Cancel</button>
                <button type="submit" class="cms-btn cms-btn-primary">Add Photo to Album</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btnNewAlbum').addEventListener('click', function () {
        document.getElementById('albumForm').reset();
        document.getElementById('alb_id').value = '0';
        document.getElementById('albumModalTitle').textContent = 'Create Gallery Album';
        CMS.openModal('albumModal');
    });

    document.getElementById('btnAddGalleryMedia').addEventListener('click', function () {
        document.getElementById('galleryMediaForm').reset();
        CMS.openModal('galleryMediaModal');
    });

    document.querySelectorAll('.js-edit-album').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const data = JSON.parse(btn.getAttribute('data-album'));
            document.getElementById('alb_id').value = data.id;
            document.getElementById('alb_title').value = data.title || '';
            document.getElementById('alb_event_date').value = data.event_date || '';
            document.getElementById('alb_status').value = data.status || 'published';
            document.getElementById('alb_description').value = data.description || '';
            document.getElementById('alb_cover_image').value = data.cover_image || '';
            document.getElementById('alb_permission_confirmed').checked = parseInt(data.permission_confirmed, 10) === 1;
            document.getElementById('albumModalTitle').textContent = 'Edit Album: ' + data.title;
            CMS.openModal('albumModal');
        });
    });
});
</script>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

