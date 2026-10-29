-- ============================================================================
-- De AnchoredSelf, LLC - Website & CMS Database Schema
-- Database: `anchored`
-- Scope: CMS-managed Events, Videos/Resources, Past Events Gallery & Media,
--        Routine Appointment Inquiries, Administrator Access, and Audit Logs.
--        (Public website pages, navigation, services, and FAQs are code-defined.)
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Drop obsolete page/service/faq tables from earlier iteration
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `service_categories`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `faqs`;

-- 1. CMS Administrators
CREATE TABLE IF NOT EXISTS `cms_admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(60) NOT NULL UNIQUE,
    `email` VARCHAR(160) NOT NULL UNIQUE,
    `full_name` VARCHAR(120) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin', 'editor') NOT NULL DEFAULT 'super_admin',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_admin_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. CMS Login Attempts (Brute-Force Throttling)
CREATE TABLE IF NOT EXISTS `cms_login_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `identifier` VARCHAR(160) NOT NULL,
    `attempted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_login_ip_time` (`ip_address`, `attempted_at`),
    INDEX `idx_login_id_time` (`identifier`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Global Website Settings
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `label` VARCHAR(150) NOT NULL,
    `updated_by` INT UNSIGNED NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_settings_admin` FOREIGN KEY (`updated_by`) REFERENCES `cms_admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Videos and Educational Resources (CMS-Managed)
CREATE TABLE IF NOT EXISTS `videos_resources` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `category` VARCHAR(100) NOT NULL DEFAULT 'General Wellness',
    `page_placement` VARCHAR(80) NOT NULL DEFAULT 'resources',
    `summary` TEXT NOT NULL,
    `media_type` ENUM('video_file', 'video_embed', 'article_guide') NOT NULL DEFAULT 'video_embed',
    `video_url` VARCHAR(500) NULL,
    `thumbnail_path` VARCHAR(255) NULL,
    `thumbnail_alt` VARCHAR(220) NULL,
    `duration_label` VARCHAR(40) NULL,
    `captions_available` TINYINT(1) NOT NULL DEFAULT 1,
    `transcript_text` MEDIUMTEXT NULL,
    `published_date` DATE NULL,
    `is_welcome_video` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_videos_placement_status` (`page_placement`, `status`, `sort_order`),
    INDEX `idx_videos_welcome` (`is_welcome_video`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Upcoming and Past Events (CMS-Managed)
CREATE TABLE IF NOT EXISTS `events` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `description` MEDIUMTEXT NOT NULL,
    `event_date` DATE NOT NULL,
    `event_time` VARCHAR(100) NOT NULL,
    `timezone` VARCHAR(60) NOT NULL DEFAULT 'Eastern Time (ET)',
    `format` ENUM('In-Person', 'Virtual', 'Hybrid') NOT NULL DEFAULT 'Virtual',
    `location` VARCHAR(255) NOT NULL,
    `cost_info` VARCHAR(150) NULL,
    `external_registration_url` VARCHAR(500) NULL,
    `registration_button_label` VARCHAR(80) NOT NULL DEFAULT 'External Registration Info',
    `image_path` VARCHAR(255) NULL,
    `image_alt` VARCHAR(220) NULL,
    `accessibility_notes` TEXT NULL,
    `event_state` ENUM('upcoming', 'past') NOT NULL DEFAULT 'upcoming',
    `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_events_state_status` (`event_state`, `status`, `event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Past Events Gallery Albums (CMS-Managed)
CREATE TABLE IF NOT EXISTS `gallery_albums` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `event_date` DATE NULL,
    `description` TEXT NOT NULL,
    `cover_image` VARCHAR(255) NULL,
    `cover_alt` VARCHAR(220) NULL,
    `permission_confirmed` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_albums_status_order` (`status`, `sort_order`),
    CONSTRAINT `fk_album_event` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Gallery Media Items (CMS-Managed)
CREATE TABLE IF NOT EXISTS `gallery_media` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `album_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(180) NOT NULL,
    `media_type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
    `file_path` VARCHAR(255) NOT NULL,
    `video_url` VARCHAR(500) NULL,
    `alt_text` VARCHAR(220) NOT NULL,
    `caption` TEXT NULL,
    `permission_verified` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_gmedia_album_status` (`album_id`, `status`, `sort_order`),
    CONSTRAINT `fk_gmedia_album` FOREIGN KEY (`album_id`) REFERENCES `gallery_albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Shared Media Library (CMS-Managed)
CREATE TABLE IF NOT EXISTS `media_library` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(180) NOT NULL,
    `original_name` VARCHAR(220) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL UNIQUE,
    `mime_type` VARCHAR(80) NOT NULL,
    `file_size` INT UNSIGNED NOT NULL DEFAULT 0,
    `alt_text` VARCHAR(220) NOT NULL DEFAULT '',
    `caption` VARCHAR(255) NULL,
    `uploaded_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_media_admin` FOREIGN KEY (`uploaded_by`) REFERENCES `cms_admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. General Appointment Inquiries (Submitted from Contact Page, Managed in CMS)
CREATE TABLE IF NOT EXISTS `inquiries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(140) NOT NULL,
    `email` VARCHAR(160) NULL,
    `phone` VARCHAR(50) NULL,
    `preferred_contact` ENUM('Telephone', 'Email', 'Either Telephone or Email') NOT NULL DEFAULT 'Telephone',
    `age_group` VARCHAR(80) NOT NULL,
    `is_child_inquiry` TINYINT(1) NOT NULL DEFAULT 0,
    `guardian_name` VARCHAR(140) NULL,
    `guardian_contact` VARCHAR(160) NULL,
    `guardian_relationship` VARCHAR(80) NULL,
    `service_state` VARCHAR(80) NOT NULL,
    `service_of_interest` VARCHAR(150) NOT NULL,
    `preferred_format` ENUM('Telehealth', 'In-Person (Essex, MD)', 'Either / Undecided') NOT NULL DEFAULT 'Either / Undecided',
    `message` TEXT NULL,
    `privacy_acknowledged` TINYINT(1) NOT NULL DEFAULT 1,
    `status` ENUM('new', 'reviewed', 'archived') NOT NULL DEFAULT 'new',
    `admin_notes` TEXT NULL,
    `reviewed_by` INT UNSIGNED NULL,
    `reviewed_at` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_inquiries_status_created` (`status`, `created_at`),
    CONSTRAINT `fk_inquiries_admin` FOREIGN KEY (`reviewed_by`) REFERENCES `cms_admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Administrative Audit Logs (No Sensitive Inquiry Content Logged)
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(60) NOT NULL,
    `entity_id` INT UNSIGNED NULL,
    `summary` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_created` (`created_at`),
    CONSTRAINT `fk_audit_admin` FOREIGN KEY (`admin_id`) REFERENCES `cms_admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
