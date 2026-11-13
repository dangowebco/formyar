<?php

/**
 * Uninstall FormYar
 *
 * @package FormYar
 */

if (!defined('WP_UNINSTALL_PLUGIN')) exit();

// Check if user wants to delete all data
if (get_option('fmyar_delete_data', '0') === '1') {
    global $wpdb;

    // phpcs:disable WordPress.DB.DirectDatabaseQuery
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}fmyar_forms");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}fmyar_forms_entries");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}fmyar_forms_tracking");
    // phpcs:enable

    delete_option('fmyar_db_version');
    delete_option('fmyar_recaptcha_enabled');
    delete_option('fmyar_recaptcha_site_key');
    delete_option('fmyar_recaptcha_secret_key');
    delete_option('fmyar_delete_data');

    $upload_dir = wp_upload_dir();
    $target_dir = $upload_dir['basedir'] . '/formyar-uploads';

    if (is_dir($target_dir)) {
        $files = glob($target_dir . '/*.*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    wp_delete_file($file);
                }
            }
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
        rmdir($target_dir);
    }
}