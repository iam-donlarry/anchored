<?php
/**
 * De AnchoredSelf CMS - Shared Media Library Module
 */

declare(strict_types=1);

$cmsActiveModule = 'media';
$cmsPageTitle    = 'Shared Media Library';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();
$mediaList = $pdo->query("SELECT * FROM media_library ORDER BY id DESC")->fetchAll();
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Approved Website Media Assets</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Upload, preview, copy asset paths, and remove unused media. Assets actively referenced on pages, services, events, or albums are protected against accidental deletion.
            </p>
        </div>
        <button type="button" class="cms-btn cms-btn-primary" onclick="CMS.openModal('uploadMediaModal')">
            <i data-lucide="upload"></i>
            <span>Upload Media Asset</span>
        </button>
    </div>

    <div class="cms-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem;">
            <?php foreach ($mediaList as $m): ?>
                <div style="border: 1px solid var(--cms-border); border-radius: 10px; overflow: hidden; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="height: 155px; background: var(--cms-navy-950); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <?php if (strpos((string)$m['mime_type'], 'video/') === 0): ?>
                                <video controls preload="none" style="width: 100%; height: 100%; object-fit: cover;">
                                    <source src="<?= e(resolve_asset_url($m['file_path'])) ?>" type="<?= e($m['mime_type']) ?>">
                                </video>
                            <?php else: ?>
                                <img src="<?= e(resolve_asset_url($m['file_path'])) ?>" alt="<?= e($m['alt_text']) ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div style="padding: 0.85rem 1rem;">
                            <div style="font-weight: 700; font-size: 0.9rem; color: var(--cms-navy-900); word-break: break-word;">
                                <?= e($m['original_name']) ?>
                            </div>
                            <div style="font-size: 0.76rem; color: var(--cms-muted); margin: 0.2rem 0 0.5rem;">
                                <?= e($m['mime_type']) ?> &bull; <?= round(((int)$m['file_size']) / 1024, 1) ?> KB
                            </div>
                            <input type="text" readonly class="cms-input" style="font-size: 0.76rem; padding: 0.35rem 0.5rem; background: #F8FAFC;" value="<?= e($m['file_path']) ?>" onclick="this.select();">
                        </div>
                    </div>
                    <div style="padding: 0.65rem 1rem; border-top: 1px solid var(--cms-border); background: #FAFBFD; display: flex; justify-content: space-between; align-items: center;">
                        <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" onclick="navigator.clipboard.writeText('<?= e($m['file_path']) ?>'); CMS.toast('Copied asset path to clipboard.');">
                            <i data-lucide="copy"></i>
                            <span>Copy Path</span>
                        </button>
                        <button type="button" class="cms-btn cms-btn-danger cms-btn-sm" onclick="CMS.actionRequest({action:'delete_media', id:<?= (int)$m['id'] ?>}, 'Remove this media item from the library?')">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Upload Media Modal -->
<div class="cms-modal-backdrop" id="uploadMediaModal" role="dialog" aria-modal="true">
    <div class="cms-modal" style="max-width: 540px;">
        <div class="cms-modal-header">
            <h3 style="margin: 0; color: var(--cms-navy-900);">Upload Media File</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="uploadMediaModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="uploadMediaModal" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_media">
            <div class="cms-modal-body">
                <div class="cms-form-group" style="margin-bottom: 1rem;">
                    <label class="cms-label" for="media_file">Select Image or Video File (JPG, PNG, WEBP, SVG, MP4, WEBM — Max 15 MB) *</label>
                    <input type="file" name="media_file" id="media_file" class="cms-input" required accept="image/jpeg,image/png,image/webp,image/svg+xml,video/mp4,video/webm">
                </div>
                <div class="cms-form-group" style="margin-bottom: 1rem;">
                    <label class="cms-label" for="media_alt">Descriptive Alternative Text *</label>
                    <input type="text" name="alt_text" id="media_alt" class="cms-input" required placeholder="Describe the image for screen readers">
                </div>
                <div class="cms-form-group">
                    <label class="cms-label" for="media_caption">Caption / Notes (Optional)</label>
                    <input type="text" name="caption" id="media_caption" class="cms-input">
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="uploadMediaModal">Cancel</button>
                <button type="submit" class="cms-btn cms-btn-primary">Upload File</button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

