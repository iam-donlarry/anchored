<?php
/**
 * De AnchoredSelf CMS - Dashboard (dashboard.php)
 * Strictly scoped to CMS-managed areas: Events, Videos & Resources, Gallery & Media, and Appointment Inquiries.
 */

declare(strict_types=1);

$cmsActiveModule = 'dashboard';
$cmsPageTitle    = 'Practice CMS Dashboard';

require_once __DIR__ . '/includes/header.php';

$pdo = Database::getConnection();

$pubVideos      = (int)$pdo->query("SELECT COUNT(*) FROM videos_resources WHERE status = 'published'")->fetchColumn();
$draftVideos    = (int)$pdo->query("SELECT COUNT(*) FROM videos_resources WHERE status = 'draft'")->fetchColumn();
$upcomingEvents = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_state = 'upcoming' AND status = 'published'")->fetchColumn();
$pastEvents     = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_state = 'past'")->fetchColumn();
$galleryAlbums  = (int)$pdo->query("SELECT COUNT(*) FROM gallery_albums WHERE status = 'published'")->fetchColumn();
$mediaCount     = (int)$pdo->query("SELECT COUNT(*) FROM media_library")->fetchColumn();
$newInquiries   = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$revInquiries   = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'reviewed'")->fetchColumn();

$recentInquiries = $pdo->query("
    SELECT id, full_name, preferred_contact, age_group, service_state, service_of_interest, preferred_format, status, created_at
    FROM inquiries
    ORDER BY created_at DESC
    LIMIT 6
")->fetchAll();

$recentAudit = $pdo->query("
    SELECT a.*, adm.full_name AS admin_name
    FROM audit_logs a
    LEFT JOIN cms_admins adm ON a.admin_id = adm.id
    ORDER BY a.created_at DESC
    LIMIT 8
")->fetchAll();
?>

<div class="cms-stats-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="cms-stat-card gold">
        <div>
            <div class="cms-stat-label">Appointment Inquiries</div>
            <div class="cms-stat-value"><?= $newInquiries ?> <span style="font-size: 0.9rem; font-weight: 500; color: var(--cms-muted);">New</span></div>
            <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= $revInquiries ?> Reviewed</div>
        </div>
        <div class="cms-stat-icon"><i data-lucide="inbox"></i></div>
    </div>

    <div class="cms-stat-card">
        <div>
            <div class="cms-stat-label">Upcoming Events</div>
            <div class="cms-stat-value"><?= $upcomingEvents ?></div>
            <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= $pastEvents ?> Completed / Past Events</div>
        </div>
        <div class="cms-stat-icon"><i data-lucide="calendar"></i></div>
    </div>

    <div class="cms-stat-card">
        <div>
            <div class="cms-stat-label">Videos &amp; Resources</div>
            <div class="cms-stat-value"><?= $pubVideos ?></div>
            <div style="font-size: 0.78rem; color: var(--cms-muted);">Published &bull; <?= $draftVideos ?> Draft</div>
        </div>
        <div class="cms-stat-icon"><i data-lucide="play-circle"></i></div>
    </div>

    <div class="cms-stat-card">
        <div>
            <div class="cms-stat-label">Event Gallery &amp; Media</div>
            <div class="cms-stat-value"><?= $galleryAlbums ?></div>
            <div style="font-size: 0.78rem; color: var(--cms-muted);">Albums &bull; <?= $mediaCount ?> Media Assets</div>
        </div>
        <div class="cms-stat-icon"><i data-lucide="images"></i></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.5rem;" class="cms-form-grid">
    <!-- Recent Routine Appointment Inquiries -->
    <div class="cms-card">
        <div class="cms-card-header">
            <h2 class="cms-card-title">Recent Appointment Inquiries</h2>
            <a href="<?= e(CMS_BASE_URL) ?>/modules/inquiries/" class="cms-btn cms-btn-outline cms-btn-sm">
                <span>Manage All Inquiries</span>
                <i data-lucide="arrow-right"></i>
            </a>
        </div>
        <div class="cms-table-wrap">
            <table class="cms-table">
                <thead>
                    <tr>
                        <th>Name &amp; Age Group</th>
                        <th>Service of Interest</th>
                        <th>Format / State</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentInquiries)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--cms-muted); padding: 2rem;">
                                No routine appointment inquiries submitted yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentInquiries as $inq): ?>
                            <tr>
                                <td>
                                    <strong><?= e($inq['full_name']) ?></strong>
                                    <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($inq['age_group']) ?></div>
                                </td>
                                <td><?= e($inq['service_of_interest']) ?></td>
                                <td>
                                    <?= e($inq['preferred_format']) ?>
                                    <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($inq['service_state']) ?></div>
                                </td>
                                <td>
                                    <span class="status-pill status-<?= e($inq['status']) ?>"><?= e(ucfirst($inq['status'])) ?></span>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--cms-muted);">
                                    <?= e(date('M j, Y g:i A', strtotime((string)$inq['created_at']))) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Management Shortcuts & Audit Trail -->
    <div>
        <div class="cms-card">
            <div class="cms-card-header">
                <h2 class="cms-card-title">Quick Content Actions</h2>
            </div>
            <div class="cms-card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <a href="<?= e(CMS_BASE_URL) ?>/modules/events/" class="cms-btn cms-btn-outline">
                    <i data-lucide="calendar-plus"></i>
                    <span>Manage Events</span>
                </a>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/videos/" class="cms-btn cms-btn-outline">
                    <i data-lucide="video"></i>
                    <span>Videos &amp; Resources</span>
                </a>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/gallery/" class="cms-btn cms-btn-outline">
                    <i data-lucide="images"></i>
                    <span>Event Gallery Albums</span>
                </a>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/media/" class="cms-btn cms-btn-outline">
                    <i data-lucide="folder-kanban"></i>
                    <span>Media Library</span>
                </a>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/inquiries/" class="cms-btn cms-btn-outline">
                    <i data-lucide="inbox"></i>
                    <span>Review Inquiries</span>
                </a>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/settings/" class="cms-btn cms-btn-outline">
                    <i data-lucide="sliders"></i>
                    <span>Practice Settings</span>
                </a>
            </div>
        </div>

        <div class="cms-card">
            <div class="cms-card-header">
                <h2 class="cms-card-title">Recent Administrative Activity</h2>
            </div>
            <div class="cms-card-body" style="padding: 0.75rem 1.25rem;">
                <?php if (empty($recentAudit)): ?>
                    <p style="color: var(--cms-muted); font-size: 0.88rem; margin: 0.5rem 0;">No administrative actions recorded yet.</p>
                <?php else: ?>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php foreach ($recentAudit as $log): ?>
                            <li style="padding: 0.55rem 0; border-bottom: 1px solid var(--cms-border); font-size: 0.85rem;">
                                <div style="font-weight: 600; color: var(--cms-navy-900);"><?= e($log['summary']) ?></div>
                                <div style="color: var(--cms-muted); font-size: 0.76rem;">
                                    <?= e($log['admin_name'] ?: 'System') ?> &bull; <?= e(date('M j, g:i A', strtotime((string)$log['created_at']))) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
