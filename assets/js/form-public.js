/**
 * FormYar - Public JavaScript
 * Handles frontend form interactions
 */
(function ($) {
    'use strict';

    // Initialize Jalali Date Picker
    if (typeof jalaliDatepicker !== 'undefined') {
        jalaliDatepicker.startWatch({
            minDate: "attr",
            maxDate: "attr",
            zIndex: 99999,
            time: false,
            separatorChars: { between: " - " },
        });
    }

    // Toast Notification System
    /**
     * Display a toast notification.
     *
     * @param {string} message  The message to display.
     * @param {string} type     The type: 'success', 'error', 'warning', or 'info'.
     * @param {number} duration Optional. Duration in milliseconds. Default 4000.
     */
    function showToast(message, type, duration) {
        type = type || 'success';
        duration = duration || 4000;

        // RTL detection.
        var isRTL = $('html').attr('dir') === 'rtl' || $('body').hasClass('rtl');

        // Icons for each type (SVG).
        var icons = {
            success: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            error: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            warning: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
            info: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
        };

        // Create container if not exists.
        var $container = $('#fmyar-toast-container');
        if (!$container.length) {
            $container = $('<div>', {
                id: 'fmyar-toast-container',
                css: {
                    position: 'fixed',
                    bottom: '24px',
                    [isRTL ? 'left' : 'right']: '24px',
                    zIndex: '99999',
                    display: 'flex',
                    flexDirection: 'column',
                    gap: '10px',
                    pointerEvents: 'none',
                    maxWidth: '380px'
                }
            }).appendTo('body');
        }

        // Build toast.
        var $toast = $('<div>', {
            class: 'fmyar-toast fmyar-toast-' + type,
            role: 'alert',
            'aria-live': 'polite',
            css: {
                display: 'flex',
                alignItems: 'center',
                gap: '10px',
                padding: '14px 16px',
                background: 'var(--fmyar-surface, #fff)',
                color: 'var(--fmyar-text, #18181b)',
                borderRadius: '10px',
                fontSize: '14px',
                fontWeight: '500',
                boxShadow: '0 10px 25px rgba(0,0,0,0.15)',
                border: '1px solid var(--fmyar-border, #e8e8ea)',
                pointerEvents: 'auto',
                opacity: '0',
                transform: isRTL ? 'translateX(-20px)' : 'translateX(20px)',
                transition: 'all 300ms cubic-bezier(0.4, 0, 0.2, 1)',
                position: 'relative',
                overflow: 'hidden',
                minWidth: '280px'
            }
        });

        // Icon.
        var $icon = $('<span>', {
            class: 'fmyar-toast-icon',
            html: icons[type] || icons.info,
            css: {
                width: '20px',
                height: '20px',
                flexShrink: '0',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center'
            }
        });

        // Message.
        var $message = $('<span>', {
            class: 'fmyar-toast-message',
            text: message,
            css: {
                flex: '1',
                lineHeight: '1.5'
            }
        });

        // Close button.
        var $close = $('<button>', {
            type: 'button',
            class: 'fmyar-toast-close',
            html: '&times;',
            'aria-label': (fmyar_public.i18n && fmyar_public.i18n.close) || 'Close',
            css: {
                background: 'none',
                border: 'none',
                color: 'var(--fmyar-text-tertiary, #a1a1aa)',
                fontSize: '20px',
                lineHeight: '1',
                cursor: 'pointer',
                padding: '0 4px',
                flexShrink: '0',
                transition: 'color 150ms'
            }
        });

        // Assemble.
        $toast.append($icon, $message, $close);
        $container.append($toast);

        // Animate in.
        setTimeout(function () {
            $toast.css({
                opacity: '1',
                transform: 'translateX(0)'
            });
        }, 10);

        // Auto-dismiss.
        var dismissTimer = setTimeout(function () {
            dismissToast($toast, isRTL);
        }, duration);

        // Close button click.
        $close.on('click', function () {
            clearTimeout(dismissTimer);
            dismissToast($toast, isRTL);
        });

        // Hover pauses the auto-dismiss.
        $toast.on('mouseenter', function () {
            clearTimeout(dismissTimer);
        }).on('mouseleave', function () {
            dismissTimer = setTimeout(function () {
                dismissToast($toast, isRTL);
            }, 2000);
        });

        /**
         * Dismiss a toast with animation.
         */
        function dismissToast($el, rtl) {
            $el.css({
                opacity: '0',
                transform: rtl ? 'translateX(-20px)' : 'translateX(20px)'
            });
            setTimeout(function () {
                $el.remove();
                // Remove container if empty.
                if ($container.children().length === 0) {
                    $container.remove();
                }
            }, 300);
        }
    }

    // Form Submission
    $(document).on('submit', '.fmyar-form', function (e) {
        e.preventDefault();

        var form = $(this);
        var formId = form.data('form-id');
        var submitBtn = form.find('button[type="submit"]');

        if (!validateFormFields(form)) return;

        var formData = new FormData();
        formData.append('action', 'fmyar_submit_form');
        formData.append('form_id', formId);
        formData.append('nonce', fmyar_public.nonce);

        form.find('[name^="fmyar_form_data["]').each(function () {
            var name = $(this).attr('name');
            var match = name.match(/fmyar_form_data\[(.+?)\](?:\[\])?/);
            if (!match) return;
            var fieldId = match[1];

            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                if ($(this).is(':checked')) formData.append('fmyar_form_data[' + fieldId + '][]', $(this).val());
            } else {
                formData.append('fmyar_form_data[' + fieldId + ']', $(this).val());
            }
        });

        form.find('.fmyar-file-drop-zone').each(function () {
            var fieldId = $(this).closest('[data-field-id]').data('field-id');
            var urls = [];
            $(this).closest('[data-field-id]').find('.fmyar-file-preview-row').each(function () {
                var url = $(this).data('file-url');
                if (url) urls.push(url);
            });
            if (urls.length > 0) formData.append('fmyar_files[' + fieldId + ']', JSON.stringify(urls));
        });

        submitBtn.prop('disabled', true).text(fmyar_public.i18n.submitting);

        $.ajax({
            url: fmyar_public.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    form.html('<div class="fmyar-success-message"><div class="fmyar-success-icon"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 12l5 5l10 -10" /><path d="M2 12l5 5m5 -5l5 -5" /></svg></div><p>' + response.data.msg + '</p></div>');
                } else {
                    showToast(response.data.msg, 'error');
                    submitBtn.prop('disabled', false).text(fmyar_public.i18n.submit);
                }
            },
            error: function () {
                showToast(fmyar_public.i18n.error, 'error');
                submitBtn.prop('disabled', false).text(fmyar_public.i18n.submit);
            }
        });
    });

    // Field Validation
    function validateFormFields(form) {
        var isValid = true, firstError = null;

        form.find('[data-required="1"]').each(function () {
            var container = $(this);
            var input = container.find('input, textarea, select');
            var hasValue = false;

            if (input.is(':file')) {
                hasValue = container.find('.fmyar-file-preview-row').length > 0;
            } else if (input.is(':radio') || input.is(':checkbox')) {
                hasValue = container.find('input:checked').length > 0;
            } else {
                hasValue = input.val() && input.val().trim() !== '';
            }

            if (!hasValue) {
                showFieldError(container, fmyar_public.i18n.required_field);
                isValid = false;
                if (!firstError) firstError = container;
            } else {
                removeFieldError(container);
            }
        });

        if (firstError) {
            $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 300);
        }

        return isValid;
    }

    function showFieldError(container, message) {
        container.addClass('fmyar-field-error');
        if (!container.find('.fmyar-field-error-message').length) {
            container.append('<span class="fmyar-field-error-message">' + message + '</span>');
        }
    }

    function removeFieldError(container) {
        container.removeClass('fmyar-field-error');
        container.find('.fmyar-field-error-message').remove();
    }

    // File Upload - Click
    $(document).on('click', '.fmyar-file-drop-zone', function (e) {
        if ($(e.target).closest('.fmyar-file-remove-btn, .fmyar-file-preview-row').length) return;
        var $input = $(this).find('input[type="file"]');
        if ($input.length) $input[0].click();
    });

    // File Upload - File Dialog Change
    $(document).on('change', '.fmyar-file-drop-zone input[type="file"]', function (e) {
        e.stopPropagation();
        var files = this.files;
        if (files.length > 0) handleFiles($(this).closest('.fmyar-file-drop-zone'), files);
    });

    // File Upload - Drag Over
    $(document).on('dragover', '.fmyar-file-drop-zone', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('fmyar-file-drop-zone-active');
    });

    // File Upload - Drag Leave
    $(document).on('dragleave', '.fmyar-file-drop-zone', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('fmyar-file-drop-zone-active');
    });

    // File Upload - Drop
    $(document).on('drop', '.fmyar-file-drop-zone', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('fmyar-file-drop-zone-active');
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) handleFiles($(this), files);
    });

    // File Upload - Handler
    function handleFiles(dropZone, files) {
        var allowedTypes = dropZone.data('allowed-types') ? dropZone.data('allowed-types').split(',') : [];
        var maxSize = (parseInt(dropZone.data('max-size')) || 5) * 1024 * 1024;
        var maxFiles = parseInt(dropZone.data('max-files')) || 1;
        var formId = dropZone.closest('.fmyar-form').data('form-id');
        var fieldId = dropZone.closest('[data-field-id]').data('field-id');
        var fileList = dropZone.closest('[data-field-id]').find('.fmyar-file-list');
        var currentCount = fileList.find('.fmyar-file-preview-row').length;

        Array.from(files).forEach(function (file, index) {
            if (currentCount + index >= maxFiles) {
                showToast(fmyar_public.i18n.max_files, 'warning');
                return;
            }

            var ext = file.name.split('.').pop().toLowerCase();
            if (allowedTypes.length > 0 && allowedTypes.indexOf(ext) === -1) {
                showToast(fmyar_public.i18n.invalid_type, 'warning');
                return;
            }

            if (file.size > maxSize) {
                showToast(fmyar_public.i18n.file_too_large, 'warning');
                return;
            }

            var previewRow = $('<div>', { class: 'fmyar-file-preview-row fmyar-file-uploading' });
            previewRow.append('<span class="fmyar-file-preview-name">' + file.name + '</span>');
            previewRow.append('<span class="fmyar-file-upload-progress">' + fmyar_public.i18n.uploading + '</span>');
            fileList.append(previewRow);

            (function (row) {
                var formData = new FormData();
                formData.append('action', 'fmyar_upload_form_file');
                formData.append('file', file);
                formData.append('form_id', formId);
                formData.append('field_id', fieldId);
                formData.append('nonce', fmyar_public.nonce);

                $.ajax({
                    url: fmyar_public.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            row.removeClass('fmyar-file-uploading');
                            row.attr('data-file-url', response.data.url);
                            row.find('.fmyar-file-upload-progress').remove();
                            row.append(
                                '<span class="fmyar-file-preview-size">' + (response.data.name || fmyar_public.i18n.file) + '</span>' +
                                '<button type="button" class="fmyar-file-remove-btn">&times;</button>'
                            );
                        } else {
                            row.remove();
                            showToast(response.data.msg || fmyar_public.i18n.upload_error, 'error');
                        }
                    },
                    error: function () {
                        row.remove();
                        showToast(fmyar_public.i18n.upload_error, 'error');
                    }
                });
            })(previewRow);
        });

        dropZone.find('input[type="file"]').val('');
    }

    // File Upload - Remove
    $(document).on('click', '.fmyar-file-remove-btn', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).closest('.fmyar-file-preview-row').fadeOut(200, function () {
            $(this).remove();
        });
    });

    // ============================================
