/**
 * De AnchoredSelf, LLC - Public Website Interactivity, Motion & Accessibility
 * Includes:
 *  1. Lucide Icon Initialization
 *  2. Accessible Mobile Navigation & Dropdowns (`includes/navbar.php`)
 *  3. Header Scroll Morphing, Top Reading Progress Bar & Back-to-Top with SVG Ring
 *  4. Scroll-Triggered Reveal Animations (`IntersectionObserver`)
 *  5. Interactive Service Spotlight Explorer Tabs
 *  6. Accessible FAQ Accordions
 *  7. Conditional Parent/Guardian Inquiry Fields & Double-Submit Protection
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Lucide Icons
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // 2. Accessible Mobile Navigation & Dropdowns
    const mobileToggle = document.getElementById('mobileNavToggle');
    const primaryNav = document.getElementById('primaryNav');

    if (mobileToggle && primaryNav) {
        mobileToggle.addEventListener('click', function () {
            const isOpen = primaryNav.classList.toggle('is-open');
            mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    const dropdownItems = document.querySelectorAll('.nav-item.has-dropdown');
    dropdownItems.forEach(function (item) {
        const trigger = item.querySelector('.dropdown-toggle-btn');
        if (!trigger) return;

        trigger.addEventListener('click', function (e) {
            if (window.innerWidth <= 980) {
                e.preventDefault();
                const opened = item.classList.toggle('is-open');
                trigger.setAttribute('aria-expanded', opened ? 'true' : 'false');
            }
        });

        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                item.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
                trigger.focus();
            }
        });
    });

    // 3. Header Scroll Morphing, Top Progress Bar & Universal Back-to-Top Button
    const siteHeader = document.getElementById('siteHeader');
    const scrollProgressBar = document.getElementById('scrollProgressBar');
    const backToTopBtn = document.getElementById('backToTopBtn');
    const progressCircle = document.getElementById('backToTopProgressCircle');
    const circumference = 2 * Math.PI * 23; // r=23 => ~144.51

    function handleScrollMetrics() {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const docHeight = Math.max(
            document.documentElement.scrollHeight - document.documentElement.clientHeight,
            1
        );
        const scrollRatio = Math.min(Math.max(scrollTop / docHeight, 0), 1);

        if (siteHeader) {
            siteHeader.classList.toggle('is-scrolled', scrollTop > 40);
        }

        if (scrollProgressBar) {
            scrollProgressBar.style.width = (scrollRatio * 100).toFixed(2) + '%';
        }

        if (backToTopBtn) {
            backToTopBtn.classList.toggle('is-visible', scrollTop > 280);
        }

        if (progressCircle) {
            const offset = circumference - scrollRatio * circumference;
            progressCircle.style.strokeDashoffset = offset.toFixed(2);
        }
    }

    window.addEventListener('scroll', handleScrollMetrics, { passive: true });
    handleScrollMetrics();

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function () {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({
                top: 0,
                behavior: prefersReducedMotion ? 'auto' : 'smooth'
            });
        });
    }

    // 4. Scroll-Triggered Reveal Animations (IntersectionObserver)
    const revealElements = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && revealElements.length > 0) {
        const revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        const delay = entry.target.getAttribute('data-reveal-delay');
                        if (delay) {
                            entry.target.style.transitionDelay = delay + 'ms';
                        }
                        entry.target.classList.add('is-revealed');
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px'
            }
        );

        revealElements.forEach(function (el) {
            revealObserver.observe(el);
        });
    } else {
        revealElements.forEach(function (el) {
            el.classList.add('is-revealed');
        });
    }

    // 5. Interactive Service Spotlight Explorer
    const spotlightTabs = document.querySelectorAll('.js-spotlight-tab');
    const spotlightPanes = document.querySelectorAll('.js-spotlight-pane');

    if (spotlightTabs.length > 0 && spotlightPanes.length > 0) {
        function activateSpotlight(targetId) {
            spotlightTabs.forEach(function (t) {
                const active = t.getAttribute('data-target') === targetId;
                t.classList.toggle('is-active', active);
                t.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            spotlightPanes.forEach(function (p) {
                const active = p.id === targetId;
                p.classList.toggle('is-active', active);
            });
        }

        spotlightTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const targetId = tab.getAttribute('data-target');
                if (targetId) activateSpotlight(targetId);
            });
            tab.addEventListener('mouseenter', function () {
                if (window.innerWidth > 1080) {
                    const targetId = tab.getAttribute('data-target');
                    if (targetId) activateSpotlight(targetId);
                }
            });
        });
    }

    // 6. Accessible FAQ Accordions
    const faqTriggers = document.querySelectorAll('.faq-trigger');
    faqTriggers.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const item = btn.closest('.faq-item');
            const panelId = btn.getAttribute('aria-controls');
            const panel = panelId ? document.getElementById(panelId) : null;
            if (!item || !panel) return;

            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
            item.classList.toggle('is-open', !isExpanded);
            if (isExpanded) {
                panel.setAttribute('hidden', '');
            } else {
                panel.removeAttribute('hidden');
            }
        });
    });

    // 7. Child / Minor Inquiry Conditional Parent/Guardian Section
    const ageGroupSelect = document.getElementById('inquiryAgeGroup');
    const guardianSection = document.getElementById('guardianFieldsSection');
    const guardianNameInput = document.getElementById('guardianName');
    const guardianContactInput = document.getElementById('guardianContact');

    function syncGuardianVisibility() {
        if (!ageGroupSelect || !guardianSection) return;
        const val = ageGroupSelect.value || '';
        const isChildOrTeen = val.indexOf('Child') !== -1 || val.indexOf('Adolescent') !== -1;
        guardianSection.style.display = isChildOrTeen ? 'block' : 'none';
        if (guardianNameInput) guardianNameInput.required = isChildOrTeen;
        if (guardianContactInput) guardianContactInput.required = isChildOrTeen;
    }

    if (ageGroupSelect) {
        ageGroupSelect.addEventListener('change', syncGuardianVisibility);
        syncGuardianVisibility();
    }

    // 8. Prevent Double Submission on Inquiry Form
    const inquiryForm = document.getElementById('appointmentInquiryForm');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function () {
            const submitBtn = inquiryForm.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Submitting Inquiry...</span>';
            }
        });
    }
});
