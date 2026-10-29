<?php
/**
 * De AnchoredSelf CMS - Protected Routine Appointment Inquiries Module
 * Strictly for viewing, filtering, marking as reviewed, archiving, and deleting routine inquiries.
 * No automated follow-up sequences or appointment booking engines.
 */

declare(strict_types=1);

$cmsActiveModule = 'inquiries';
$cmsPageTitle    = 'Routine Appointment Inquiries';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();

$statusFilter = trim((string)($_GET['status'] ?? ''));
$searchQuery  = trim((string)($_GET['q'] ?? ''));

$sql = "SELECT i.*, a.full_name AS reviewer_name
        FROM inquiries i
        LEFT JOIN cms_admins a ON i.reviewed_by = a.id
        WHERE 1=1";
$params = [];

if (in_array($statusFilter, ['new', 'reviewed', 'archived'], true)) {
    $sql .= " AND i.status = :status";
    $params[':status'] = $statusFilter;
}

if ($searchQuery !== '') {
    $sql .= " AND (i.full_name LIKE :q1 OR i.email LIKE :q2 OR i.phone LIKE :q3 OR i.service_of_interest LIKE :q4)";
    $like = '%' . $searchQuery . '%';
    $params[':q1'] = $like;
    $params[':q2'] = $like;
    $params[':q3'] = $like;
    $params[':q4'] = $like;
}

$sql .= " ORDER BY i.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Routine Website Appointment Inquiries</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Review contact details submitted through the public inquiry form. Practice staff personally manage follow-up correspondence through ordinary practice telephone or email communication.
            </p>
        </div>

        <form method="get" action="" style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
            <select name="status" class="cms-select" style="width: auto;">
                <option value="">All Statuses</option>
                <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
                <option value="reviewed" <?= $statusFilter === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                <option value="archived" <?= $statusFilter === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
            <input type="search" name="q" class="cms-input" style="width: 220px;" placeholder="Search name, phone, service..." value="<?= e($searchQuery) ?>">
            <button type="submit" class="cms-btn cms-btn-primary cms-btn-sm">Filter</button>
            <?php if ($statusFilter !== '' || $searchQuery !== ''): ?>
                <a href="<?= e(CMS_BASE_URL) ?>/modules/inquiries/" class="cms-btn cms-btn-outline cms-btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="cms-table-wrap">
        <table class="cms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prospective Client &amp; Age Group</th>
                    <th>Contact &amp; Preferred Method</th>
                    <th>Service &amp; Format</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($inquiries)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--cms-muted);">
                            No inquiries match your current filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td>#<?= (int)$inq['id'] ?></td>
                            <td>
                                <strong><?= e($inq['full_name']) ?></strong>
                                <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($inq['age_group']) ?></div>
                                <?php if ((int)$inq['is_child_inquiry'] === 1): ?>
                                    <span class="cms-badge-pill" style="margin-top: 2px; display: inline-block;">Guardian: <?= e($inq['guardian_name']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($inq['phone'])): ?>
                                    <div><a href="<?= e(format_tel_href((string)$inq['phone'])) ?>"><?= e($inq['phone']) ?></a></div>
                                <?php endif; ?>
                                <?php if (!empty($inq['email'])): ?>
                                    <div style="font-size: 0.82rem;"><a href="mailto:<?= e($inq['email']) ?>"><?= e($inq['email']) ?></a></div>
                                <?php endif; ?>
                                <div style="font-size: 0.75rem; color: var(--cms-muted);">Prefers: <?= e($inq['preferred_contact']) ?></div>
                            </td>
                            <td>
                                <strong><?= e($inq['service_of_interest']) ?></strong>
                                <div style="font-size: 0.78rem; color: var(--cms-muted);">
                                    <?= e($inq['preferred_format']) ?> &bull; State: <?= e($inq['service_state']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="status-pill status-<?= e($inq['status']) ?>"><?= e(ucfirst($inq['status'])) ?></span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--cms-muted);">
                                <?= e(date('M j, Y g:i A', strtotime((string)$inq['created_at']))) ?>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <button type="button"
                                        class="cms-btn cms-btn-outline cms-btn-sm js-view-inquiry"
                                        data-inquiry="<?= e(json_encode($inq, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>">
                                    <i data-lucide="eye"></i>
                                    <span>View / Update</span>
                                </button>
                                <button type="button"
                                        class="cms-btn cms-btn-danger cms-btn-sm"
                                        onclick="CMS.actionRequest({action:'delete_inquiry', id:<?= (int)$inq['id'] ?>}, 'Permanently delete this inquiry record per retention policy?')">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- View / Update Inquiry Modal -->
