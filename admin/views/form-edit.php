<?php

/**
 * Form Edit Page
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$form_id) {
    wp_die(esc_html__('Invalid form ID.', 'formyar'));
}

$form = new FormYar_Form($form_id);

if (!$form->exists()) {
    wp_die(esc_html__('Form not found.', 'formyar'));
}

$entries_count      = $form->get_entry_count();
$settings           = $form->get_settings_array();
$limitation         = $settings['limitation'] ?? ['type' => 'none', 'who_can_submit' => 'all', 'max_entries' => 1, 'time_limit' => 0, 'message' => __('You have already submitted this form.', 'formyar')];
$notifications      = $settings['notifications'] ?? [];
$notify_enabled     = $notifications['enabled'] ?? '0';
$admin_email        = $notifications['admin_email'] ?? get_option('admin_email');
/* translators: %s: form title */
$subject            = $notifications['subject'] ?? sprintf(__('New Form Submission: %s', 'formyar'), $form->get_title());
$user_reply_enabled = $notifications['user_reply_enabled'] ?? '0';
$user_email_field   = $notifications['user_email_field'] ?? '';
$user_reply_subject = $notifications['user_reply_subject'] ?? __('Thank you for your submission', 'formyar');
$user_reply_message = $notifications['user_reply_message'] ?? __('We have received your submission. Thank you!', 'formyar');
$is_active          = $form->is_active();
$form_fields        = $form->get_fields();
?>

