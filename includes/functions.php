<?php
/**
 * De AnchoredSelf, LLC - Core Security, Sanitization, and Formatting Helpers
 */

declare(strict_types=1);

/**
 * Context-safe HTML output escaping
 */
function e(?string $value): string
{
    if ($value === null) {
        return '';
    }
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Generate or retrieve CSRF token for the active session
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token from request or header
 */
function verify_csrf_token(?string $token = null): bool
{
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Resolve asset or media URL safely
 */
function resolve_asset_url(?string $path, string $fallback = '/assets/images/placeholders/hero-sanctuary.jpg'): string
{
    $target = trim((string)$path);
    if ($target === '') {
        $target = $fallback;
    }
    if (preg_match('#^https?://#i', $target)) {
        return $target;
    }
    if (strpos($target, SITE_BASE_URL) === 0) {
        return $target;
    }
    return SITE_BASE_URL . '/' . ltrim($target, '/');
}

/**
 * Convert title string to URL-friendly slug
 */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text) ?? '';
    $text = trim($text, '-');
    return $text !== '' ? $text : 'item-' . substr(bin2hex(random_bytes(4)), 0, 8);
}

/**
 * Sanitize rich-text HTML to prevent stored XSS while allowing safe editorial formatting
 */
function sanitize_rich_html(?string $html): string
{
    if ($html === null || trim($html) === '') {
        return '';
    }
    $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><blockquote><a><span><hr>';
    $clean = strip_tags($html, $allowedTags);

    // Strip any inline event handlers (on*) or javascript:/data: protocols
    $clean = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/i', '', $clean) ?? $clean;
    $clean = preg_replace('/\s+on[a-z]+\s*=\s*[^\s>]+/i', '', $clean) ?? $clean;
    $clean = preg_replace('/href\s*=\s*(["\'])\s*(javascript|data|vbscript):.*?\1/i', 'href="#"', $clean) ?? $clean;

    return trim($clean);
}

/**
 * Validate and normalize safe external or video URLs
 */
function sanitize_safe_url(?string $url): string
{
    $url = trim((string)$url);
    if ($url === '') {
        return '';
    }
    // Allow relative local paths starting with /assets/ or /cms/uploads/
    if (preg_match('#^/(assets|cms/uploads)/[a-zA-Z0-9_\-\./]+$#', $url)) {
        return $url;
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }
    $parts = parse_url($url);
    $scheme = strtolower($parts['scheme'] ?? '');
    if (!in_array($scheme, ['http', 'https'], true)) {
        return '';
    }
    return $url;
}

/**
 * Format telephone number for tel: links
 */
function format_tel_href(string $phone): string
{
    $digits = preg_replace('/[^0-9+]/', '', $phone) ?? '';
    return 'tel:' . $digits;
}

/**
 * Return standardized list of approved service inquiry choices per Builder Guide
 */
function get_approved_inquiry_services(): array
{
    return [
        'Individual Psychotherapy',
        'Couples Psychotherapy',
        'Family Psychotherapy',
        'Group Psychotherapy',
        'Christian Counseling',
        'Child & Adolescent Services',
        'A Place to Talk',
        'Trauma-Informed Support',
        'Substance-Use Counseling & Recovery Support',
        'Whole-Person Wellness',
        'Herbalism',
        'Juice Therapy',
        'Weight-Management Support',
        'Weight-Loss Surgery Assessment Consultation',
        'Pain-Management Counseling',
        'Clinical Supervision',
    ];
}

/**
 * Return standardized age group options per Builder Guide (Children ages 5+, Adolescents, Adults, Older Adults)
 */
function get_approved_age_groups(): array
{
    return [
        'Child (Ages 5–12)',
        'Adolescent (Ages 13–17)',
        'Adult (Ages 18–64)',
        'Older Adult (Ages 65+ through Late Adulthood)',
        'Couples / Family',
        'Professional Supervisee',
    ];
}

/**
 * US States list for state where services will be received
 */
function get_us_states(): array
{
    return [
        'Maryland', 'District of Columbia', 'Virginia', 'Pennsylvania', 'Delaware',
        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut',
        'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas',
        'Kentucky', 'Louisiana', 'Maine', 'Massachusetts', 'Michigan', 'Minnesota',
        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
        'Oklahoma', 'Oregon', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee',
        'Texas', 'Utah', 'Vermont', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming',
        'Other / Outside US'
    ];
}

