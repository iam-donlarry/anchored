<?php
/**
 * De AnchoredSelf CMS - Unified Protected Action & Form Handler (api.php)
 * Strictly scoped to CMS-managed areas:
 *  - Videos & Educational Resources (`videos_resources`)
 *  - Events (`events`)
 *  - Past Events Gallery & Media (`gallery_albums`, `gallery_media`, `media_library`)
 *  - Appointment Inquiries (`inquiries`)
 *  - Global Contact/Practice Settings (`site_settings`)
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

Auth::requireLogin(true);
$admin = Auth::currentUser();
$adminId = (int)($admin['id'] ?? 0);

if (!verify_csrf_token()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid or expired security token. Please refresh the page.']);
    exit;
}

$action = trim((string)($_POST['action'] ?? ''));
$pdo = Database::getConnection();

function json_error(string $msg, int $code = 422): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

function json_success(string $msg, array $extra = []): void
{
    echo json_encode(array_merge(['success' => true, 'message' => $msg], $extra));
    exit;
}

/**
 * Helper to validate and store an uploaded image/video file safely
 */
function handle_safe_upload(array $file, int $adminId, string $altText = '', string $caption = ''): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File upload failed or no file was selected.');
    }

    if ((int)$file['size'] <= 0 || (int)$file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('File size exceeds the maximum permitted limit (15 MB).');
    }

    $tmpPath = (string)$file['tmp_name'];
    if (!is_uploaded_file($tmpPath)) {
        throw new RuntimeException('Invalid upload source.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = (string)$finfo->file($tmpPath);

    $allowedMimes = [
        'image/jpeg'    => 'jpg',
        'image/png'     => 'png',
        'image/webp'    => 'webp',
        'image/svg+xml' => 'svg',
        'video/mp4'     => 'mp4',
        'video/webm'    => 'webm',
    ];

    if (!isset($allowedMimes[$mime])) {
        throw new RuntimeException('Unsupported file type (' . htmlspecialchars($mime) . '). Allowed: JPG, PNG, WEBP, SVG, MP4, WEBM.');
    }

    $ext = $allowedMimes[$mime];

    if ($ext === 'svg') {
        $svgContent = (string)file_get_contents($tmpPath);
        if (preg_match('/<script|onload|onerror|javascript:/i', $svgContent)) {
            throw new RuntimeException('SVG file contains disallowed active script content.');
        }
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $safeFileName  = 'media_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $destDiskPath  = UPLOAD_DIR . $safeFileName;
    $publicRelPath = '/cms/uploads/media/' . $safeFileName;

    if (!move_uploaded_file($tmpPath, $destDiskPath)) {
        throw new RuntimeException('Could not save uploaded file to storage.');
    }

    $pdo      = Database::getConnection();
    $origName = mb_substr(basename((string)$file['name']), 0, 200);
    $stmt     = $pdo->prepare("
        INSERT INTO media_library (file_name, original_name, file_path, mime_type, file_size, alt_text, caption, uploaded_by)
        VALUES (:fn, :on, :fp, :mt, :fs, :alt, :cap, :ub)
    ");
    $stmt->execute([
        ':fn'  => $safeFileName,
        ':on'  => $origName,
        ':fp'  => $publicRelPath,
        ':mt'  => $mime,
        ':fs'  => (int)$file['size'],
        ':alt' => mb_substr(trim($altText ?: $origName), 0, 220),
        ':cap' => mb_substr(trim($caption), 0, 250),
        ':ub'  => $adminId ?: null,
    ]);

    return [
        'id'        => (int)$pdo->lastInsertId(),
        'file_path' => $publicRelPath,
        'mime_type' => $mime,
    ];
}

try {
    switch ($action) {
        // ====================================================================
        // 1. VIDEOS & EDUCATIONAL RESOURCES MANAGEMENT
        // ====================================================================
        case 'save_video': {
            $id                = (int)($_POST['id'] ?? 0);
            $title             = trim((string)($_POST['title'] ?? ''));
            $category          = trim((string)($_POST['category'] ?? 'General Wellness'));
            $pagePlacement     = trim((string)($_POST['page_placement'] ?? 'resources'));
            $summary           = trim((string)($_POST['summary'] ?? ''));
            $mediaType         = in_array($_POST['media_type'] ?? '', ['video_file', 'video_embed', 'article_guide'], true) ? $_POST['media_type'] : 'article_guide';
            $videoUrl          = sanitize_safe_url($_POST['video_url'] ?? '');
            $thumbnailPath     = sanitize_safe_url($_POST['thumbnail_path'] ?? '/assets/images/placeholders/hero-sanctuary.jpg');
            $thumbnailAlt      = trim((string)($_POST['thumbnail_alt'] ?? $title));
            $durationLabel     = trim((string)($_POST['duration_label'] ?? ''));
            $captionsAvailable = !empty($_POST['captions_available']) ? 1 : 0;
            $transcriptText    = trim((string)($_POST['transcript_text'] ?? ''));
            $publishedDate     = !empty($_POST['published_date']) ? $_POST['published_date'] : date('Y-m-d');
            $isWelcomeVideo    = !empty($_POST['is_welcome_video']) ? 1 : 0;
            $sortOrder         = (int)($_POST['sort_order'] ?? 0);
            $status            = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

            if ($title === '' || $summary === '') {
                json_error('Resource title and summary description are required.');
            }

            if ($isWelcomeVideo === 1) {
                $pdo->exec("UPDATE videos_resources SET is_welcome_video = 0");
            }

            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE videos_resources SET
                        title = :title,
                        category = :cat,
                        page_placement = :place,
                        summary = :sum,
                        media_type = :mtype,
                        video_url = :vurl,
                        thumbnail_path = :thumb,
                        thumbnail_alt = :talt,
                        duration_label = :dur,
                        captions_available = :cap,
                        transcript_text = :trans,
                        published_date = :pdate,
                        is_welcome_video = :wel,
                        sort_order = :ord,
                        status = :status
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':cat'    => $category,
                    ':place'  => $pagePlacement,
                    ':sum'    => $summary,
                    ':mtype'  => $mediaType,
                    ':vurl'   => $videoUrl,
                    ':thumb'  => $thumbnailPath ?: '/assets/images/placeholders/hero-sanctuary.jpg',
                    ':talt'   => $thumbnailAlt ?: $title,
                    ':dur'    => $durationLabel,
                    ':cap'    => $captionsAvailable,
                    ':trans'  => $transcriptText,
                    ':pdate'  => $publishedDate,
                    ':wel'    => $isWelcomeVideo,
                    ':ord'    => $sortOrder,
                    ':status' => $status,
                    ':id'     => $id,
                ]);
                Repository::logAudit($adminId, 'update_video', 'videos_resources', $id, "Updated resource: {$title}");
                json_success('Video/resource updated successfully.');
            } else {
                $slug = slugify($title);
                $stmt = $pdo->prepare("
                    INSERT INTO videos_resources (title, slug, category, page_placement, summary, media_type, video_url, thumbnail_path, thumbnail_alt, duration_label, captions_available, transcript_text, published_date, is_welcome_video, sort_order, status)
                    VALUES (:title, :slug, :cat, :place, :sum, :mtype, :vurl, :thumb, :talt, :dur, :cap, :trans, :pdate, :wel, :ord, :status)
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':slug'   => $slug,
                    ':cat'    => $category,
                    ':place'  => $pagePlacement,
                    ':sum'    => $summary,
                    ':mtype'  => $mediaType,
                    ':vurl'   => $videoUrl,
                    ':thumb'  => $thumbnailPath ?: '/assets/images/placeholders/hero-sanctuary.jpg',
                    ':talt'   => $thumbnailAlt ?: $title,
                    ':dur'    => $durationLabel,
                    ':cap'    => $captionsAvailable,
                    ':trans'  => $transcriptText,
                    ':pdate'  => $publishedDate,
                    ':wel'    => $isWelcomeVideo,
                    ':ord'    => $sortOrder,
                    ':status' => $status,
                ]);
                $newId = (int)$pdo->lastInsertId();
                Repository::logAudit($adminId, 'create_video', 'videos_resources', $newId, "Created resource: {$title}");
                json_success('Video/resource added successfully.');
            }
            break;
        }

        case 'delete_video': {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM videos_resources WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_video', 'videos_resources', $id, "Deleted video/resource ID #{$id}");
            json_success('Resource deleted.');
            break;
        }

        // ====================================================================
        // 2. EVENTS MANAGEMENT (Upcoming & Move to Past Gallery)
        // ====================================================================
        case 'save_event': {
            $id          = (int)($_POST['id'] ?? 0);
            $title       = trim((string)($_POST['title'] ?? ''));
            $description = sanitize_rich_html($_POST['description'] ?? '');
            $eventDate   = trim((string)($_POST['event_date'] ?? ''));
            $eventTime   = trim((string)($_POST['event_time'] ?? ''));
            $timezone    = trim((string)($_POST['timezone'] ?? 'Eastern Time (ET)'));
            $format      = in_array($_POST['format'] ?? '', ['In-Person', 'Virtual', 'Hybrid'], true) ? $_POST['format'] : 'Virtual';
            $location    = trim((string)($_POST['location'] ?? ''));
            $costInfo    = trim((string)($_POST['cost_info'] ?? ''));
            $extRegUrl   = sanitize_safe_url($_POST['external_registration_url'] ?? '');
            $regBtnLabel = trim((string)($_POST['registration_button_label'] ?? 'External Registration Info'));
            $imagePath   = sanitize_safe_url($_POST['image_path'] ?? '/assets/images/placeholders/community-workshop.jpg');
            $imageAlt    = trim((string)($_POST['image_alt'] ?? $title));
            $accessNotes = trim((string)($_POST['accessibility_notes'] ?? ''));
            $eventState  = in_array($_POST['event_state'] ?? '', ['upcoming', 'past'], true) ? $_POST['event_state'] : 'upcoming';
            $status      = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';
            $sortOrder   = (int)($_POST['sort_order'] ?? 0);

            if ($title === '' || $eventDate === '' || $eventTime === '' || $location === '') {
                json_error('Event title, date, time, and location are required.');
            }

            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE events SET
                        title = :title,
                        description = :desc,
                        event_date = :edate,
                        event_time = :etime,
                        timezone = :tz,
                        format = :fmt,
                        location = :loc,
                        cost_info = :cost,
                        external_registration_url = :rurl,
                        registration_button_label = :rbtn,
                        image_path = :img,
                        image_alt = :alt,
                        accessibility_notes = :acc,
                        event_state = :estate,
                        status = :status,
                        sort_order = :ord
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':desc'   => $description,
                    ':edate'  => $eventDate,
                    ':etime'  => $eventTime,
                    ':tz'     => $timezone,
                    ':fmt'    => $format,
                    ':loc'    => $location,
                    ':cost'   => $costInfo,
                    ':rurl'   => $extRegUrl,
                    ':rbtn'   => $regBtnLabel ?: 'External Registration Info',
                    ':img'    => $imagePath ?: '/assets/images/placeholders/community-workshop.jpg',
                    ':alt'    => $imageAlt ?: $title,
                    ':acc'    => $accessNotes,
                    ':estate' => $eventState,
                    ':status' => $status,
                    ':ord'    => $sortOrder,
                    ':id'     => $id,
                ]);
                Repository::logAudit($adminId, 'update_event', 'events', $id, "Updated event: {$title}");
                json_success('Event updated successfully.');
            } else {
                $slug = slugify($title);
                $stmt = $pdo->prepare("
                    INSERT INTO events (title, slug, description, event_date, event_time, timezone, format, location, cost_info, external_registration_url, registration_button_label, image_path, image_alt, accessibility_notes, event_state, status, sort_order)
                    VALUES (:title, :slug, :desc, :edate, :etime, :tz, :fmt, :loc, :cost, :rurl, :rbtn, :img, :alt, :acc, :estate, :status, :ord)
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':slug'   => $slug,
                    ':desc'   => $description,
                    ':edate'  => $eventDate,
                    ':etime'  => $eventTime,
                    ':tz'     => $timezone,
                    ':fmt'    => $format,
                    ':loc'    => $location,
                    ':cost'   => $costInfo,
                    ':rurl'   => $extRegUrl,
                    ':rbtn'   => $regBtnLabel ?: 'External Registration Info',
                    ':img'    => $imagePath ?: '/assets/images/placeholders/community-workshop.jpg',
                    ':alt'    => $imageAlt ?: $title,
                    ':acc'    => $accessNotes,
                    ':estate' => $eventState,
                    ':status' => $status,
                    ':ord'    => $sortOrder,
                ]);
                $newId = (int)$pdo->lastInsertId();
                Repository::logAudit($adminId, 'create_event', 'events', $newId, "Created event: {$title}");
                json_success('Event created successfully.');
            }
            break;
        }

        case 'move_event_to_past': {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $ev = $stmt->fetch();
            if (!$ev) {
                json_error('Event not found.', 404);
            }

            $pdo->prepare("UPDATE events SET event_state = 'past' WHERE id = :id")->execute([':id' => $id]);

            $checkAlbum = $pdo->prepare("SELECT id FROM gallery_albums WHERE event_id = :eid LIMIT 1");
            $checkAlbum->execute([':eid' => $id]);
            if (!$checkAlbum->fetchColumn()) {
                $albumStmt = $pdo->prepare("
                    INSERT INTO gallery_albums (event_id, title, slug, event_date, description, cover_image, cover_alt, permission_confirmed, sort_order, status)
                    VALUES (:eid, :title, :slug, :edate, :desc, :img, :alt, 1, 0, 'published')
                ");
                $albumStmt->execute([
                    ':eid'   => $id,
                    ':title' => $ev['title'],
                    ':slug'  => slugify($ev['title'] . '-gallery'),
                    ':edate' => $ev['event_date'],
                    ':desc'  => strip_tags((string)$ev['description']),
                    ':img'   => $ev['image_path'],
                    ':alt'   => $ev['image_alt'] ?: $ev['title'],
                ]);
            }

            Repository::logAudit($adminId, 'move_event_to_past', 'events', $id, "Moved event '{$ev['title']}' to Past Events & Gallery");
            json_success('Event moved to Past Events and linked to the Past Events Gallery.');
            break;
        }

        case 'delete_event': {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM events WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_event', 'events', $id, "Deleted event ID #{$id}");
            json_success('Event deleted.');
            break;
        }

        // ====================================================================
        // 3. PAST EVENTS GALLERY ALBUMS & MEDIA
        // ====================================================================
        case 'save_album': {
            $id          = (int)($_POST['id'] ?? 0);
            $title       = trim((string)($_POST['title'] ?? ''));
            $eventDate   = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
            $description = trim((string)($_POST['description'] ?? ''));
            $coverImage  = sanitize_safe_url($_POST['cover_image'] ?? '/assets/images/placeholders/community-workshop.jpg');
            $coverAlt    = trim((string)($_POST['cover_alt'] ?? $title));
            $permConfirm = !empty($_POST['permission_confirmed']) ? 1 : 0;
            $sortOrder   = (int)($_POST['sort_order'] ?? 0);
            $status      = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

            if ($title === '' || $description === '') {
                json_error('Album title and description are required.');
            }
            if ($status === 'published' && $permConfirm !== 1) {
                json_error('Participant permission confirmation is required before publishing a gallery album.');
            }

            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE gallery_albums SET
                        title = :title,
                        event_date = :edate,
                        description = :desc,
                        cover_image = :img,
                        cover_alt = :alt,
                        permission_confirmed = :perm,
                        sort_order = :ord,
                        status = :status
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':edate'  => $eventDate,
                    ':desc'   => $description,
                    ':img'    => $coverImage ?: '/assets/images/placeholders/community-workshop.jpg',
                    ':alt'    => $coverAlt ?: $title,
                    ':perm'   => $permConfirm,
                    ':ord'    => $sortOrder,
                    ':status' => $status,
                    ':id'     => $id,
                ]);
                Repository::logAudit($adminId, 'update_album', 'gallery_albums', $id, "Updated gallery album: {$title}");
                json_success('Gallery album updated.');
            } else {
                $slug = slugify($title);
                $stmt = $pdo->prepare("
                    INSERT INTO gallery_albums (title, slug, event_date, description, cover_image, cover_alt, permission_confirmed, sort_order, status)
                    VALUES (:title, :slug, :edate, :desc, :img, :alt, :perm, :ord, :status)
                ");
                $stmt->execute([
                    ':title'  => $title,
                    ':slug'   => $slug,
                    ':edate'  => $eventDate,
                    ':desc'   => $description,
                    ':img'    => $coverImage ?: '/assets/images/placeholders/community-workshop.jpg',
                    ':alt'    => $coverAlt ?: $title,
                    ':perm'   => $permConfirm,
                    ':ord'    => $sortOrder,
                    ':status' => $status,
                ]);
                $newId = (int)$pdo->lastInsertId();
                Repository::logAudit($adminId, 'create_album', 'gallery_albums', $newId, "Created gallery album: {$title}");
                json_success('Gallery album created.');
            }
            break;
        }

        case 'add_gallery_media': {
            $albumId  = (int)($_POST['album_id'] ?? 0);
            $title    = trim((string)($_POST['title'] ?? ''));
            $altText  = trim((string)($_POST['alt_text'] ?? $title));
            $caption  = trim((string)($_POST['caption'] ?? ''));
            $permVer  = !empty($_POST['permission_verified']) ? 1 : 0;
            $filePath = sanitize_safe_url($_POST['file_path'] ?? '');

            if ($albumId <= 0 || $title === '') {
                json_error('Album and item title are required.');
            }
            if ($permVer !== 1) {
                json_error('Please confirm participant permission before adding identifiable event media.');
            }

            if (!empty($_FILES['upload_file']['name'])) {
                $uploaded = handle_safe_upload($_FILES['upload_file'], $adminId, $altText, $caption);
                $filePath = $uploaded['file_path'];
            }

            if ($filePath === '') {
                json_error('Please upload an image file or select an existing media path.');
            }

            $stmt = $pdo->prepare("
                INSERT INTO gallery_media (album_id, title, media_type, file_path, alt_text, caption, permission_verified, status)
                VALUES (:aid, :title, 'image', :fp, :alt, :cap, 1, 'published')
            ");
            $stmt->execute([
                ':aid'   => $albumId,
                ':title' => $title,
                ':fp'    => $filePath,
                ':alt'   => $altText ?: $title,
                ':cap'   => $caption,
            ]);
            Repository::logAudit($adminId, 'add_gallery_media', 'gallery_media', (int)$pdo->lastInsertId(), "Added media '{$title}' to album #{$albumId}");
            json_success('Media item added to gallery album.');
            break;
        }

        case 'delete_gallery_media': {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM gallery_media WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_gallery_media', 'gallery_media', $id, "Deleted gallery media item #{$id}");
            json_success('Gallery media item removed.');
            break;
        }

        case 'delete_album': {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM gallery_albums WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_album', 'gallery_albums', $id, "Deleted gallery album #{$id}");
            json_success('Gallery album deleted.');
            break;
        }

        // ====================================================================
        // 4. MEDIA LIBRARY UPLOAD & REMOVAL
        // ====================================================================
        case 'upload_media': {
            $altText = trim((string)($_POST['alt_text'] ?? ''));
            $caption = trim((string)($_POST['caption'] ?? ''));
            if (empty($_FILES['media_file'])) {
                json_error('Please select a file to upload.');
            }
            $res = handle_safe_upload($_FILES['media_file'], $adminId, $altText, $caption);
            Repository::logAudit($adminId, 'upload_media', 'media_library', $res['id'], "Uploaded media asset {$res['file_path']}");
            json_success('Media uploaded successfully.', ['file_path' => $res['file_path']]);
            break;
        }

        case 'delete_media': {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM media_library WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $media = $stmt->fetch();
            if (!$media) {
                json_error('Media asset not found.', 404);
            }

            $fp = (string)$media['file_path'];
            // Prevent accidental deletion if asset is actively referenced by events, albums, gallery media, or videos
            $inUseStmt = $pdo->prepare("
                SELECT
                    (SELECT COUNT(*) FROM events WHERE image_path = :p1) +
                    (SELECT COUNT(*) FROM gallery_albums WHERE cover_image = :p2) +
                    (SELECT COUNT(*) FROM gallery_media WHERE file_path = :p3) +
                    (SELECT COUNT(*) FROM videos_resources WHERE thumbnail_path = :p4 OR video_url = :p5)
            ");
            $inUseStmt->execute([':p1' => $fp, ':p2' => $fp, ':p3' => $fp, ':p4' => $fp, ':p5' => $fp]);
            if ((int)$inUseStmt->fetchColumn() > 0) {
                json_error('This asset is currently in use on an event, gallery album, or video resource. Replace the reference first before deleting.');
            }

            if (strpos($fp, '/cms/uploads/media/') === 0) {
                $diskFile = UPLOAD_DIR . basename($fp);
                if (is_file($diskFile)) {
                    @unlink($diskFile);
                }
            }

            $pdo->prepare("DELETE FROM media_library WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_media', 'media_library', $id, "Removed media asset {$fp}");
            json_success('Media asset removed.');
            break;
        }

        // ====================================================================
        // 5. APPOINTMENT INQUIRIES MANAGEMENT
        // ====================================================================
        case 'update_inquiry_status': {
            $id     = (int)($_POST['id'] ?? 0);
            $status = in_array($_POST['status'] ?? '', ['new', 'reviewed', 'archived'], true) ? $_POST['status'] : 'reviewed';
            $notes  = trim((string)($_POST['admin_notes'] ?? ''));

            $stmt = $pdo->prepare("
                UPDATE inquiries SET
                    status = :status,
                    admin_notes = :notes,
                    reviewed_by = :rb,
                    reviewed_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':status' => $status,
                ':notes'  => $notes !== '' ? mb_substr($notes, 0, 1000) : null,
                ':rb'     => $adminId ?: null,
                ':id'     => $id,
            ]);
            Repository::logAudit($adminId, 'update_inquiry', 'inquiries', $id, "Marked inquiry #{$id} as {$status}");
            json_success("Inquiry #{$id} marked as {$status}.");
            break;
        }

        case 'delete_inquiry': {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM inquiries WHERE id = :id")->execute([':id' => $id]);
            Repository::logAudit($adminId, 'delete_inquiry', 'inquiries', $id, "Deleted inquiry record #{$id} per retention policy");
            json_success('Inquiry permanently deleted.');
            break;
        }

        // ====================================================================
        // 6. PRACTICE CONTACT & SITE SETTINGS
        // ====================================================================
        case 'save_settings': {
            $postedSettings = $_POST['settings'] ?? [];
            if (!is_array($postedSettings)) {
                json_error('Invalid settings payload.');
            }

            $confirmPolicyPublish = !empty($_POST['confirm_policy_publish']);

            $stmtCheck = $pdo->prepare("SELECT is_policy FROM site_settings WHERE setting_key = :k");
            $stmtUp = $pdo->prepare("UPDATE site_settings SET setting_value = :v, updated_by = :u WHERE setting_key = :k");

            foreach ($postedSettings as $k => $v) {
                $stmtCheck->execute([':k' => $k]);
                $row = $stmtCheck->fetch();
                if (!$row) {
                    continue;
                }
                if ((int)$row['is_policy'] === 1 && !$confirmPolicyPublish) {
                    continue;
                }
                $stmtUp->execute([
                    ':v' => trim((string)$v),
                    ':u' => $adminId ?: null,
                    ':k' => (string)$k,
                ]);
            }

            Repository::logAudit($adminId, 'update_settings', 'site_settings', null, 'Updated global practice settings');
            json_success('Practice settings saved successfully.');
            break;
        }

        default:
            json_error('Unknown CMS action requested.', 400);
    }
} catch (Throwable $e) {
    error_log('CMS API Error: ' . $e->getMessage());
    json_error($e->getMessage(), 422);
}
