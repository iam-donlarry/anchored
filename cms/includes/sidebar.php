<?php
/**
 * De AnchoredSelf CMS - Sidebar Navigation Partial
 * Strictly scoped to CMS-managed areas: Dashboard, Events, Videos & Resources,
 * Gallery & Media, Appointment Inquiries, and Settings.
 */

declare(strict_types=1);
?>
<aside class="cms-sidebar" id="cmsSidebar" aria-label="CMS Navigation">
    <div class="cms-sidebar-brand">
        <a href="<?= e(CMS_BASE_URL) ?>/dashboard.php" style="text-decoration: none; display: block;">
            <img src="<?= e(SITE_BASE_URL) ?>/assets/images/logo-light.svg" alt="De AnchoredSelf CMS" width="215" height="43">
        </a>
        <div style="font-size: 0.72rem; color: var(--cms-gold-500); margin-top: 0.45rem; font-weight: 600; letter-spacing: 0.05em;">
            PRACTICE CONTENT MANAGEMENT
        </div>
    </div>

    <nav class="cms-nav">
        <div class="cms-nav-section">Overview</div>
        <a href="<?= e(CMS_BASE_URL) ?>/dashboard.php" class="cms-nav-link <?= $activeModule === 'dashboard' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </span>
        </a>

        <div class="cms-nav-section">Dynamic Practice Content</div>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/events/" class="cms-nav-link <?= $activeModule === 'events' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="calendar"></i>
                <span>Events</span>
            </span>
        </a>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/videos/" class="cms-nav-link <?= $activeModule === 'videos' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="play-circle"></i>
                <span>Videos &amp; Resources</span>
            </span>
        </a>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/gallery/" class="cms-nav-link <?= $activeModule === 'gallery' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="images"></i>
                <span>Event Gallery</span>
            </span>
        </a>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/media/" class="cms-nav-link <?= $activeModule === 'media' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="folder-kanban"></i>
                <span>Media Library</span>
            </span>
        </a>

        <div class="cms-nav-section">Inquiries &amp; Administration</div>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/inquiries/" class="cms-nav-link <?= $activeModule === 'inquiries' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="inbox"></i>
                <span>Appointment Inquiries</span>
            </span>
            <?php if (!empty($newInquiriesCount)): ?>
                <span class="cms-badge-pill"><?= (int)$newInquiriesCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= e(CMS_BASE_URL) ?>/modules/settings/" class="cms-nav-link <?= $activeModule === 'settings' ? 'active' : '' ?>">
            <span class="cms-nav-link-left">
                <i data-lucide="settings"></i>
                <span>Settings</span>
            </span>
        </a>
    </nav>
</aside>