<div class="fmyar-app">
    <header class="fmyar-app-header">
        <div class="fmyar-app-header-inner">
            <div class="fmyar-header-left">
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-back-btn" title="<?php esc_attr_e('Back to Forms', 'formyar'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <div class="fmyar-header-info">
                    <div class="fmyar-header-title-row">
                        <h1 class="fmyar-header-title"><?php echo esc_html($form->get_title()); ?></h1>
                        <span class="fmyar-status-pill <?php echo $is_active ? 'fmyar-status-pill-active' : 'fmyar-status-pill-inactive'; ?>">
                            <span class="fmyar-status-dot <?php echo $is_active ? 'fmyar-status-active' : 'fmyar-status-inactive'; ?>"></span>
                            <?php echo $is_active ? esc_html__('Active', 'formyar') : esc_html__('Inactive', 'formyar'); ?>
                        </span>
                    </div>
                    <p class="fmyar-header-meta">
                        <?php
                        /* translators: %s: creation date */
                        printf(esc_html__('Created %s', 'formyar'), esc_html($form->get_created_at('M j, Y')));
                        ?> &middot;
                        <?php
                        /* translators: %d: number of entries */
                        printf(esc_html(_n('%d entry', '%d entries', $entries_count, 'formyar')), esc_html($entries_count));
                        ?>
                    </p>
                </div>
            </div>
            <div class="fmyar-header-right">
                <div class="fmyar-shortcode-box">
                    <code class="fmyar-shortcode-text">[fmyar_form id="<?php echo esc_attr($form->get_id()); ?>"]</code>
                    <button type="button" class="fmyar-copy-btn" data-copy="[fmyar_form id=&quot;<?php echo esc_attr($form->get_id()); ?>&quot;]" title="<?php esc_attr_e('Copy shortcode', 'formyar'); ?>">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                        </svg>
                    </button>
                </div>
                <button type="button" id="fmyar-edit-form-btn" class="fmyar-btn fmyar-btn-primary">
                    <?php esc_html_e('Save Changes', 'formyar'); ?>
                </button>
            </div>
        </div>
    </header>

    <div class="fmyar-tabs-container">
        <div class="fmyar-tabs-scroll" role="tablist">
            <button type="button"
                class="fmyar-tab fmyar-tab-active"
                data-tab="builder"
                data-tab-group="form-edit"
                role="tab"
                aria-selected="true"
                aria-controls="tab-form-edit-builder"
                id="tab-btn-builder">
                <?php esc_html_e('Builder', 'formyar'); ?>
            </button>

            <button type="button"
                class="fmyar-tab"
                data-tab="entries"
                data-tab-group="form-edit"
                role="tab"
                aria-selected="false"
                aria-controls="tab-form-edit-entries"
                id="tab-btn-entries">
                <?php esc_html_e('Entries', 'formyar'); ?>
            </button>

            <button type="button"
                class="fmyar-tab"
                data-tab="settings"
                data-tab-group="form-edit"
                role="tab"
                aria-selected="false"
                aria-controls="tab-form-edit-settings"
                id="tab-btn-settings">
                <?php esc_html_e('Settings', 'formyar'); ?>
            </button>

            <?php
            /**
             * Action: Add Pro tabs to form edit page.
             *
             * Allows Pro to add tabs like Analytics, Conditional Logic, etc.
             *
             * @since 1.1.0
             * @param FormYar_Form $form Current form object.
             */
            do_action('fmyar_form_edit_tabs', $form);
            ?>
        </div>
    </div>

    <div class="fmyar-edit-content" id="fmyar-main-content">

        <div id="tab-form-edit-builder"
            class="fmyar-tab-panel"
            data-tab-group="form-edit"
            role="tabpanel"
            aria-labelledby="tab-btn-builder"
            tabindex="0">
            <form method="post" id="fmyar-edit-form">
                <?php wp_nonce_field('fmyar_edit_form'); ?>
                <input type="hidden" name="save_form" value="1">

                <div class="fmyar-builder-layout">
                    <div class="fmyar-builder-canvas">
                        <div class="fmyar-builder-card">
                            <div class="fmyar-builder-toolbar">
                                <h3 class="fmyar-builder-title"><?php esc_html_e('Form Fields', 'formyar'); ?></h3>
                                <button type="button" id="fmyar-add-field" class="fmyar-btn fmyar-btn-secondary fmyar-btn-sm">
                                    <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    <?php esc_html_e('Add Field', 'formyar'); ?>
                                </button>
                            </div>
                            <div class="fmyar-builder-body">
                                <div id="fmyar-fields-list" class="fmyar-sortable-fields">
                                    <?php if (!empty($form_fields)):
                                        $builder = new FormYar_Form_Builder('form', $form->get_id(), FORMYAR_TABLE_FORMS, 'id');
                                        foreach ($form_fields as $field):
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            echo $builder->render_field_item($field);
                                        endforeach;
                                    endif; ?>
                                </div>
                                <div id="fmyar-empty-state" class="fmyar-empty-state-hero <?php echo empty($form_fields) ? '' : 'fmyar-hidden'; ?>" style="padding: 48px 16px;">
                                    <div class="fmyar-empty-header">
                                        <div class="fmyar-empty-logo-small">
                                            <span class="dashicons dashicons-plus-alt2" style="font-size: 32px; width: 32px; height: 32px;"></span>
                                        </div>
                                        <h3 class="fmyar-empty-title-inline"><?php esc_html_e('Start building', 'formyar'); ?></h3>
                                    </div>
                                    <p class="fmyar-empty-desc-large"><?php esc_html_e('Add your first field to begin creating this form.', 'formyar'); ?></p>
                                    <button type="button" id="fmyar-add-first-field" class="fmyar-btn fmyar-btn-primary fmyar-btn-sm" style="margin-top: 20px;">
                                        <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                        </svg>
                                        <?php esc_html_e('Add Your First Field', 'formyar'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="fmyar-builder-sidebar">
                        <div class="fmyar-builder-sidebar-card">
                            <h4 class="fmyar-sidebar-title"><?php esc_html_e('Quick Settings', 'formyar'); ?></h4>
                            <div class="fmyar-form-group">
                                <label class="fmyar-form-label"><?php esc_html_e('Status', 'formyar'); ?></label>
                                <div class="fmyar-toggle-switch">
                                    <input type="checkbox" id="status-toggle" <?php checked($is_active); ?> onchange="document.getElementById('status-input').value = this.checked ? 'enable' : 'disabled'">
                                    <label for="status-toggle" class="fmyar-toggle-track"><span class="fmyar-toggle-thumb"></span></label>
                                    <span class="fmyar-toggle-label-text"><?php echo $is_active ? esc_html__('Active', 'formyar') : esc_html__('Inactive', 'formyar'); ?></span>
                                </div>
                                <input type="hidden" name="status" id="status-input" value="<?php echo esc_attr($form->get_status()); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="tab-form-edit-entries" class="fmyar-tab-panel fmyar-hidden" data-tab-group="form-edit">
            <div class="fmyar-entries-panel">
                <div class="fmyar-entries-header">
                    <div>
                        <h3 class="fmyar-panel-title"><?php esc_html_e('Form Entries', 'formyar'); ?></h3>
                        <?php /* translators: %d: total entries count */ ?>
                        <p class="fmyar-panel-subtitle"><?php printf(esc_html__('%d total entries', 'formyar'), esc_html($entries_count)); ?></p>
                    </div>
                    <div class="fmyar-entries-actions">
                        <?php if ($entries_count > 0): ?>
                            <button type="button" id="fmyar-export-form-entries" class="fmyar-btn fmyar-btn-secondary fmyar-btn-sm">
                                <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <?php esc_html_e('Export CSV', 'formyar'); ?>
                            </button>
                        <?php endif; ?>
                        <button type="button" id="fmyar-refresh-form-entries" class="fmyar-btn fmyar-btn-secondary fmyar-btn-sm">
                            <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <?php esc_html_e('Refresh', 'formyar'); ?>
                        </button>
                    </div>
                </div>
                <div class="fmyar-table-wrapper">
                    <table class="fmyar-table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php esc_html_e('User', 'formyar'); ?></th>
                                <th><?php esc_html_e('Date', 'formyar'); ?></th>
                                <th class="fmyar-table-actions-col"><?php esc_html_e('Actions', 'formyar'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="fmyar-form-entries-list">
                            <tr>
                                <td colspan="4" class="fmyar-table-loading">
                                    <div class="fmyar-spinner"></div><?php esc_html_e('Loading entries...', 'formyar'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="fmyar-form-entries-pagination" class="fmyar-pagination-bar"></div>

                <div id="fmyar-form-entries-empty" class="fmyar-empty-state-hero <?php echo $entries_count > 0 ? 'fmyar-hidden' : ''; ?>" style="padding: 48px 16px;">
                    <div class="fmyar-empty-header">
                        <div class="fmyar-empty-logo-small">
                            <span class="dashicons dashicons-list-view" style="font-size: 32px; width: 32px; height: 32px;"></span>
                        </div>
                        <h3 class="fmyar-empty-title-inline"><?php esc_html_e('No entries yet', 'formyar'); ?></h3>
                    </div>
                    <p class="fmyar-empty-desc-large"><?php esc_html_e('Entries will appear here when users submit the form.', 'formyar'); ?></p>
                </div>
            </div>
        </div>

        <div id="tab-form-edit-settings" class="fmyar-tab-panel fmyar-hidden" data-tab-group="form-edit">
            <div class="fmyar-settings-panel">
                <div class="fmyar-settings-grid">

                    <div class="fmyar-settings-card">
                        <div class="fmyar-settings-card-header">
                            <div class="fmyar-settings-icon fmyar-settings-icon-primary">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="fmyar-settings-card-title"><?php esc_html_e('General', 'formyar'); ?></h4>
                                <p class="fmyar-settings-card-desc"><?php esc_html_e('Basic form information', 'formyar'); ?></p>
                            </div>
                        </div>
                        <div class="fmyar-settings-card-body">
                            <div class="fmyar-form-group">
                                <label for="settings-title" class="fmyar-form-label"><?php esc_html_e('Form Title', 'formyar'); ?></label>
                                <input type="text" id="settings-title" name="title" value="<?php echo esc_attr($form->get_title()); ?>" class="fmyar-input-full" form="fmyar-edit-form">
                            </div>
                            <div class="fmyar-form-group">
                                <label for="settings-desc" class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                                <textarea id="settings-desc" name="description" rows="3" class="fmyar-textarea" form="fmyar-edit-form"><?php echo esc_textarea($form->get_description()); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="fmyar-settings-card">
                        <div class="fmyar-settings-card-header">
                            <div class="fmyar-settings-icon fmyar-settings-icon-warning">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="fmyar-settings-card-title"><?php esc_html_e('Submission Limit', 'formyar'); ?></h4>
                                <p class="fmyar-settings-card-desc"><?php esc_html_e('Control who can submit and how often', 'formyar'); ?></p>
                            </div>
                        </div>
                        <div class="fmyar-settings-card-body">
                            <div class="fmyar-form-group">
                                <label class="fmyar-form-label"><?php esc_html_e('Who Can Submit', 'formyar'); ?></label>
                                <select name="settings[limitation][who_can_submit]" class="fmyar-select-full" form="fmyar-edit-form">
                                    <option value="all" <?php selected($limitation['who_can_submit'] ?? 'all', 'all'); ?>><?php esc_html_e('Everyone', 'formyar'); ?></option>
                                    <option value="logged_in" <?php selected($limitation['who_can_submit'] ?? '', 'logged_in'); ?>><?php esc_html_e('Logged-in Users Only', 'formyar'); ?></option>
                                </select>
                            </div>
                            <div class="fmyar-form-group">
                                <label class="fmyar-form-label"><?php esc_html_e('Limit Type', 'formyar'); ?></label>
                                <select name="settings[limitation][type]" class="fmyar-select-full" form="fmyar-edit-form">
                                    <option value="none" <?php selected($limitation['type'], 'none'); ?>><?php esc_html_e('No Limit', 'formyar'); ?></option>
                                    <option value="user" <?php selected($limitation['type'], 'user'); ?>><?php esc_html_e('Per User', 'formyar'); ?></option>
                                    <option value="ip" <?php selected($limitation['type'], 'ip'); ?>><?php esc_html_e('Per IP Address', 'formyar'); ?></option>
                                    <option value="cookie" <?php selected($limitation['type'], 'cookie'); ?>><?php esc_html_e('Per Browser', 'formyar'); ?></option>
                                    <option value="both" <?php selected($limitation['type'], 'both'); ?>><?php esc_html_e('User + IP', 'formyar'); ?></option>
                                </select>
                            </div>
                            <div class="fmyar-form-row">
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Max Entries', 'formyar'); ?></label>
                                    <input type="number" name="settings[limitation][max_entries]" value="<?php echo esc_attr($limitation['max_entries'] ?? 1); ?>" min="1" class="fmyar-input-full" form="fmyar-edit-form">
                                </div>
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Time Limit (seconds)', 'formyar'); ?></label>
                                    <input type="number" name="settings[limitation][time_limit]" value="<?php echo esc_attr($limitation['time_limit'] ?? 0); ?>" min="0" class="fmyar-input-full" form="fmyar-edit-form">
                                </div>
                            </div>
                            <div class="fmyar-form-group">
                                <label class="fmyar-form-label"><?php esc_html_e('Limit Message', 'formyar'); ?></label>
                                <input type="text" name="settings[limitation][message]" value="<?php echo esc_attr($limitation['message'] ?? ''); ?>" class="fmyar-input-full" form="fmyar-edit-form" placeholder="<?php esc_attr_e('You have already submitted this form.', 'formyar'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="fmyar-settings-card">
                        <div class="fmyar-settings-card-header">
                            <div class="fmyar-settings-icon fmyar-settings-icon-success">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="fmyar-settings-card-title"><?php esc_html_e('Notifications', 'formyar'); ?></h4>
                                <p class="fmyar-settings-card-desc"><?php esc_html_e('Email alerts on new submissions', 'formyar'); ?></p>
                            </div>
                        </div>
                        <div class="fmyar-settings-card-body">
                            <div class="fmyar-toggle-row">
                                <div>
                                    <label class="fmyar-form-label"><?php esc_html_e('Admin Notifications', 'formyar'); ?></label>
                                    <p class="fmyar-form-hint"><?php esc_html_e('Receive email when someone submits this form', 'formyar'); ?></p>
                                </div>
                                <label class="fmyar-toggle-switch">
                                    <input type="checkbox" name="settings[notifications][enabled]" value="1" <?php checked($notify_enabled, '1'); ?> class="fmyar-toggle-visibility" data-toggle-target=".fmyar-notify-fields" form="fmyar-edit-form">
                                    <span class="fmyar-toggle-track"><span class="fmyar-toggle-thumb"></span></span>
                                </label>
                            </div>
                            <div class="fmyar-notify-fields <?php echo $notify_enabled == '1' ? '' : 'fmyar-hidden'; ?>">
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Admin Email', 'formyar'); ?></label>
                                    <input type="email" name="settings[notifications][admin_email]" value="<?php echo esc_attr($admin_email); ?>" class="fmyar-input-full" form="fmyar-edit-form" placeholder="<?php echo esc_attr(get_option('admin_email')); ?>">
                                    <p class="fmyar-form-hint"><?php esc_html_e('Leave empty to use site admin email', 'formyar'); ?></p>
                                </div>
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Email Subject', 'formyar'); ?></label>
                                    <input type="text" name="settings[notifications][subject]" value="<?php echo esc_attr($subject); ?>" class="fmyar-input-full" form="fmyar-edit-form">
                                </div>
                            </div>
                            <hr class="fmyar-divider">
                            <div class="fmyar-toggle-row">
                                <div>
                                    <label class="fmyar-form-label"><?php esc_html_e('Auto-Reply', 'formyar'); ?></label>
                                    <p class="fmyar-form-hint"><?php esc_html_e('Send confirmation email to user', 'formyar'); ?></p>
                                </div>
                                <label class="fmyar-toggle-switch">
                                    <input type="checkbox" name="settings[notifications][user_reply_enabled]" value="1" <?php checked($user_reply_enabled, '1'); ?> class="fmyar-toggle-visibility" data-toggle-target=".fmyar-reply-fields" form="fmyar-edit-form">
                                    <span class="fmyar-toggle-track"><span class="fmyar-toggle-thumb"></span></span>
                                </label>
                            </div>
                            <div class="fmyar-reply-fields <?php echo $user_reply_enabled == '1' ? '' : 'fmyar-hidden'; ?>">
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('User Email Field', 'formyar'); ?></label>
                                    <select name="settings[notifications][user_email_field]" class="fmyar-select-full" form="fmyar-edit-form">
                                        <option value=""><?php esc_html_e('Auto-detect email field', 'formyar'); ?></option>
                                        <?php foreach ($form_fields as $f): ?>
                                            <?php if ($f->get_type() === 'email'): ?>
                                                <option value="<?php echo esc_attr($f->get_id()); ?>" <?php selected($user_email_field, $f->get_id()); ?>><?php echo esc_html($f->get_config('label') ?: $f->get_id()); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Reply Subject', 'formyar'); ?></label>
                                    <input type="text" name="settings[notifications][user_reply_subject]" value="<?php echo esc_attr($user_reply_subject); ?>" class="fmyar-input-full" form="fmyar-edit-form">
                                </div>
                                <div class="fmyar-form-group">
                                    <label class="fmyar-form-label"><?php esc_html_e('Reply Message', 'formyar'); ?></label>
                                    <textarea name="settings[notifications][user_reply_message]" rows="3" class="fmyar-textarea" form="fmyar-edit-form"><?php echo esc_textarea($user_reply_message); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        /**
         * Action: Render Pro tab panels in form edit page.
         *
         * Allows Pro to render panels for its tabs.
         *
         * @since 1.1.0
         * @param FormYar_Form $form Current form object.
         */
        do_action('fmyar_form_edit_panels', $form);
        ?>

    </div>
</div>

<?php fmyar_render_help_widget(); ?>
<?php fmyar_modal_container(); ?>