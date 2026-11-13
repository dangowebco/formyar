<?php

/**
 * Plugin Name: FormYar – Form Builder, Surveys & Polls
 * Plugin URI: https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/
 * Description: Create and manage forms, surveys, and polls in WordPress with entry management, file uploads, and Jalali date support.
 * Version: 1.1.1
 * Author: Dango Web
 * Author URI: https://dangoweb.ir/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: formyar
 * Domain Path: /languages
 * Requires at least: 6.5
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit();

// Plugin Constants
define('FORMYAR_VERSION', '1.1.1');
define('FORMYAR_PATH', plugin_dir_path(__FILE__));
define('FORMYAR_URL', plugin_dir_url(__FILE__));
define('FORMYAR_BASENAME', plugin_basename(__FILE__));
define('FORMYAR_FILE', __FILE__);

// Database table names
define('FORMYAR_TABLE_FORMS', $GLOBALS['wpdb']->prefix . 'fmyar_forms');
define('FORMYAR_TABLE_FORMS_ENTRIES', $GLOBALS['wpdb']->prefix . 'fmyar_forms_entries');
define('FORMYAR_TABLE_FORMS_TRACKING', $GLOBALS['wpdb']->prefix . 'fmyar_forms_tracking');

// Core classes
require_once FORMYAR_PATH . 'includes/class-db.php';
require_once FORMYAR_PATH . 'includes/class-abstract-field.php';
require_once FORMYAR_PATH . 'includes/class-form.php';
require_once FORMYAR_PATH . 'includes/class-form-builder.php';
require_once FORMYAR_PATH . 'includes/functions.php';
require_once FORMYAR_PATH . 'includes/class-file-handler.php';
require_once FORMYAR_PATH . 'includes/class-admin-notices.php';

// Field type classes
require_once FORMYAR_PATH . 'includes/fields/class-field-text.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-textarea.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-number.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-email.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-select.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-radio.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-checkbox.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-date.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-date-jalali.php';
require_once FORMYAR_PATH . 'includes/fields/class-field-file.php';

// Load textdomain
add_action('init', 'fmyar_load_textdomain');
function fmyar_load_textdomain()
{
    // WordPress handles translation automatically for plugins hosted on wp.org
}

// Activation hook
register_activation_hook(FORMYAR_FILE, 'fmyar_activate');
function fmyar_activate()
{
    fmyar_create_database();
    fmyar_create_upload_htaccess();
    flush_rewrite_rules();

    // Register activation time for admin notices.
    if (class_exists('FormYar_Admin_Notices')) {
        FormYar_Admin_Notices::register_activation_time();
    }

    // Flag to redirect to welcome page after activation.
    set_transient('fmyar_activation_redirect', 1, 30);
}

// Redirect to Welcome page after activation (only once, only for admins).
add_action('admin_init', 'fmyar_maybe_redirect_to_welcome');
function fmyar_maybe_redirect_to_welcome()
{
    // Skip during AJAX / REST / cron / bulk activate.
    if (wp_doing_ajax() || defined('REST_REQUEST') || wp_doing_cron()) {
        return;
    }
    if (isset($_GET['activate-multi'])) {
        return;
    }

    // Only redirect for admins who can manage options.
    if (!current_user_can('manage_options')) {
        return;
    }

    // Only redirect once — flag is cleared after reading.
    if (!get_transient('fmyar_activation_redirect')) {
        return;
    }
    delete_transient('fmyar_activation_redirect');

    // Do not redirect if user is already on the welcome page.
    if (isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'fmyar_welcome') {
        return;
    }

    wp_safe_redirect(admin_url('admin.php?page=fmyar_welcome'));
    exit;
}

// Deactivation hook
register_deactivation_hook(FORMYAR_FILE, 'fmyar_deactivate');
function fmyar_deactivate()
{
    flush_rewrite_rules();
}

// Enqueue admin assets
add_action('admin_enqueue_scripts', 'fmyar_admin_assets');
function fmyar_admin_assets($hook)
{

  wp_enqueue_style(
        'fmyar-notice',
        FORMYAR_URL . 'assets/css/notice.css',
        [],
        FORMYAR_VERSION
    );

    $is_formyar_page = (
        strpos($hook, 'fmyar_forms') !== false ||
        strpos($hook, 'fmyar_files') !== false ||
        strpos($hook, 'fmyar_settings') !== false ||
        strpos($hook, 'fmyar_about') !== false ||
        strpos($hook, 'fmyar_welcome') !== false
    );

    if (!$is_formyar_page) {
        return;
    }

    wp_enqueue_style('dashicons');

    wp_enqueue_style('fmyar-admin', FORMYAR_URL . 'assets/css/admin.css', [], FORMYAR_VERSION);
    wp_enqueue_style('fmyar-jalali-date', FORMYAR_URL . 'assets/css/jalaliDate.css', [], FORMYAR_VERSION);

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('fmyar-jalali-date', FORMYAR_URL . 'assets/js/jalaliDate.js', ['jquery'], FORMYAR_VERSION, true);
    wp_enqueue_script('fmyar-form-builder', FORMYAR_URL . 'assets/js/form-builder.js', ['jquery', 'jquery-ui-sortable', 'fmyar-jalali-date'], FORMYAR_VERSION, true);

    wp_localize_script('fmyar-form-builder', 'fmyar_admin', [
        'ajax_url'   => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('fmyar_admin_modal_nonce'),
        'edit_nonce' => wp_create_nonce('fmyar_edit_form_nonce'),
        'i18n'       => [
            'confirm_delete_form'          => __('Are you sure you want to delete this form? This action cannot be undone.', 'formyar'),
            'confirm_delete_entry'         => __('Are you sure you want to delete this entry?', 'formyar'),
            'confirm_delete_field'         => __('Remove this field?', 'formyar'),
            'confirm_delete_file'          => __('Delete this file?', 'formyar'),
            'confirm_duplicate'            => __('Duplicate this form?', 'formyar'),
            'confirm_import'               => __('Import this form? The imported form will be disabled until you enable it.', 'formyar'),
            'confirm_template'             => __('Create a new form from this template?', 'formyar'),
            'saving'                       => __('Saving...', 'formyar'),
            'saved'                        => __('Saved!', 'formyar'),
            'error'                        => __('An error occurred.', 'formyar'),
            'no_fields'                    => __('No fields yet', 'formyar'),
            'create_form'                  => __('Create Form', 'formyar'),
            'add_field'                    => __('Add Field', 'formyar'),
            'field_settings'               => __('Field Settings', 'formyar'),
            'cancel'                       => __('Cancel', 'formyar'),
            'title_required'               => __('Form title is required.', 'formyar'),
            'form_title'                   => __('Form Title', 'formyar'),
            'description'                  => __('Description', 'formyar'),
            'optional'                     => __('optional', 'formyar'),
            'form_description_placeholder' => __('Brief description of this form...', 'formyar'),
            'creating'                     => __('Creating...', 'formyar'),
            'importing'                    => __('Importing...', 'formyar'),
            'selected'                     => __('selected', 'formyar'),

            'untitled_field'               => __('Untitled Field', 'formyar'),
            'required_badge'               => __('Required', 'formyar'),
            'option_label'                 => __('Label', 'formyar'),
            'option_value'                 => __('Value (optional)', 'formyar'),

            'entries'                      => __('entries', 'formyar'),
            'entry'                        => __('entry', 'formyar'),
            'edit'                         => __('Edit', 'formyar'),
            'duplicate'                    => __('Duplicate', 'formyar'),
            'export'                       => __('Export', 'formyar'),
            'delete'                       => __('Delete', 'formyar'),
            'active'                       => __('Active', 'formyar'),
            'inactive'                     => __('Inactive', 'formyar'),
            'copy'                         => __('Copy', 'formyar'),

            'loading_entries'              => __('Loading entries...', 'formyar'),
            'form_id_not_found'            => __('Form ID not found', 'formyar'),

            'error_loading_field_types'    => __('Error loading field types', 'formyar'),
            'error_loading_field_settings' => __('Error loading field settings', 'formyar'),
            'error_duplicating_form'       => __('Error duplicating form', 'formyar'),
            'error_deleting_form'          => __('Error deleting form', 'formyar'),
            'error_deleting_entry'         => __('Error deleting entry', 'formyar'),
            'error_deleting_file'          => __('Error deleting file', 'formyar'),
            'error_saving_form'            => __('Error saving form', 'formyar'),
            'error_saving_settings'        => __('Error saving settings', 'formyar'),
            'error_creating_form'          => __('Error creating form', 'formyar'),
            'error_importing_form'         => __('Error importing form', 'formyar'),

            'copied'                       => __('Copied!', 'formyar'),
            'copy_failed'                  => __('Failed to copy', 'formyar'),

            'select_json_file'             => __('Please select a JSON file', 'formyar'),
            'file_too_large_1mb'           => __('File is too large. Maximum size is 1 MB.', 'formyar'),
            'upload_json_only'             => __('Please upload a JSON file', 'formyar'),

        ]
    ]);
}

// Enqueue frontend assets
add_action('wp_enqueue_scripts', 'fmyar_frontend_assets');
function fmyar_frontend_assets()
{
    wp_enqueue_style('fmyar-public', FORMYAR_URL . 'assets/css/formyar-public.css', [], FORMYAR_VERSION);
    wp_enqueue_style('fmyar-jalali-date', FORMYAR_URL . 'assets/css/jalaliDate.css', [], FORMYAR_VERSION);

    wp_enqueue_script('jquery');
    wp_enqueue_script('fmyar-jalali-date', FORMYAR_URL . 'assets/js/jalaliDate.js', ['jquery'], FORMYAR_VERSION, true);

    if (get_option('fmyar_recaptcha_enabled', '0') === '1') {
        $site_key = get_option('fmyar_recaptcha_site_key', '');
        if ($site_key) {
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . urlencode($site_key), [], FORMYAR_VERSION, true);
        }
    }

    wp_enqueue_script('fmyar-public', FORMYAR_URL . 'assets/js/form-public.js', ['jquery', 'fmyar-jalali-date'], FORMYAR_VERSION, true);

    wp_localize_script('fmyar-public', 'fmyar_public', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('fmyar_form_submit'),
        'i18n'     => [
            'uploading'      => __('Uploading...', 'formyar'),
            'upload_error'   => __('Upload failed. Please try again.', 'formyar'),
            'file_too_large' => __('File is too large.', 'formyar'),
            'invalid_type'   => __('File type not allowed.', 'formyar'),
            'max_files'      => __('Maximum files reached.', 'formyar'),
            'required_field' => __('This field is required.', 'formyar'),
            'submitting'     => __('Submitting...', 'formyar'),
            'submit'         => __('Submit', 'formyar'),
            'error'          => __('An error occurred.', 'formyar'),
            'close'          => __('Close', 'formyar'),
            'file'           => __('File', 'formyar'),
        ],
        'recaptcha_enabled'  => get_option('fmyar_recaptcha_enabled', '0'),
        'recaptcha_site_key' => get_option('fmyar_recaptcha_site_key', ''),
    ]);
}
