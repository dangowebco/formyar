<?php

/**
 * Forms List Page
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$status_filter = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '';
$search        = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
$current_page  = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page      = 20;

$forms = FormYar_Form::get_all([
    'per_page' => $per_page,
    'page'     => $current_page,
    'status'   => $status_filter,
    'search'   => $search,
]);

$total_forms    = FormYar_Form::count_all('', $search);
$active_count   = FormYar_Form::count_all('enable');
$inactive_count = FormYar_Form::count_all('disabled');
$total_pages    = ceil($total_forms / $per_page);

$total_entries = 0;
foreach ($forms as $f) {
    $form_obj = new FormYar_Form($f->id);
    $total_entries += $form_obj->get_entry_count();
}
?>

<div class="fmyar-app">

    <header class="fmyar-app-header">
        <div class="fmyar-app-header-inner">
            <?php
            fmyar_render_brand(
                __('FormYar', 'formyar'),
                __('Modern Form Builder for WordPress', 'formyar')
            );
            ?>

            <div class="fmyar-header-right">
                <!-- Import -->
                <button type="button"
                    class="fmyar-btn fmyar-btn-secondary fmyar-btn fmyar-dynamic-modal"
                    data-modal-target="import"
                    title="<?php esc_attr_e('Import forms from JSON', 'formyar'); ?>">
                    <span class="dashicons dashicons-upload"></span>
                    <?php esc_html_e('Import', 'formyar'); ?>
                </button>

                <!-- From Template -->
                <button type="button"
                    class="fmyar-btn fmyar-btn-secondary fmyar-dynamic-modal"
                    data-modal-target="template">
                    <span class="dashicons dashicons-portfolio"></span>
                    <?php esc_html_e('From Template', 'formyar'); ?>
                </button>

                <!-- Create New Form -->
                <button type="button" class="fmyar-btn fmyar-btn-primary fmyar-dynamic-modal"
                    data-modal-target="form_new" data-modal-item-id="0">
                    <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    <?php esc_html_e('Create New Form', 'formyar'); ?>
                </button>
            </div>
        </div>
    </header>

    <div class="fmyar-stats-bar">
        <div class="fmyar-stat-card">
            <div class="fmyar-stat-icon fmyar-stat-icon-primary">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="fmyar-stat-content">
                <span class="fmyar-stat-value"><?php echo esc_html($total_forms); ?></span>
                <span class="fmyar-stat-label"><?php esc_html_e('Total Forms', 'formyar'); ?></span>
            </div>
        </div>
        <div class="fmyar-stat-card">
            <div class="fmyar-stat-icon fmyar-stat-icon-success">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="fmyar-stat-content">
                <span class="fmyar-stat-value"><?php echo esc_html($active_count); ?></span>
                <span class="fmyar-stat-label"><?php esc_html_e('Active', 'formyar'); ?></span>
            </div>
        </div>
        <div class="fmyar-stat-card">
            <div class="fmyar-stat-icon fmyar-stat-icon-warning">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="fmyar-stat-content">
                <span class="fmyar-stat-value"><?php echo esc_html($inactive_count); ?></span>
                <span class="fmyar-stat-label"><?php esc_html_e('Inactive', 'formyar'); ?></span>
            </div>
        </div>
        <div class="fmyar-stat-card">
            <div class="fmyar-stat-icon fmyar-stat-icon-info">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="fmyar-stat-content">
                <span class="fmyar-stat-value"><?php echo esc_html(number_format($total_entries)); ?></span>
                <span class="fmyar-stat-label"><?php esc_html_e('Total Entries', 'formyar'); ?></span>
            </div>
        </div>
    </div>

    <div class="fmyar-main-content" id="fmyar-main-content">
        <div class="fmyar-toolbar">
            <div class="fmyar-filter-tabs">
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-filter-tab <?php echo empty($status_filter) ? 'fmyar-filter-tab-active' : ''; ?>"><?php esc_html_e('All', 'formyar'); ?> <span class="fmyar-filter-count"><?php echo esc_html($total_forms); ?></span></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms&status=enable')); ?>" class="fmyar-filter-tab <?php echo $status_filter === 'enable' ? 'fmyar-filter-tab-active' : ''; ?>"><?php esc_html_e('Active', 'formyar'); ?> <span class="fmyar-filter-count fmyar-filter-count-green"><?php echo esc_html($active_count); ?></span></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms&status=disabled')); ?>" class="fmyar-filter-tab <?php echo $status_filter === 'disabled' ? 'fmyar-filter-tab-active' : ''; ?>"><?php esc_html_e('Inactive', 'formyar'); ?> <span class="fmyar-filter-count fmyar-filter-count-gray"><?php echo esc_html($inactive_count); ?></span></a>
            </div>

            <form method="get" class="fmyar-search-box">
                <input type="hidden" name="page" value="fmyar_forms">
                <?php if (!empty($status_filter)): ?>
                    <input type="hidden" name="status" value="<?php echo esc_attr($status_filter); ?>">
                <?php endif; ?>

                <svg class="fmyar-search-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>

                <input type="search"
                    name="search"
                    value="<?php echo esc_attr($search); ?>"
                    placeholder="<?php esc_attr_e('Search forms...', 'formyar'); ?>"
                    class="fmyar-search-input">
                <button type="submit" style="display:none" aria-hidden="true"></button>
            </form>
        </div>

        <form method="post">
            <?php wp_nonce_field('fmyar_forms_bulk'); ?>

            <?php if (!empty($forms)): ?>
                <div class="fmyar-bulk-bar">
                    <label class="fmyar-checkbox-label">
                        <input type="checkbox"
                            id="select-all-forms"
                            class="fmyar-checkbox-hidden"
                            aria-label="<?php esc_attr_e('Select all forms', 'formyar'); ?>">
                        <span class="fmyar-checkbox-custom" aria-hidden="true"></span>
                        <?php esc_html_e('Select All', 'formyar'); ?>
                    </label>
                    <select name="action">
                        <option value=""><?php esc_html_e('Bulk Actions', 'formyar'); ?></option>
                        <option value="enable"><?php esc_html_e('Enable', 'formyar'); ?></option>
                        <option value="disable"><?php esc_html_e('Disable', 'formyar'); ?></option>
                        <option value="export"><?php esc_html_e('Export Selected', 'formyar'); ?></option>
                        <option value="delete"><?php esc_html_e('Delete', 'formyar'); ?></option>
                        <?php
                        /**
                         * Action: Add Pro bulk action options.
                         *
                         * @since 1.1.0
                         */
                        do_action('fmyar_bulk_actions');
                        ?>
                    </select>
                    <button type="submit" name="bulk_action"><?php esc_html_e('Apply', 'formyar'); ?></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($search)): ?>
                <div class="fmyar-search-results-notice">
                    <?php
                    printf(
                        /* translators: %1$d: number of results, %2$s: search term */
                        esc_html__('Found %1$d result(s) for "%2$s"', 'formyar'),
                        esc_html($total_forms),
                        esc_html($search)
                    );
                    ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-clear-search">
                        <?php esc_html_e('Clear', 'formyar'); ?>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (!empty($forms)): ?>
                <div class="fmyar-forms-grid">
                    <?php foreach ($forms as $form_data):
                        $form        = new FormYar_Form($form_data->id);
                        $entry_count = $form->get_entry_count();
                        $is_active   = $form->is_active();
                        $edit_url    = admin_url('admin.php?page=fmyar_forms&action=edit&id=' . $form->get_id());
                        $export_url    = add_query_arg(['export_form' => $form->get_id(), 'nonce'       => wp_create_nonce('fmyar_export_form_' . $form->get_id()),], admin_url('admin.php?page=fmyar_forms'));
                    ?>
                        <div class="fmyar-form-card">
                            <label class="fmyar-form-card-check">
                                <input type="checkbox"
                                    name="selected[]"
                                    value="<?php echo esc_attr($form->get_id()); ?>"
                                    class="fmyar-checkbox-hidden"
                                    aria-label="<?php echo esc_attr(sprintf(
                                                    /* translators: %s: form title */
                                                    __('Select form: %s', 'formyar'),
                                                    $form->get_title()
                                                )); ?>">
                                <span class="fmyar-checkbox-custom" aria-hidden="true"></span>
                            </label>

                            <a href="<?php echo esc_url($edit_url); ?>" class="fmyar-form-card-body-link">
                                <div class="fmyar-form-card-body">
                                    <div class="fmyar-form-card-header">
                                        <div class="fmyar-form-card-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="fmyar-form-card-status">
                                            <span class="fmyar-status-dot <?php echo $is_active ? 'fmyar-status-active' : 'fmyar-status-inactive'; ?>"></span>
                                            <span class="fmyar-status-text"><?php echo $is_active ? esc_html__('Active', 'formyar') : esc_html__('Inactive', 'formyar'); ?></span>
                                        </div>
                                    </div>
                                    <h3 class="fmyar-form-card-title"><?php echo esc_html($form->get_title()); ?></h3>
                                    <?php if ($form->get_description()): ?>
                                        <p class="fmyar-form-card-desc"><?php echo esc_html(wp_trim_words($form->get_description(), 12)); ?></p>
                                    <?php endif; ?>
                                    <div class="fmyar-form-card-meta">
                                        <span class="fmyar-form-card-stat">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                            </svg>
                                            <?php
                                            /* translators: %d: number of entries */
                                            echo esc_html(sprintf(_n('%d entry', '%d entries', $entry_count, 'formyar'), $entry_count));
                                            ?>
                                        </span>
                                        <span class="fmyar-form-card-stat">
                                            <svg viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <?php echo esc_html($form->get_created_at('M j, Y')); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>

                            <div class="fmyar-form-card-footer">
                                <code class="fmyar-shortcode">[fmyar_form id="<?php echo esc_attr($form->get_id()); ?>"]</code>
                                <div class="fmyar-form-card-actions">
                                    <!-- Edit -->
                                    <a href="<?php echo esc_url($edit_url); ?>"
                                        class="fmyar-btn-icon-sm"
                                        aria-label="<?php esc_attr_e('Edit form', 'formyar'); ?>"
                                        title="<?php esc_attr_e('Edit', 'formyar'); ?>">
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <!-- Duplicate -->
                                    <button type="button"
                                        class="fmyar-btn-icon-sm fmyar-duplicate-form"
                                        data-form-id="<?php echo esc_attr($form->get_id()); ?>"
                                        aria-label="<?php esc_attr_e('Duplicate form', 'formyar'); ?>"
                                        title="<?php esc_attr_e('Duplicate', 'formyar'); ?>">
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                                            <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                            <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </button>


                                    <!-- Export -->
                                    <a href="<?php echo esc_url($export_url); ?>"
                                        class="fmyar-btn-icon-sm"
                                        title="<?php esc_attr_e('Export', 'formyar'); ?>">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button"
                                        class="fmyar-btn-icon-sm fmyar-delete-form"
                                        data-form-id="<?php echo esc_attr($form->get_id()); ?>"
                                        aria-label="<?php esc_attr_e('Delete form', 'formyar'); ?>"
                                        title="<?php esc_attr_e('Delete', 'formyar'); ?>">
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="fmyar-pagination-bar">
                        <p class="fmyar-pagination-info">
                            <?php
                            /* translators: %1$d: displayed forms count, %2$d: total forms count */
                            printf(esc_html__('Showing %1$d of %2$d forms', 'formyar'), count($forms), esc_html($total_forms));
                            ?>
                        </p>
                        <div class="fmyar-pagination-buttons">
                            <?php if ($current_page > 1): ?>
                                <a href="<?php echo esc_url(add_query_arg('paged', $current_page - 1)); ?>" class="fmyar-pagination-btn">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="fmyar-pagination-btn fmyar-pagination-btn-active"><?php echo esc_html($i); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo esc_url(add_query_arg('paged', $i)); ?>" class="fmyar-pagination-btn"><?php echo esc_html($i); ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <?php if ($current_page < $total_pages): ?>
                                <a href="<?php echo esc_url(add_query_arg('paged', $current_page + 1)); ?>" class="fmyar-pagination-btn">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="fmyar-empty-state-hero">

                    <!-- Header: Logo + Title inline -->
                    <div class="fmyar-empty-header">
                        <div class="fmyar-empty-logo-small">
                            <svg width="40" height="40" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" aria-hidden="true">
                                <g>
                                    <path opacity="1.00" d="M 79.67 0.00 L 287.92 0.00 C 295.71 1.05 303.60 -0.05 311.40 1.01 C 327.82 3.65 343.47 11.03 355.84 22.17 C 359.71 25.60 363.87 28.82 366.86 33.10 C 371.08 38.93 375.67 44.56 379.02 50.96 C 384.47 62.96 388.75 75.72 389.23 89.00 C 389.20 127.00 389.25 165.01 389.16 203.01 C 389.21 205.38 389.45 207.74 389.70 210.09 C 384.13 213.71 378.08 216.59 372.97 220.89 C 362.61 228.94 355.02 239.80 348.42 250.99 C 346.48 250.06 344.38 248.61 344.87 246.12 C 344.88 195.07 344.88 144.01 344.87 92.96 C 344.02 74.07 331.95 55.93 314.40 48.57 C 308.94 46.20 302.98 45.07 297.03 45.15 C 223.99 45.17 150.94 45.14 77.90 45.17 C 70.50 45.18 63.22 47.93 57.50 52.58 C 49.86 58.59 45.57 68.34 45.80 78.00 C 45.88 176.66 45.81 275.32 45.87 373.98 C 45.94 382.72 45.11 391.90 48.59 400.16 C 53.55 412.68 66.50 421.56 79.96 421.72 C 137.64 421.68 195.32 421.75 252.99 421.69 C 255.69 421.73 259.35 421.31 260.69 424.32 C 265.98 433.77 271.60 443.11 278.52 451.48 C 281.23 455.02 284.08 458.46 286.47 462.24 C 280.82 464.05 274.84 463.16 269.02 463.31 C 210.01 463.39 150.99 463.19 91.97 463.30 C 79.23 463.14 66.25 464.43 53.83 460.88 C 38.34 456.78 24.90 446.33 15.65 433.41 C 5.48 420.64 1.04 404.22 0.00 388.14 L 0.00 78.29 C 0.70 60.50 5.76 42.23 17.51 28.50 C 28.36 14.79 44.10 5.29 61.13 1.57 C 67.22 0.16 73.52 0.87 79.67 0.00 Z" />
                                    <path opacity="1.00" d="M 95.27 97.25 C 100.83 96.94 106.41 97.26 111.98 96.92 C 173.01 96.80 234.03 96.85 295.06 96.74 C 303.33 96.61 311.59 102.06 314.13 110.04 C 315.09 114.96 314.68 120.02 314.78 125.01 C 314.61 130.28 315.10 135.59 314.45 140.83 C 313.10 148.36 306.54 154.31 299.12 155.66 C 294.46 156.53 289.71 156.11 285.01 156.17 C 222.66 156.24 160.32 156.64 97.97 156.65 C 88.39 156.93 79.40 148.78 79.17 139.11 C 79.08 131.41 79.11 123.70 79.13 116.00 C 78.80 106.88 86.42 98.60 95.27 97.25 Z" />
                                    <path opacity="1.00" d="M 93.35 189.65 C 95.16 189.16 97.03 188.93 98.90 188.88 C 164.58 188.68 230.26 188.80 295.93 188.59 C 305.11 189.08 314.29 196.52 314.56 206.11 C 314.65 214.03 314.63 221.96 314.55 229.88 C 314.75 237.05 309.80 243.62 303.40 246.47 C 298.01 249.22 291.80 248.00 286.01 248.29 C 224.02 248.36 162.04 248.51 100.06 248.84 C 92.32 249.48 84.43 245.18 80.99 238.19 C 78.57 233.50 79.24 228.07 79.16 222.99 C 79.30 217.17 78.87 211.34 79.33 205.53 C 80.04 197.95 86.25 191.74 93.35 189.65 Z" />
                                    <path opacity="1.00" d="M 415.38 217.55 C 429.41 216.29 443.63 220.73 454.79 229.24 C 466.80 237.50 474.48 250.85 478.12 264.74 C 480.91 278.88 479.13 294.18 471.79 306.73 C 463.80 321.58 449.04 332.01 432.93 336.38 C 421.30 338.78 408.78 338.73 397.67 334.15 C 392.24 331.46 386.27 329.58 381.82 325.28 C 370.11 315.74 361.98 301.90 359.87 286.92 C 357.24 270.56 362.26 253.36 372.60 240.52 C 383.22 227.82 398.59 218.48 415.38 217.55 Z" />
                                    <path opacity="1.00" d="M 96.43 283.62 C 101.83 282.17 107.47 282.62 113.00 282.60 C 171.65 282.45 230.30 282.55 288.95 282.50 C 297.31 282.34 305.71 286.71 310.06 293.91 C 312.47 297.47 313.21 301.86 313.66 306.06 C 313.64 320.71 313.64 335.36 313.65 350.01 C 313.45 356.28 311.71 362.78 307.34 367.46 C 302.17 372.96 294.68 376.33 287.07 375.75 C 226.38 375.90 165.69 375.78 104.99 375.80 C 98.31 376.04 91.32 374.30 86.43 369.52 C 81.02 364.99 78.85 357.74 78.97 350.87 C 78.72 336.25 78.93 321.61 78.86 306.99 C 78.64 296.42 86.40 286.55 96.43 283.62 Z" />
                                    <path opacity="1.00" d="M 475.69 333.80 C 481.01 331.44 487.60 332.14 492.21 335.74 C 496.79 339.27 499.57 344.87 500.00 350.60 L 500.00 354.46 C 499.35 364.43 492.31 372.09 487.04 380.01 C 476.92 393.80 466.93 407.76 455.42 420.43 C 446.03 431.96 435.61 442.64 424.64 452.67 C 411.34 464.67 396.33 475.24 379.31 481.20 C 366.89 485.11 353.18 485.00 340.76 481.15 C 331.50 477.27 322.22 472.96 314.51 466.41 C 307.72 460.61 300.75 454.87 295.20 447.82 C 288.08 439.11 281.16 430.12 276.01 420.07 C 286.53 418.56 295.96 412.76 303.13 405.09 C 325.61 382.80 348.27 360.69 370.84 338.49 C 379.15 345.23 388.92 350.07 399.15 353.12 C 411.28 356.52 424.17 356.11 436.46 353.80 C 445.11 352.06 453.03 347.97 460.72 343.80 C 466.02 340.93 470.09 336.17 475.69 333.80 Z" />
                                </g>
                            </svg>
                        </div>
                        <h2 class="fmyar-empty-title-inline">
                            <?php esc_html_e('Welcome to FormYar', 'formyar'); ?>
                        </h2>
                    </div>

                    <!-- Description -->
                    <p class="fmyar-empty-desc-large">
                        <?php esc_html_e('Create your first form in minutes and start collecting data — no code required.', 'formyar'); ?>
                    </p>

                    <!-- Primary CTA -->
                    <button type="button" class="fmyar-btn fmyar-btn-primary fmyar-btn-lg fmyar-dynamic-modal"
                        data-modal-target="form_new" data-modal-item-id="0">
                        <svg class="fmyar-btn-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        <?php esc_html_e('Create Your First Form', 'formyar'); ?>
                    </button>

                    <!-- Templates -->
                    <div class="fmyar-empty-templates">
                        <div class="fmyar-empty-templates-grid">
                            <?php
                            $templates = [
                                'complete' => [
                                    'title' => __('Complete Form', 'formyar'),
                                    'desc'  => __('All 10 field types in one form', 'formyar'),
                                    'icon'  => 'dashicons-editor-table',
                                ],
                                'contact' => [
                                    'title' => __('Contact Form', 'formyar'),
                                    'desc'  => __('Name, email, subject, and message fields.', 'formyar'),
                                    'icon'  => 'dashicons-email',
                                ],
                                'survey' => [
                                    'title' => __('Survey', 'formyar'),
                                    'desc'  => __('Multiple choice questions and rating scale.', 'formyar'),
                                    'icon'  => 'dashicons-chart-bar',
                                ],
                                'employment' => [
                                    'title' => __('Employment', 'formyar'),
                                    'desc'  => __('Resume upload, cover letter, and contact info.', 'formyar'),
                                    'icon'  => 'dashicons-businessman',
                                ],
                                'reservation' => [
                                    'title' => __('Reservation', 'formyar'),
                                    'desc'  => __('Date, time, party size, and contact details.', 'formyar'),
                                    'icon'  => 'dashicons-calendar-alt',
                                ],
                            ];

                            foreach ($templates as $key => $template) :
                            ?>
                                <button type="button"
                                    class="fmyar-template-card"
                                    data-template="<?php echo esc_attr($key); ?>">
                                    <div class="fmyar-template-card-icon">
                                        <span class="dashicons <?php echo esc_attr($template['icon']); ?>"></span>
                                    </div>
                                    <div class="fmyar-template-card-content">
                                        <h4 class="fmyar-template-card-title"><?php echo esc_html($template['title']); ?></h4>
                                        <p class="fmyar-template-card-desc"><?php echo esc_html($template['desc']); ?></p>
                                    </div>
                                    <span class="fmyar-template-card-arrow dashicons dashicons-arrow-right-alt2"></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php fmyar_render_help_widget(); ?>
<?php fmyar_modal_container(); ?>