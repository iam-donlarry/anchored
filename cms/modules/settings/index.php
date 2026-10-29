<?php
/**
 * De AnchoredSelf CMS - Practice Contact & General Settings Module
 */

declare(strict_types=1);

$cmsActiveModule = 'settings';
$cmsPageTitle    = 'Practice Settings';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();
$allSettings = $pdo->query("SELECT * FROM site_settings ORDER BY is_policy ASC, id ASC")->fetchAll();

$generalSettings = array_filter($allSettings, static fn($s) => (int)$s['is_policy'] === 0);
$policySettings  = array_filter($allSettings, static fn($s) => (int)$s['is_policy'] === 1);
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Practice Contact Information &amp; General Settings</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Update global contact details, office hours, and inquiry form confirmation notices. Note: Public website pages, service descriptions, and FAQs are defined directly in the website source code.
            </p>
        </div>
    </div>

    <form class="js-cms-ajax-form" id="settingsForm">
        <input type="hidden" name="action" value="save_settings">
        <div class="cms-card-body">
            <h3 style="margin: 0 0 1rem; font-size: 1rem; color: var(--cms-navy-900);">Contact, Hours &amp; General Settings</h3>
            <div class="cms-form-grid" style="margin-bottom: 2rem;">
                <?php foreach ($generalSettings as $gs): ?>
                    <div class="cms-form-group <?= mb_strlen((string)$gs['setting_value']) > 85 ? 'full' : '' ?>">
                        <label class="cms-label" for="set_<?= e($gs['setting_key']) ?>">
                            <?= e($gs['label']) ?> <code style="font-size: 0.72rem; color: var(--cms-muted);">(<?= e($gs['setting_key']) ?>)</code>
                        </label>
                        <?php if (mb_strlen((string)$gs['setting_value']) > 85): ?>
                            <textarea name="settings[<?= e($gs['setting_key']) ?>]" id="set_<?= e($gs['setting_key']) ?>" class="cms-textarea" rows="2"><?= e($gs['setting_value']) ?></textarea>
                        <?php else: ?>
                            <input type="text" name="settings[<?= e($gs['setting_key']) ?>]" id="set_<?= e($gs['setting_key']) ?>" class="cms-input" value="<?= e($gs['setting_value']) ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($policySettings)): ?>
            <div style="background: #FFFAEB; border: 1px solid #FEDF89; border-left: 4px solid #D97706; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <h3 style="margin: 0 0 0.35rem; font-size: 1rem; color: #92400E;">Reviewed Practice &amp; Inquiry Form Notices</h3>
                <p style="margin: 0 0 1rem; font-size: 0.85rem; color: #92400E;">
                    To prevent silent publication of unreviewed policy edits, check the confirmation box below when saving changes to any policy or inquiry notice statement.
                </p>
                <div class="cms-form-grid">
                    <?php foreach ($policySettings as $ps): ?>
                        <div class="cms-form-group full">
                            <label class="cms-label" for="set_<?= e($ps['setting_key']) ?>"><?= e($ps['label']) ?></label>
                            <textarea name="settings[<?= e($ps['setting_key']) ?>]" id="set_<?= e($ps['setting_key']) ?>" class="cms-textarea" rows="2"><?= e($ps['setting_value']) ?></textarea>
                        </div>
                    <?php endforeach; ?>
                </div>
                <label style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; font-weight: 600; color: #92400E; cursor: pointer;">
                    <input type="checkbox" name="confirm_policy_publish" value="1">
                    <span>I have reviewed any notice modifications above and authorize publishing them.</span>
                </label>
            </div>
            <?php endif; ?>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="cms-btn cms-btn-primary">
                    <i data-lucide="save"></i>
                    <span>Save Practice Settings</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>
