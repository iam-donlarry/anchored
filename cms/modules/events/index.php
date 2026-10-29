<?php
/**
 * De AnchoredSelf CMS - Events Management Module (Upcoming & Move to Past Gallery)
 */

declare(strict_types=1);

$cmsActiveModule = 'events';
$cmsPageTitle    = 'Events Management';

require_once dirname(__DIR__, 2) . '/includes/header.php';

$pdo = Database::getConnection();
$events = $pdo->query("SELECT * FROM events ORDER BY event_state ASC, event_date DESC, id DESC")->fetchAll();
$mediaItems = $pdo->query("SELECT file_path, original_name FROM media_library WHERE mime_type LIKE 'image/%' ORDER BY id DESC")->fetchAll();
?>

<div class="cms-card">
    <div class="cms-card-header">
        <div>
            <h2 class="cms-card-title">Upcoming &amp; Past Events</h2>
            <p style="margin: 0.2rem 0 0; font-size: 0.85rem; color: var(--cms-muted);">
                Manage workshops, wellness sessions, and community gatherings. Completed events can be moved directly into the Past Events section and linked to a Past Events Gallery album with one click.
            </p>
        </div>
        <button type="button" class="cms-btn cms-btn-primary" id="btnNewEvent">
            <i data-lucide="calendar-plus"></i>
            <span>Add Event</span>
        </button>
    </div>

    <div class="cms-table-wrap">
        <table class="cms-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date &amp; Time</th>
                    <th>Format &amp; Location</th>
                    <th>Event State</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $ev): ?>
                    <tr>
                        <td>
                            <strong><?= e($ev['title']) ?></strong>
                            <?php if (!empty($ev['cost_info'])): ?>
                                <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($ev['cost_info']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= e($ev['event_date']) ?></div>
                            <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($ev['event_time']) ?> (<?= e($ev['timezone']) ?>)</div>
                        </td>
                        <td>
                            <strong><?= e($ev['format']) ?></strong>
                            <div style="font-size: 0.78rem; color: var(--cms-muted);"><?= e($ev['location']) ?></div>
                        </td>
                        <td>
                            <span class="status-pill status-<?= e($ev['event_state']) ?>"><?= e(ucfirst($ev['event_state'])) ?></span>
                        </td>
                        <td>
                            <span class="status-pill status-<?= e($ev['status']) ?>"><?= e(ucfirst($ev['status'])) ?></span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <?php if ($ev['event_state'] === 'upcoming'): ?>
                                <button type="button"
                                        class="cms-btn cms-btn-outline cms-btn-sm"
                                        onclick="CMS.actionRequest({action:'move_event_to_past', id:<?= (int)$ev['id'] ?>}, 'Move this completed event to Past Events and create a Past Events Gallery album?')">
                                    <i data-lucide="archive"></i>
                                    <span>Move to Past &amp; Gallery</span>
                                </button>
                            <?php endif; ?>
                            <button type="button"
                                    class="cms-btn cms-btn-outline cms-btn-sm js-edit-event"
                                    data-event="<?= e(json_encode($ev, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>">
                                <i data-lucide="edit-3"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button"
                                    class="cms-btn cms-btn-danger cms-btn-sm"
                                    onclick="CMS.actionRequest({action:'delete_event', id:<?= (int)$ev['id'] ?>}, 'Delete this event?')">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Event Modal -->
<div class="cms-modal-backdrop" id="eventModal" role="dialog" aria-modal="true" aria-labelledby="eventModalTitle">
    <div class="cms-modal">
        <div class="cms-modal-header">
            <h3 id="eventModalTitle" style="margin: 0; color: var(--cms-navy-900);">Add / Edit Event</h3>
            <button type="button" class="cms-btn cms-btn-outline cms-btn-sm" data-close-modal="eventModal">&times;</button>
        </div>
        <form class="js-cms-ajax-form" data-modal-id="eventModal" id="eventForm">
            <input type="hidden" name="action" value="save_event">
            <input type="hidden" name="id" id="ev_id" value="0">
            <div class="cms-modal-body">
                <div class="cms-form-grid">
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_title">Event Title *</label>
                        <input type="text" name="title" id="ev_title" class="cms-input" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_event_date">Event Date *</label>
                        <input type="date" name="event_date" id="ev_event_date" class="cms-input" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_event_time">Event Time *</label>
                        <input type="text" name="event_time" id="ev_event_time" class="cms-input" placeholder="e.g., 11:00 a.m. – 12:30 p.m." required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_timezone">Time Zone</label>
                        <input type="text" name="timezone" id="ev_timezone" class="cms-input" value="Eastern Time (ET)">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_format">Format</label>
                        <select name="format" id="ev_format" class="cms-select">
                            <option value="In-Person">In-Person</option>
                            <option value="Virtual">Virtual</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_location">Location or Virtual Format Details *</label>
                        <input type="text" name="location" id="ev_location" class="cms-input" value="403 Eastern Boulevard, Essex, Maryland 21221" required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_cost_info">Cost / Attendance Information (Informational Only)</label>
                        <input type="text" name="cost_info" id="ev_cost_info" class="cms-input" placeholder="e.g., Complimentary community session or Contact practice">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_registration_button_label">Button Label</label>
                        <input type="text" name="registration_button_label" id="ev_registration_button_label" class="cms-input" value="External Registration Info">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_external_registration_url">External Registration Link (Optional — Leave blank to direct to Routine Inquiry Form)</label>
                        <input type="url" name="external_registration_url" id="ev_external_registration_url" class="cms-input" placeholder="https://...">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_description">Event Description (Safe HTML Allowed) *</label>
                        <textarea name="description" id="ev_description" class="cms-textarea" rows="4" required></textarea>
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_accessibility_notes">Accessibility Information (Optional)</label>
                        <input type="text" name="accessibility_notes" id="ev_accessibility_notes" class="cms-input">
                    </div>
                    <div class="cms-form-group full">
                        <label class="cms-label" for="ev_image_path">Event Image Path</label>
                        <select id="ev_image_picker" class="cms-select" style="margin-bottom: 0.4rem;">
                            <option value="">-- Choose Image from Media Library --</option>
                            <?php foreach ($mediaItems as $mi): ?>
                                <option value="<?= e($mi['file_path']) ?>"><?= e($mi['original_name']) ?> (<?= e($mi['file_path']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="image_path" id="ev_image_path" class="cms-input" value="/assets/images/placeholders/community-workshop.jpg">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_event_state">Event State</label>
                        <select name="event_state" id="ev_event_state" class="cms-select">
                            <option value="upcoming">Upcoming Event</option>
                            <option value="past">Past / Completed Event</option>
                        </select>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label" for="ev_status">Publication Status</label>
                        <select name="status" id="ev_status" class="cms-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-outline" data-close-modal="eventModal">Cancel</button>
                <button type="submit" class="cms-btn cms-btn-primary">Save Event</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.getElementById('ev_image_picker');
    const imgInput = document.getElementById('ev_image_path');
    if (picker && imgInput) {
        picker.addEventListener('change', function () {
            if (picker.value) imgInput.value = picker.value;
        });
    }

    document.getElementById('btnNewEvent').addEventListener('click', function () {
        document.getElementById('eventForm').reset();
        document.getElementById('ev_id').value = '0';
        document.getElementById('eventModalTitle').textContent = 'Create New Event';
        CMS.openModal('eventModal');
    });

    document.querySelectorAll('.js-edit-event').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const data = JSON.parse(btn.getAttribute('data-event'));
            document.getElementById('ev_id').value = data.id;
            document.getElementById('ev_title').value = data.title || '';
            document.getElementById('ev_event_date').value = data.event_date || '';
            document.getElementById('ev_event_time').value = data.event_time || '';
            document.getElementById('ev_timezone').value = data.timezone || 'Eastern Time (ET)';
            document.getElementById('ev_format').value = data.format || 'Virtual';
            document.getElementById('ev_location').value = data.location || '';
            document.getElementById('ev_cost_info').value = data.cost_info || '';
            document.getElementById('ev_registration_button_label').value = data.registration_button_label || '';
            document.getElementById('ev_external_registration_url').value = data.external_registration_url || '';
            document.getElementById('ev_description').value = data.description || '';
            document.getElementById('ev_accessibility_notes').value = data.accessibility_notes || '';
            document.getElementById('ev_image_path').value = data.image_path || '';
            document.getElementById('ev_event_state').value = data.event_state || 'upcoming';
            document.getElementById('ev_status').value = data.status || 'published';
            document.getElementById('eventModalTitle').textContent = 'Edit Event: ' + data.title;
            CMS.openModal('eventModal');
        });
    });
});
</script>

<?php require_once dirname(__DIR__, 2) . '/includes/footer.php'; ?>