// Progress Bar
// ============================================
function updateProgressBar(form) {
    var $wrapper = form.closest('.fmyar-form-wrapper');
    if (!$wrapper.length) return;

    var $progressBar = $wrapper.find('.fmyar-form-progress-bar');
    var $progressCount = $wrapper.find('.fmyar-form-progress-count');
    var $fieldCards = form.find('.fmyar-field-card');
    var totalFields = $fieldCards.length;
    var filledFields = 0;

    $fieldCards.each(function () {
        var $card = $(this);
        var $input = $card.find('input, textarea, select').not('[type="hidden"]').first();
        var isFilled = false;

        if ($input.is(':file')) {
            isFilled = $card.find('.fmyar-file-preview-row').length > 0;
        } else if ($input.is(':radio') || $input.is(':checkbox')) {
            isFilled = $card.find('input:checked').length > 0;
        } else {
            var val = $input.val();
            isFilled = val && val.trim() !== '';
        }

        if (isFilled) {
            filledFields++;
            $card.addClass('fmyar-field-complete');
        } else {
            $card.removeClass('fmyar-field-complete');
        }
    });

    var percent = totalFields > 0 ? (filledFields / totalFields) * 100 : 0;
    $progressBar.css('width', percent + '%');
    $progressCount.text(filledFields);
}

// Track field changes for progress.
$(document).on('input change', '.fmyar-form input, .fmyar-form textarea, .fmyar-form select', function () {
    var form = $(this).closest('.fmyar-form');
    if (form.length) {
        updateProgressBar(form);
    }
});

// Track file uploads for progress.
$(document).on('DOMNodeInserted', '.fmyar-file-list', function () {
    var form = $(this).closest('.fmyar-form');
    if (form.length) {
        updateProgressBar(form);
    }
});

})(jQuery);