<div class="cms-modal-backdrop" id="inquiryModal" role="dialog" aria-modal="true">
    <div class="cms-modal">
        <div class="cms-modal-header">
            <h3 id="inquiryModalTitle" style="margin: 0; color: var(--cms-navy-900);">Inquiry Details</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="inquiryModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="inquiryModal" id="inquiryUpdateForm">
            <input type="hidden" name="action" value="update_inquiry_status">
            <input type="hidden" name="id" id="inq_id" value="0">
            <div class="cms-modal-body">
                <div id="inq_details_box" style="background: #F8FAFC; border: 1px solid var(--cms-border); border-radius: 8px; padding: 1.15rem; margin-bottom: 1.25rem; font-size: 0.92rem;"></div>

                <div class="cms-form-grid">
                    <div class="cms-form-group">
                        <label class="cms-label" for="inq_status">Inquiry Review Status</label>
                        <select name="status" id="inq_status" class="cms-select">
                            <option value="new">New</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="inq_admin_notes">Administrative Notes (Internal Only — Avoid Sensitive Clinical Details)</label>
                        <textarea name="admin_notes" id="inq_admin_notes" class="cms-textarea" rows="3" placeholder="e.g., Called prospective client on 10/30 to discuss availability."></textarea>
                    </div>
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="inquiryModal">Close</button>
                <button type="submit" class="cms-btn cms-btn-primary">Update Status &amp; Notes</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-view-inquiry').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = JSON.parse(btn.getAttribute('data-inquiry'));
            document.getElementById('inq_id').value = d.id;
            document.getElementById('inq_status').value = d.status || 'reviewed';
            document.getElementById('inq_admin_notes').value = d.admin_notes || '';
            document.getElementById('inquiryModalTitle').textContent = 'Inquiry #' + d.id + ' — ' + d.full_name;

            const box = document.getElementById('inq_details_box');
            box.innerHTML = '';
            const rows = [
                ['Name', d.full_name],
                ['Telephone', d.phone || 'Not provided'],
                ['Email', d.email || 'Not provided'],
                ['Preferred Contact Method', d.preferred_contact],
                ['Age Group', d.age_group],
                ['Parent / Guardian', parseInt(d.is_child_inquiry, 10) === 1 ? ((d.guardian_name || '') + ' (' + (d.guardian_relationship || 'Guardian') + ') — ' + (d.guardian_contact || '')) : 'N/A'],
                ['State Where Services Received', d.service_state],
                ['Service of Interest', d.service_of_interest],
                ['Preferred Appointment Format', d.preferred_format],
                ['Submitted Message', d.message || '(No optional message included)'],
                ['Submitted At', d.created_at]
            ];
            rows.forEach(function (r) {
                const p = document.createElement('div');
                p.style.marginBottom = '0.45rem';
                const strong = document.createElement('strong');
                strong.textContent = r[0] + ': ';
                const span = document.createElement('span');
                span.textContent = r[1];
                p.appendChild(strong);
                p.appendChild(span);
                box.appendChild(p);
            });

            CMS.openModal('inquiryModal');
        });
    });
});
</script>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

