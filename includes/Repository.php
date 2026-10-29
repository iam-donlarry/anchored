<?php
/**
 * De AnchoredSelf, LLC - Dynamic CMS Content Repository (PDO)
 *
 * Strictly scoped to CMS-managed entities:
 *  - Videos and Educational Resources (`videos_resources`)
 *  - Upcoming and Past Events (`events`)
 *  - Past Events Gallery Albums and Media (`gallery_albums`, `gallery_media`)
 *  - Global Contact/Practice Settings (`site_settings`)
 *  - Administrative Audit Logs (`audit_logs`)
 *
 * All public website pages, navigation, service descriptions, and FAQs are
 * code-defined in the frontend and do not depend on database page/service queries.
 * Every dynamic query below degrades gracefully if the database is empty or unavailable.
 */

declare(strict_types=1);

class Repository
{
    private static ?array $settingsCache = null;

    public static function getSettings(): array
    {
        if (self::$settingsCache !== null) {
            return self::$settingsCache;
        }
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $rows = $stmt->fetchAll();
            $map = [];
            foreach ($rows as $r) {
                $map[$r['setting_key']] = (string)($r['setting_value'] ?? '');
            }
            self::$settingsCache = $map;
            return $map;
        } catch (Throwable $e) {
            error_log('Repository::getSettings fallback: ' . $e->getMessage());
            self::$settingsCache = [];
            return [];
        }
    }

    public static function setting(string $key, string $default = ''): string
    {
        $settings = self::getSettings();
        return isset($settings[$key]) && $settings[$key] !== '' ? $settings[$key] : $default;
    }

    public static function getWelcomeVideo(): ?array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT * FROM videos_resources WHERE status = 'published' AND is_welcome_video = 1 ORDER BY sort_order ASC, id ASC LIMIT 1");
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            error_log('Repository::getWelcomeVideo fallback: ' . $e->getMessage());
            return null;
        }
    }

    public static function getVideosByPlacement(string $placement, int $limit = 6): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM videos_resources
                WHERE status = 'published' AND (page_placement = :placement OR page_placement = 'all')
                ORDER BY sort_order ASC, id DESC
                LIMIT :lim
            ");
            $stmt->bindValue(':placement', $placement);
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log('Repository::getVideosByPlacement fallback: ' . $e->getMessage());
            return [];
        }
    }

    public static function getAllPublishedVideos(): array
    {
        try {
            $pdo = Database::getConnection();
            return $pdo->query("SELECT * FROM videos_resources WHERE status = 'published' ORDER BY sort_order ASC, published_date DESC, id DESC")->fetchAll();
        } catch (Throwable $e) {
            error_log('Repository::getAllPublishedVideos fallback: ' . $e->getMessage());
            return [];
        }
    }

    public static function getUpcomingEvents(int $limit = 10): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM events
                WHERE status = 'published' AND event_state = 'upcoming'
                ORDER BY event_date ASC, sort_order ASC
                LIMIT :lim
            ");
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log('Repository::getUpcomingEvents fallback: ' . $e->getMessage());
            return [];
        }
    }

    public static function getPastEvents(int $limit = 10): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM events
                WHERE status = 'published' AND event_state = 'past'
                ORDER BY event_date DESC, sort_order ASC
                LIMIT :lim
            ");
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log('Repository::getPastEvents fallback: ' . $e->getMessage());
            return [];
        }
    }

    public static function getPublishedAlbumsWithMedia(): array
    {
        try {
            $pdo = Database::getConnection();
            $albums = $pdo->query("
                SELECT * FROM gallery_albums
                WHERE status = 'published' AND permission_confirmed = 1
                ORDER BY sort_order ASC, event_date DESC, id DESC
            ")->fetchAll();

            if (empty($albums)) {
                return [];
            }

            $mediaStmt = $pdo->prepare("
                SELECT * FROM gallery_media
                WHERE album_id = :aid AND status = 'published' AND permission_verified = 1
                ORDER BY sort_order ASC, id ASC
            ");

            foreach ($albums as &$album) {
                $mediaStmt->execute([':aid' => $album['id']]);
                $album['media_items'] = $mediaStmt->fetchAll();
            }
            unset($album);

            return $albums;
        } catch (Throwable $e) {
            error_log('Repository::getPublishedAlbumsWithMedia fallback: ' . $e->getMessage());
            return [];
        }
    }

    public static function logAudit(?int $adminId, string $action, string $entityType, ?int $entityId, string $summary): void
    {
        try {
            $pdo = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $stmt = $pdo->prepare("
                INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, summary, ip_address)
                VALUES (:admin_id, :action, :entity_type, :entity_id, :summary, :ip)
            ");
            $stmt->execute([
                ':admin_id'    => $adminId,
                ':action'      => $action,
                ':entity_type' => $entityType,
                ':entity_id'   => $entityId,
                ':summary'     => mb_substr($summary, 0, 250),
                ':ip'          => mb_substr((string)$ip, 0, 45),
            ]);
        } catch (Throwable $e) {
            error_log('Audit log write error: ' . $e->getMessage());
        }
    }
}
