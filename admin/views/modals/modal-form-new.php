<?php

/**
 * Modal: New Form
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();
?>

<form id="fmyar_new_form" class="fmyar-modal-form">
    <div class="fmyar-form-group">
        <label class="fmyar-form-label">
            <?php esc_html_e('Form Title', 'formyar'); ?> <span class="fmyar-required">*</span>
        </label>
        <input type="text" name="title" class="fmyar-input-full" required autofocus>
    </div>

    <div class="fmyar-form-group">
        <label class="fmyar-form-label">
            <?php esc_html_e('Description', 'formyar'); ?>
            <span class="fmyar-form-hint">(<?php esc_html_e('optional', 'formyar'); ?>)</span>
        </label>
        <textarea name="description" rows="3" class="fmyar-textarea" placeholder="<?php esc_attr_e('Brief description of this form...', 'formyar'); ?>"></textarea>
    </div>

    <input type="hidden" name="type" value="custom">

    <div class="fmyar-modal-form-actions">
        <button type="submit" class="fmyar-btn fmyar-btn-primary fmyar-flex-1">
            <?php esc_html_e('Create Form', 'formyar'); ?>
            <span class="fmyar-loading-spinner"></span>
        </button>
        <button type="button" class="fmyar-modal-cancel fmyar-btn fmyar-btn-secondary fmyar-flex-1">
            <?php esc_html_e('Cancel', 'formyar'); ?>
        </button>
    </div>
</form>