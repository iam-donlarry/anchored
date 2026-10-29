<?php
/**
 * De AnchoredSelf, LLC - Repeatable Database Seeder
 * Seeds only the CMS-managed tables (`videos_resources`, `events`, `gallery_albums`,
 * `gallery_media`, `media_library`, and optional `site_settings`).
 * Public website pages, navigation, services, and FAQs are defined directly in code.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$pdo = Database::getConnection();
$pdo->beginTransaction();

try {
    // 1. Optional Site Settings
    $settings = [
        ['practice_name', 'De AnchoredSelf, LLC', 'general', 'Practice Name'],
        ['tagline', 'Grounded in Healing. Growing in Strength. Delivering Hope.', 'general', 'Practice Tagline'],
        ['telephone', '443-912-4612', 'contact', 'Telephone Number'],
        ['full_address', '403 Eastern Boulevard, Essex, Maryland 21221', 'contact', 'Full Office Address'],
        ['office_hours', 'Monday through Saturday, 9:00 a.m.–6:00 p.m., Eastern Time', 'contact', 'Office Hours'],
        ['availability_notice', 'Telehealth and in-person services are available by appointment. Contact us to confirm availability in your area.', 'contact', 'Availability Notice'],
    ];

    $stmtSetting = $pdo->prepare("
        INSERT INTO site_settings (setting_key, setting_value, setting_group, label)
        VALUES (:k, :v, :g, :l)
        ON DUPLICATE KEY UPDATE
            setting_value = VALUES(setting_value),
            setting_group = VALUES(setting_group),
            label = VALUES(label)
    ");
    foreach ($settings as $s) {
        $stmtSetting->execute([
            ':k' => $s[0],
            ':v' => $s[1],
            ':g' => $s[2],
            ':l' => $s[3],
        ]);
    }

    // 2. Videos & Educational Resources (CMS-Managed)
    $videos = [
        [
            'title' => 'Welcome to De AnchoredSelf, LLC: Compassionate Psychotherapy & Whole-Person Wellness',
            'slug' => 'welcome-to-de-anchoredself',
            'category' => 'Practice Introduction',
            'page_placement' => 'home',
            'summary' => 'An introduction to our welcoming practice in Essex, Maryland and via telehealth—exploring how psychotherapy, optional Christian counseling, and whole-person wellness come together.',
            'media_type' => 'article_guide',
            'video_url' => '',
            'thumbnail_path' => '/assets/images/placeholders/hero-sanctuary.jpg',
            'thumbnail_alt' => 'Welcoming counseling office at De AnchoredSelf, LLC',
            'duration_label' => '3 min overview & transcript',
            'captions_available' => 1,
            'transcript_text' => "Welcome to De AnchoredSelf, LLC, where compassionate psychotherapy and whole-person wellness come together.\n\nWhether you are navigating emotional challenges, strengthening relationships, seeking Christian counseling, living with ongoing pain, or pursuing wellness and weight-management goals, our practice offers a welcoming space to begin.\n\nWe serve children ages 5 and older, adolescents, adults, and older adults through late adulthood. Sometimes you need structured support; sometimes you simply need a place to talk. Reach out to our practice at 443-912-4612 or submit an appointment inquiry to discuss your next steps.",
            'published_date' => '2026-10-01',
            'is_welcome_video' => 1,
            'sort_order' => 1,
        ],
        [
            'title' => 'Understanding Whole-Person Wellness: Stress, Sleep, Nutrition & Emotional Balance',
            'slug' => 'understanding-whole-person-wellness',
            'category' => 'Whole-Person Wellness',
            'page_placement' => 'wellness',
            'summary' => 'Explore how daily habits, restorative sleep, mindful nutrition, herbal wellness education, and juice therapy connect with emotional resilience.',
            'media_type' => 'article_guide',
            'video_url' => '',
            'thumbnail_path' => '/assets/images/placeholders/wellness-botanical.jpg',
            'thumbnail_alt' => 'Botanical wellness ingredients and wholesome nutrition',
            'duration_label' => '5 min guide & transcript',
            'captions_available' => 1,
            'transcript_text' => "At De AnchoredSelf, LLC, we recognize that emotional well-being does not exist in isolation. Stress, sleep quality, nutrition habits, movement, and daily routines all influence how we feel and cope.\n\nOur whole-person wellness education, herbalism education, and juice therapy guidance help you establish realistic, sustainable routines that complement care from your medical providers. Video and educational content is for informational purposes; individual recommendations are discussed during your appointments.",
            'published_date' => '2026-10-10',
            'is_welcome_video' => 0,
            'sort_order' => 2,
        ],
        [
            'title' => 'Strengthening Communication & Emotional Safety in Relationships',
            'slug' => 'strengthening-communication-in-relationships',
            'category' => 'Psychotherapy & Relationships',
            'page_placement' => 'psychotherapy',
            'summary' => 'Practical reflections on listening to understand, navigating conflict constructively, and rebuilding trust in couples and family life.',
            'media_type' => 'article_guide',
            'video_url' => '',
            'thumbnail_path' => '/assets/images/placeholders/couples-connection.jpg',
            'thumbnail_alt' => 'Couple speaking attentively in a calm setting',
            'duration_label' => '4 min guide & transcript',
            'captions_available' => 1,
            'transcript_text' => "Healthy relationships require space where each person feels heard and respected. In couples and family psychotherapy, we explore recurring communication patterns, practice emotional regulation during moments of tension, and work collaboratively toward shared goals.\n\nRemember that educational resources are general guides—individualized support is tailored during your sessions.",
            'published_date' => '2026-10-15',
            'is_welcome_video' => 0,
            'sort_order' => 3,
        ],
        [
            'title' => 'Integrating Faith & Emotional Healing: How Optional Christian Counseling Works',
            'slug' => 'integrating-faith-and-emotional-healing',
            'category' => 'Christian Counseling',
            'page_placement' => 'christian-counseling',
            'summary' => 'Learn how prayer, biblical reflection, and spiritual discussion can be incorporated at your request while honoring your personal preferences.',
            'media_type' => 'article_guide',
            'video_url' => '',
            'thumbnail_path' => '/assets/images/placeholders/christian-reflection.jpg',
            'thumbnail_alt' => 'Open scripture and warm sunlight symbolizing faith and hope',
            'duration_label' => '4 min guide & transcript',
            'captions_available' => 1,
            'transcript_text' => "For clients who desire faith-integrated care, Christian counseling provides a compassionate space to explore grief, forgiveness, purpose, anxiety, and relationship challenges through both therapeutic interventions and spiritual reflection.\n\nChristian counseling at De AnchoredSelf, LLC is always optional and guided by your preferences. We welcome people of all faiths and backgrounds.",
            'published_date' => '2026-10-18',
            'is_welcome_video' => 0,
            'sort_order' => 4,
        ],
        [
            'title' => 'Behavioral Coping Strategies for Living with Persistent Pain',
            'slug' => 'behavioral-coping-strategies-persistent-pain',
            'category' => 'Pain-Management Support',
            'page_placement' => 'wellness',
            'summary' => 'How behavioral pain-management counseling supports mood, sleep, pacing, and relaxation alongside your medical care.',
            'media_type' => 'article_guide',
            'video_url' => '',
            'thumbnail_path' => '/assets/images/placeholders/calm-nature.jpg',
            'thumbnail_alt' => 'Peaceful natural landscape representing relaxation and pacing',
            'duration_label' => '4 min guide & transcript',
            'captions_available' => 1,
            'transcript_text' => "Living with ongoing pain affects more than physical comfort—it can impact sleep, mood, relationships, and daily energy. Behavioral pain-management counseling focuses on relaxation strategies, activity pacing, and addressing the emotional toll of chronic pain in coordination with your medical providers.",
            'published_date' => '2026-10-22',
            'is_welcome_video' => 0,
            'sort_order' => 5,
        ],
    ];

    $stmtVideo = $pdo->prepare("
        INSERT INTO videos_resources (title, slug, category, page_placement, summary, media_type, video_url, thumbnail_path, thumbnail_alt, duration_label, captions_available, transcript_text, published_date, is_welcome_video, sort_order, status)
        VALUES (:title, :slug, :category, :page_placement, :summary, :media_type, :video_url, :thumbnail_path, :thumbnail_alt, :duration_label, :captions_available, :transcript_text, :published_date, :is_welcome_video, :sort_order, 'published')
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            category = VALUES(category),
            page_placement = VALUES(page_placement),
            summary = VALUES(summary),
            thumbnail_path = VALUES(thumbnail_path),
            thumbnail_alt = VALUES(thumbnail_alt),
            duration_label = VALUES(duration_label),
            transcript_text = VALUES(transcript_text),
            is_welcome_video = VALUES(is_welcome_video),
            sort_order = VALUES(sort_order)
    ");
    foreach ($videos as $v) {
        $stmtVideo->execute([
            ':title'              => $v['title'],
            ':slug'               => $v['slug'],
            ':category'           => $v['category'],
            ':page_placement'     => $v['page_placement'],
            ':summary'            => $v['summary'],
            ':media_type'         => $v['media_type'],
            ':video_url'          => $v['video_url'],
            ':thumbnail_path'     => $v['thumbnail_path'],
            ':thumbnail_alt'      => $v['thumbnail_alt'],
            ':duration_label'     => $v['duration_label'],
            ':captions_available' => $v['captions_available'],
            ':transcript_text'    => $v['transcript_text'],
            ':published_date'     => $v['published_date'],
            ':is_welcome_video'   => $v['is_welcome_video'],
            ':sort_order'         => $v['sort_order'],
        ]);
    }

    // 3. Events (Upcoming & Past - CMS-Managed)
    $events = [
        [
            'title' => 'Whole-Person Wellness & Stress Resilience Community Workshop',
            'slug' => 'whole-person-wellness-stress-resilience-workshop',
            'description' => '<p>Join De AnchoredSelf, LLC for an educational presentation exploring practical connections among emotional well-being, restorative sleep, nutrition habits, and everyday stress management.</p>',
            'event_date' => '2026-11-21',
            'event_time' => '11:00 a.m. – 12:30 p.m.',
            'timezone' => 'Eastern Time (ET)',
            'format' => 'Hybrid',
            'location' => '403 Eastern Boulevard, Essex, MD 21221 & Virtual Telehealth Broadcast',
            'cost_info' => 'Contact practice for event details',
            'external_registration_url' => '',
            'registration_button_label' => 'Inquire About Attendance',
            'image_path' => '/assets/images/placeholders/wellness-botanical.jpg',
            'image_alt' => 'Whole-person wellness educational workshop banner',
            'accessibility_notes' => 'Wheelchair accessible ground-level entry in Essex; live captions enabled for virtual attendees.',
            'event_state' => 'upcoming',
            'sort_order' => 1,
        ],
        [
            'title' => 'Supporting Children & Teens Through Transitions: Parent Educational Session',
            'slug' => 'supporting-children-teens-parent-educational-session',
            'description' => '<p>An educational session for parents and guardians of children ages 5+ and adolescents discussing emotional regulation, school stress, peer relationships, and supportive family communication.</p>',
            'event_date' => '2026-12-05',
            'event_time' => '10:00 a.m. – 11:30 a.m.',
            'timezone' => 'Eastern Time (ET)',
            'format' => 'Virtual',
            'location' => 'Online Educational Presentation (Eastern Time)',
            'cost_info' => 'Complimentary community education session',
            'external_registration_url' => '',
            'registration_button_label' => 'Request Event Details',
            'image_path' => '/assets/images/placeholders/family-support.jpg',
            'image_alt' => 'Parent and family educational session illustration',
            'accessibility_notes' => 'Closed captioning and downloadable educational handout provided.',
            'event_state' => 'upcoming',
            'sort_order' => 2,
        ],
        [
            'title' => 'Mindful Nourishment: Herbalism & Juice Therapy Educational Seminar',
            'slug' => 'mindful-nourishment-herbalism-juice-therapy-seminar',
            'description' => '<p>An interactive educational gathering introducing herbal wellness principles and balanced juice therapy routines that support everyday vitality.</p>',
            'event_date' => '2026-09-19',
            'event_time' => '1:00 p.m. – 2:30 p.m.',
            'timezone' => 'Eastern Time (ET)',
            'format' => 'In-Person',
            'location' => '403 Eastern Boulevard, Essex, Maryland 21221',
            'cost_info' => 'Completed Event',
            'external_registration_url' => '',
            'registration_button_label' => 'Completed Event',
            'image_path' => '/assets/images/placeholders/herbal-tea.jpg',
            'image_alt' => 'Herbalism and juice therapy educational display',
            'accessibility_notes' => 'In-person educational presentation.',
            'event_state' => 'past',
            'sort_order' => 3,
        ],
    ];

    $stmtEvent = $pdo->prepare("
        INSERT INTO events (title, slug, description, event_date, event_time, timezone, format, location, cost_info, external_registration_url, registration_button_label, image_path, image_alt, accessibility_notes, event_state, status, sort_order)
        VALUES (:title, :slug, :description, :event_date, :event_time, :timezone, :format, :location, :cost_info, :external_registration_url, :registration_button_label, :image_path, :image_alt, :accessibility_notes, :event_state, 'published', :sort_order)
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            description = VALUES(description),
            event_date = VALUES(event_date),
            event_time = VALUES(event_time),
            timezone = VALUES(timezone),
            format = VALUES(format),
            location = VALUES(location),
            cost_info = VALUES(cost_info),
            image_path = VALUES(image_path),
            image_alt = VALUES(image_alt),
            accessibility_notes = VALUES(accessibility_notes),
            event_state = VALUES(event_state),
            sort_order = VALUES(sort_order)
    ");
    foreach ($events as $ev) {
        $stmtEvent->execute([
            ':title'                     => $ev['title'],
            ':slug'                      => $ev['slug'],
            ':description'               => $ev['description'],
            ':event_date'                => $ev['event_date'],
            ':event_time'                => $ev['event_time'],
            ':timezone'                  => $ev['timezone'],
            ':format'                    => $ev['format'],
            ':location'                  => $ev['location'],
            ':cost_info'                 => $ev['cost_info'],
            ':external_registration_url' => $ev['external_registration_url'],
            ':registration_button_label' => $ev['registration_button_label'],
            ':image_path'                => $ev['image_path'],
            ':image_alt'                 => $ev['image_alt'],
            ':accessibility_notes'       => $ev['accessibility_notes'],
            ':event_state'               => $ev['event_state'],
            ':sort_order'                => $ev['sort_order'],
        ]);
    }

    // 4. Past Events Gallery Albums & Media (CMS-Managed)
    $albums = [
        [
            'title' => 'Mindful Nourishment & Whole-Person Wellness Open House',
            'slug' => 'mindful-nourishment-wellness-open-house',
            'event_date' => '2026-09-19',
            'description' => 'Highlights from our educational tables featuring herbal wellness education, balanced juice therapy preparation, and whole-person wellness resources at our Essex, Maryland office (no identifiable client photographs shown).',
            'cover_image' => '/assets/images/placeholders/herbal-tea.jpg',
            'cover_alt' => 'Herbal wellness tea and botanical educational display',
            'sort_order' => 1,
            'media' => [
                [
                    'title' => 'Herbal Wellness Education Display',
                    'file_path' => '/assets/images/placeholders/herbal-tea.jpg',
                    'alt_text' => 'Botanical herbs and educational materials prepared for workshop attendees',
                    'caption' => 'Educational display exploring safe, informed herbal wellness habits.',
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Balanced Juice Therapy Ingredients',
                    'file_path' => '/assets/images/placeholders/juice-nutrition.jpg',
                    'alt_text' => 'Fresh nutrient-dense produce and juices prepared for educational demonstration',
                    'caption' => 'Exploring ways to incorporate nutrient-rich juices into a balanced daily routine.',
                    'sort_order' => 2,
                ],
            ],
        ],
        [
            'title' => 'Practice Environment & Community Education Spaces',
            'slug' => 'practice-environment-community-education-spaces',
            'event_date' => '2026-08-15',
            'description' => 'A look inside our welcoming, calm therapeutic and educational spaces designed for comfort, privacy, and reflection.',
            'cover_image' => '/assets/images/placeholders/counseling-space.jpg',
            'cover_alt' => 'Warm and welcoming counseling room interior',
            'sort_order' => 2,
            'media' => [
                [
                    'title' => 'Welcoming Consultation & Reflection Space',
                    'file_path' => '/assets/images/placeholders/counseling-space.jpg',
                    'alt_text' => 'Comfortable seating and warm lighting in the practice consultation space',
                    'caption' => 'A calm, welcoming space to talk, heal, and plan practical steps forward.',
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Community Workshop & Group Learning Setting',
                    'file_path' => '/assets/images/placeholders/community-workshop.jpg',
                    'alt_text' => 'Educational presentation seating arranged for community workshops',
                    'caption' => 'Prepared seating for educational presentations and group learning.',
                    'sort_order' => 2,
                ],
            ],
        ],
    ];

    $stmtAlbum = $pdo->prepare("
        INSERT INTO gallery_albums (title, slug, event_date, description, cover_image, cover_alt, permission_confirmed, sort_order, status)
        VALUES (:title, :slug, :event_date, :description, :cover_image, :cover_alt, 1, :sort_order, 'published')
        ON DUPLICATE KEY UPDATE
            title = VALUES(title),
            event_date = VALUES(event_date),
            description = VALUES(description),
            cover_image = VALUES(cover_image),
            cover_alt = VALUES(cover_alt),
            sort_order = VALUES(sort_order)
    ");

    $pdo->exec("DELETE FROM gallery_media");
    $stmtGMedia = $pdo->prepare("
        INSERT INTO gallery_media (album_id, title, media_type, file_path, alt_text, caption, permission_verified, sort_order, status)
        VALUES (:album_id, :title, 'image', :file_path, :alt_text, :caption, 1, :sort_order, 'published')
    ");

    foreach ($albums as $al) {
        $stmtAlbum->execute([
            ':title'       => $al['title'],
            ':slug'        => $al['slug'],
            ':event_date'  => $al['event_date'],
            ':description' => $al['description'],
            ':cover_image' => $al['cover_image'],
            ':cover_alt'   => $al['cover_alt'],
            ':sort_order'  => $al['sort_order'],
        ]);
        $stmtAlId = $pdo->prepare("SELECT id FROM gallery_albums WHERE slug = :slug");
        $stmtAlId->execute([':slug' => $al['slug']]);
        $albumId = (int)$stmtAlId->fetchColumn();

        foreach ($al['media'] as $m) {
            $stmtGMedia->execute([
                ':album_id'   => $albumId,
                ':title'      => $m['title'],
                ':file_path'  => $m['file_path'],
                ':alt_text'   => $m['alt_text'],
                ':caption'    => $m['caption'],
                ':sort_order' => $m['sort_order'],
            ]);
        }
    }

    // 5. Register curated placeholder images in Media Library
    $mediaSeed = [
        ['hero-sanctuary.jpg', 'Warm Welcoming Practice Sanctuary', '/assets/images/placeholders/hero-sanctuary.jpg', 'image/jpeg', 'Welcoming therapeutic environment with natural light'],
        ['counseling-space.jpg', 'Supportive Counseling Seating Area', '/assets/images/placeholders/counseling-space.jpg', 'image/jpeg', 'Calm counseling seating space'],
        ['couples-connection.jpg', 'Couples Psychotherapy & Communication', '/assets/images/placeholders/couples-connection.jpg', 'image/jpeg', 'Supportive conversation between partners'],
        ['family-support.jpg', 'Family & Child Support Outdoors', '/assets/images/placeholders/family-support.jpg', 'image/jpeg', 'Warm family connection representing child, adolescent, and family support'],
        ['christian-reflection.jpg', 'Optional Christian Counseling Reflection', '/assets/images/placeholders/christian-reflection.jpg', 'image/jpeg', 'Peaceful reflection and scripture study'],
        ['wellness-botanical.jpg', 'Whole-Person Wellness & Nutrition', '/assets/images/placeholders/wellness-botanical.jpg', 'image/jpeg', 'Wholesome botanicals and nutrition representing whole-person wellness'],
        ['herbal-tea.jpg', 'Herbalism Wellness Education', '/assets/images/placeholders/herbal-tea.jpg', 'image/jpeg', 'Herbal infusion and botanical wellness education'],
        ['juice-nutrition.jpg', 'Balanced Juice Therapy', '/assets/images/placeholders/juice-nutrition.jpg', 'image/jpeg', 'Fresh cold-pressed juices and wholesome produce'],
        ['calm-nature.jpg', 'Resilience & Trauma-Informed Calm', '/assets/images/placeholders/calm-nature.jpg', 'image/jpeg', 'Sunlight through calm forest trees'],
        ['community-workshop.jpg', 'Educational Workshop & Supervision', '/assets/images/placeholders/community-workshop.jpg', 'image/jpeg', 'Educational presentation and professional supervision environment'],
    ];

    $stmtMedia = $pdo->prepare("
        INSERT INTO media_library (file_name, original_name, file_path, mime_type, file_size, alt_text, caption)
        VALUES (:fn, :on, :fp, :mt, :fs, :alt, :cap)
        ON DUPLICATE KEY UPDATE
            original_name = VALUES(original_name),
            alt_text = VALUES(alt_text),
            caption = VALUES(caption)
    ");
    foreach ($mediaSeed as $ms) {
        $fullDiskPath = ROOT_PATH . str_replace('/', DIRECTORY_SEPARATOR, $ms[2]);
        $size = is_file($fullDiskPath) ? (int)filesize($fullDiskPath) : 0;
        $stmtMedia->execute([
            ':fn'  => $ms[0],
            ':on'  => $ms[1],
            ':fp'  => $ms[2],
            ':mt'  => $ms[3],
            ':fs'  => $size,
            ':alt' => $ms[4],
            ':cap' => $ms[1] . ' (Development Placeholder — Replace via CMS Media Library)',
        ]);
    }

    $pdo->commit();
    if (PHP_SAPI === 'cli') {
        echo "Database `anchored` seeded with CMS-managed content successfully.\n";
    }
} catch (Throwable $e) {
    $pdo->rollBack();
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "Seeding error: " . $e->getMessage() . "\n");
        exit(1);
    }
    throw $e;
}
