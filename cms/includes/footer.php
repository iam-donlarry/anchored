<?php
/**
 * De AnchoredSelf CMS - Master Footer Partial
 */

declare(strict_types=1);
?>
        </main>
        <footer style="padding: 1rem 2rem; border-top: 1px solid var(--cms-border); background: var(--cms-surface); font-size: 0.82rem; color: var(--cms-muted); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                &copy; <?= date('Y') ?> <strong>De AnchoredSelf, LLC</strong> &bull; Practice Content Management System
            </div>
            <div>
                Database: <code>anchored</code> &bull; Eastern Time (ET)
            </div>
        </footer>
    </div>
</div>
<script src="<?= e(CMS_BASE_URL) ?>/assets/js/lucide.min.js"></script>
<script src="<?= e(CMS_BASE_URL) ?>/assets/js/cms.js?v=<?= @filemtime(CMS_PATH . '/assets/js/cms.js') ?: '1' ?>"></script>
</body>
</html>

