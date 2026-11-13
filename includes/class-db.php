<?php

/**
 * Database creation and migration
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

function fmyar_create_database()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $fmyar_db_version         = '1.0.0';
    $fmyar_db_version_current = get_option('fmyar_db_version');

    if ($fmyar_db_version_current === $fmyar_db_version) {
        return;
    }

    $table_forms     = FORMYAR_TABLE_FORMS;
    $table_entries   = FORMYAR_TABLE_FORMS_ENTRIES;
    $table_tracking  = FORMYAR_TABLE_FORMS_TRACKING;
    $charset_collate = $wpdb->get_charset_collate();

    // phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
    $sql = "CREATE TABLE {$table_forms} (
        `id` bigint(20) PRIMARY KEY AUTO_INCREMENT,
        `title` text NOT NULL,
        `description` text,
        `form` longtext NOT NULL,
        `type` varchar(50) NOT NULL,
        `settings` longtext,
        `created_by` bigint(20),
        `created_at` datetime,
        `updated_at` datetime,
        `status` varchar(50)
    ) $charset_collate;

    CREATE TABLE {$table_entries} (
        `id` bigint(20) PRIMARY KEY AUTO_INCREMENT,
        `form_id` bigint(20) NOT NULL,
        `user_id` bigint(20),
        `entry_data` longtext NOT NULL,
        `status` tinyint(1) DEFAULT 1,
        `created_at` datetime,
        KEY `idx_form_id` (`form_id`)
    ) $charset_collate;

    CREATE TABLE {$table_tracking} (
        `id` bigint(20) PRIMARY KEY AUTO_INCREMENT,
        `form_id` bigint(20) NOT NULL,
        `user_id` bigint(20) DEFAULT 0,
        `ip_address` varchar(45),
        `cookie_hash` varchar(64),
        `created_at` datetime,
        KEY `idx_form_id` (`form_id`),
        KEY `idx_cookie_hash` (`cookie_hash`)
    ) $charset_collate;";
    // phpcs:enable

    dbDelta($sql);

    fmyar_create_upload_htaccess();

    update_option('fmyar_db_version', $fmyar_db_version);
}

// Database update notice
add_action('admin_notices', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    $current  = get_option('fmyar_db_version', '0');
    $required = '1.0.0';

    if (version_compare($current, $required, '<')) {
        $update_url = add_query_arg(
            [
                'fmyar_update_db' => '1',
                '_fmyar_nonce'    => wp_create_nonce('fmyar_update_db'),
            ],
            admin_url()
        );
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong><?php esc_html_e('FormYar:', 'formyar'); ?></strong>
                <?php esc_html_e('Database update required.', 'formyar'); ?>
            </p>
            <p>
                <a href="<?php echo esc_url($update_url); ?>" class="button button-primary">
                    <?php esc_html_e('Update Database Now', 'formyar'); ?>
                </a>
            </p>
        </div>
        <?php
    }
});

// Manual update trigger
add_action('admin_init', function () {
    if (!isset($_GET['fmyar_update_db'], $_GET['_fmyar_nonce'])) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_GET['_fmyar_nonce']));

    if (!wp_verify_nonce($nonce, 'fmyar_update_db')) {
        return;
    }

    fmyar_create_database();
    wp_safe_redirect(remove_query_arg(['fmyar_update_db', '_fmyar_nonce']));
    exit;
});

// Create .htaccess in formyar-uploads folder
function fmyar_create_upload_htaccess()
{
    $upload_dir = wp_upload_dir();
    $target_dir = $upload_dir['basedir'] . '/formyar-uploads';

    if (!file_exists($target_dir)) {
        wp_mkdir_p($target_dir);
    }

    $htaccess_file = $target_dir . '/.htaccess';

    if (!file_exists($htaccess_file)) {
        $htaccess_content  = "# FormYar - Protect direct file access\n";
        $htaccess_content .= "<IfModule mod_authz_core.c>\n";
        $htaccess_content .= "    Require all denied\n";
        $htaccess_content .= "</IfModule>\n";
        $htaccess_content .= "<IfModule !mod_authz_core.c>\n";
        $htaccess_content .= "    Order deny,allow\n";
        $htaccess_content .= "    Deny from all\n";
        $htaccess_content .= "</IfModule>\n";

        WP_Filesystem();
        global $wp_filesystem;
        if ($wp_filesystem) {
            $wp_filesystem->put_contents($htaccess_file, $htaccess_content, FS_CHMOD_FILE);
        }
    }

    $index_file = $target_dir . '/index.php';
    if (!file_exists($index_file)) {
        WP_Filesystem();
        global $wp_filesystem;
        if ($wp_filesystem) {
            $wp_filesystem->put_contents($index_file, '<?php // Silence is golden', FS_CHMOD_FILE);
        }
    }
}