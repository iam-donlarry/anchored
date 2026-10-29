<?php
/**
 * De AnchoredSelf, LLC - Dynamic XML Sitemap
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/xml; charset=UTF-8');

$routes = [
    '/',
    '/about/',
    '/services/',
    '/psychotherapy/',
    '/christian-counseling/',
    '/wellness/',
    '/weight-loss-surgery-assessments/',
    '/clinical-supervision/',
    '/membership-faq/',
    '/resources/',
    '/events/',
    '/contact/',
    '/privacy-policy/',
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($routes as $r): ?>
    <url>
        <loc><?= htmlspecialchars(SITE_BASE_URL . $r, ENT_XML1, 'UTF-8') ?></loc>
        <changefreq>weekly</changefreq>
        <priority><?= $r === '/' ? '1.0' : '0.8' ?></priority>
    </url>
<?php endforeach; ?>
</urlset>

