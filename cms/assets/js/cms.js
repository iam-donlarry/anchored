/**
 * De AnchoredSelf CMS - Interactive Modal, AJAX Form, Toast & Confirmation Engine
 */

window.CMS = {
    toast: function (message, type = 'success') {
        let container = document.getElementById('cmsToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'cmsToastContainer';
            container.className = 'cms-toast-container';
            document.body.appendChild(container);
        }
        const el = document.createElement('div');
        el.className = 'cms-toast ' + type;
        el.setAttribute('role', 'status');
        el.innerHTML = '<span></span><button type="button" style="background:none;border:none;color:#fff;cursor:pointer;font-size:1.1rem;line-height:1;">&times;</button>';
        el.querySelector('span').textContent = message;
        el.querySelector('button').addEventListener('click', function () {
            el.remove();
        });
        container.appendChild(el);
        setTimeout(function () {
            if (el.parentNode) el.remove();
        }, 4500);
    },

    openModal: function (modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.add('is-open');
        }
    },

    closeModal: function (modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.remove('is-open');
        }
    },

    submitAjaxForm: async function (formEl, options = {}) {
        const submitBtn = formEl.querySelector('button[type="submit"]');
        const originalHtml = submitBtn ? submitBtn.innerHTML : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Saving...</span>';
        }

        try {
            const formData = new FormData(formEl);
            if (!formData.has('csrf_token') && window.CMS_CSRF_TOKEN) {
                formData.append('csrf_token', window.CMS_CSRF_TOKEN);
            }

            const response = await fetch(window.CMS_API_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': window.CMS_CSRF_TOKEN || ''
                },
                body: formData
            });

            let result;
            try {
                result = await response.json();
            } catch (parseErr) {
                throw new Error('Unexpected server response. Please check your input and try again.');
            }

            if (!response.ok || !result.success) {
                CMS.toast(result.message || 'Validation failed. Please review the form.', 'error');
                return false;
            }

            CMS.toast(result.message || 'Saved successfully.', 'success');

            if (options.modalId) {
                CMS.closeModal(options.modalId);
            }

            if (options.reload !== false) {
                setTimeout(function () {
                    window.location.reload();
                }, 450);
            }

            return result;
        } catch (err) {
            CMS.toast(err.message || 'Network error while saving.', 'error');
            return false;
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            }
        }
    },

    actionRequest: async function (payload, confirmText = null) {
        if (confirmText && !window.confirm(confirmText)) {
            return;
        }

        const formData = new FormData();
        Object.keys(payload).forEach(function (k) {
            formData.append(k, payload[k]);
        });
        formData.append('csrf_token', window.CMS_CSRF_TOKEN || '');

        try {
            const response = await fetch(window.CMS_API_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': window.CMS_CSRF_TOKEN || ''
                },
                body: formData
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                CMS.toast(result.message || 'Operation could not be completed.', 'error');
                return;
            }
            CMS.toast(result.message || 'Updated successfully.', 'success');
            setTimeout(function () {
                window.location.reload();
            }, 400);
        } catch (err) {
            CMS.toast('Request failed. Please try again.', 'error');
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // Mobile sidebar toggle
    const sidebarBtn = document.getElementById('cmsSidebarToggle');
    const sidebar = document.getElementById('cmsSidebar');
    if (sidebarBtn && sidebar) {
        sidebarBtn.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
        });
    }

    // Close modal on backdrop click or data-close-modal
    document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = btn.getAttribute('data-close-modal');
            CMS.closeModal(target);
        });
    });

    // Bind AJAX forms with class .js-cms-ajax-form
    document.querySelectorAll('.js-cms-ajax-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const modalId = form.getAttribute('data-modal-id') || null;
            CMS.submitAjaxForm(form, { modalId: modalId });
        });
    });
});

