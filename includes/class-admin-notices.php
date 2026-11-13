<?php

/**
 * Admin Notices handler.
 *
 * Manages dismissible admin notices for FormYar.
 *
 * @package FormYar
 * @since   1.0.0
 */

defined('ABSPATH') || exit();

/**
 * Class FormYar_Admin_Notices
 *
 * Handles the display and dismissal of admin notices.
 *
 * @since 1.0.0
 */
class FormYar_Admin_Notices
{
    /**
     * User meta key for dismissed notices.
     *
     * @var string
     */
    const META_KEY = 'fmyar_dismissed_notices';

    /**
     * Option key for tracking plugin activation time.
     *
     * @var string
     */
    const ACTIVATION_KEY = 'fmyar_activation_time';

    /**
     * Initialize the notices system.
     *
     * @since 1.0.0
     * @return void
     */
    public static function init()
    {
        add_action('admin_notices', [__CLASS__, 'render_notices']);
        add_action('admin_init', [__CLASS__, 'handle_dismiss']);
    }

    /**
     * Register the activation time if not already set.
     *
     * Called on plugin activation.
     *
     * @since 1.0.0
     * @return void
     */
    public static function register_activation_time()
    {
        if (!get_option(self::ACTIVATION_KEY)) {
            update_option(self::ACTIVATION_KEY, time());
        }
    }

    /**
     * Get the list of notices to display.
     *
     * @since 1.0.0
     * @return array<int, array<string, mixed>> List of notice definitions.
     */
private static function get_notices()
{
    $notices    = [];
    $user_id    = get_current_user_id();
    $dismissed  = self::get_dismissed_notices($user_id);
    $now        = time();
    $activated  = (int) get_option(self::ACTIVATION_KEY, $now);
    $days_since = (int) floor(($now - $activated) / DAY_IN_SECONDS);

    // Get form count once.
    $form_count = class_exists('FormYar_Form') ? (int) FormYar_Form::count_all() : 0;

    // ─────────────────────────────────────────────
    // Notice 1: Welcome (until Welcome page is seen)
    // ─────────────────────────────────────────────
    if (
        !in_array('welcome', $dismissed, true)
        && !get_user_meta($user_id, 'fmyar_welcome_seen', true)
    ) {
        $notices[] = [
            'id'      => 'welcome',
            'type'    => 'info',
            'title'   => __('Welcome to FormYar!', 'formyar'),
            'message' => __('Thanks for installing FormYar. Let\'s create your first form.', 'formyar'),
            'actions' => [
                [
                    'label'   => __('Get Started', 'formyar'),
                    'url'     => admin_url('admin.php?page=fmyar_welcome'),
                    'style'   => 'primary',
                ],
                [
                    'label'   => __('Skip', 'formyar'),
                    'url'     => '#',
                    'style'   => 'secondary',
                    'dismiss' => true,
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // Notice 2: Review request (7 days + at least one form)
    // ─────────────────────────────────────────────
    if (
        $days_since >= 7
        && $form_count > 0
        && !in_array('review', $dismissed, true)
    ) {
        $notices[] = [
            'id'      => 'review',
            'type'    => 'success',
            'title'   => __('Enjoying FormYar?', 'formyar'),
            'message' => __('If you find FormYar helpful, please leave us a 5-star review. Your feedback helps us improve!', 'formyar'),
            'actions' => [
                [
                    'label'  => __('Leave a Review', 'formyar'),
                    'url'    => 'https://wordpress.org/support/plugin/formyar/reviews/#new-post',
                    'style'  => 'primary',
                    'target' => '_blank',
                ],
                [
                    'label'   => __('Maybe Later', 'formyar'),
                    'url'     => '#',
                    'style'   => 'secondary',
                    'dismiss' => true,
                ],
                [
                    'label'   => __('Already Reviewed', 'formyar'),
                    'url'     => '#',
                    'style'   => 'link',
                    'dismiss' => true,
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // Notice 3: reCAPTCHA recommended (14 days + reCAPTCHA disabled + forms exist)
    // ─────────────────────────────────────────────
    if (
        $days_since >= 14
        && $form_count > 0
        && get_option('fmyar_recaptcha_enabled', '0') !== '1'
        && !in_array('recaptcha', $dismissed, true)
    ) {
        $notices[] = [
            'id'      => 'recaptcha',
            'type'    => 'warning',
            'title'   => __('Protect Your Forms from Spam', 'formyar'),
            'message' => __('Enable Google reCAPTCHA v3 to protect your forms from spam submissions.', 'formyar'),
            'actions' => [
                [
                    'label' => __('Enable reCAPTCHA', 'formyar'),
                    'url'   => admin_url('admin.php?page=fmyar_settings'),
                    'style' => 'primary',
                ],
                [
                    'label'   => __('Dismiss', 'formyar'),
                    'url'     => '#',
                    'style'   => 'secondary',
                    'dismiss' => true,
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // Notice 4: Forms with no responses
    // ─────────────────────────────────────────────
    if (
        $form_count > 0
        && !in_array('no_entries', $dismissed, true)
    ) {
        global $wpdb;

        $table_entries = FORMYAR_TABLE_FORMS_ENTRIES;

        // Total entries.
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
        $total_entries = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_entries}");

        // Recent entries (last 7 days).
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
        $recent_entries = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$table_entries} WHERE created_at > DATE_SUB(NOW(), INTERVAL %d DAY)",
                7
            )
        );

        if ($total_entries === 0 || $recent_entries === 0) {
            $notices[] = [
                'id'      => 'no_entries',
                'type'    => 'info',
                'title'   => __('Your Forms Are Ready', 'formyar'),
                'message' => __('You\'ve created forms but haven\'t received any submissions yet. Make sure to embed the shortcode on a page or post to start collecting data.', 'formyar'),
                'actions' => [
                    [
                        'label' => __('View Forms', 'formyar'),
                        'url'   => admin_url('admin.php?page=fmyar_forms'),
                        'style' => 'primary',
                    ],
                    [
                        'label'  => __('Learn How', 'formyar'),
                        'url'    => 'https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/',
                        'style'  => 'secondary',
                        'target' => '_blank',
                    ],
                    [
                        'label'   => __('Dismiss', 'formyar'),
                        'url'     => '#',
                        'style'   => 'link',
                        'dismiss' => true,
                    ],
                ],
            ];
        }
    }

            // Notice 4: Pro teaser — shown after 30 days.
        // if (
        //     $days_since >= 30
        //     && !in_array('pro', $dismissed, true)
        // ) {
        //     $notices[] = [
        //         'id'      => 'pro',
        //         'type'    => 'info',
        //         'title'   => __('FormYar Pro is Coming Soon', 'formyar'),
        //         'message' => __('Get ready for advanced features: conditional logic, multi-step forms, payment integrations, and 30+ field types.', 'formyar'),
        //         'actions' => [
        //             [
        //                 'label' => __('Learn More', 'formyar'),
        //                 'url'   => 'https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/',
        //                 'style' => 'primary',
        //                 'target' => '_blank',
        //             ],
        //             [
        //                 'label' => __('Dismiss', 'formyar'),
        //                 'url'   => '#',
        //                 'style' => 'secondary',
        //                 'dismiss' => true,
        //             ],
        //         ],
        //     ];
        // }
        
    /**
     * Filter the list of admin notices.
     *
     * @since 1.0.0
     * @param array $notices List of notice definitions.
     */
    return apply_filters('fmyar_admin_notices', $notices);
}

    /**
     * Render the notices in the admin area.
     *
     * @since 1.0.0
     * @return void
     */
    public static function render_notices()
    {
        // Only show on FormYar pages or Dashboard.
        $screen = get_current_screen();
        if (!$screen) {
            return;
        }

        $is_formyar_page = strpos($screen->id, 'fmyar') !== false;
        $is_dashboard    = $screen->id === 'dashboard';

        if (!$is_formyar_page && !$is_dashboard) {
            return;
        }

        // Only show to admins.
        if (!current_user_can('manage_options')) {
            return;
        }

        $notices = self::get_notices();
        if (empty($notices)) {
            return;
        }

        foreach ($notices as $notice) {
            self::render_notice($notice);
        }
    }

    /**
     * Render a single notice.
     *
     * @since 1.0.0
     * @param array<string, mixed> $notice Notice definition.
     * @return void
     */
    private static function render_notice($notice)
    {
        $type       = $notice['type'] ?? 'info';
        $id         = $notice['id'] ?? '';
        $title      = $notice['title'] ?? '';
        $message    = $notice['message'] ?? '';
        $actions    = $notice['actions'] ?? [];
        $dismiss_url = wp_nonce_url(
            add_query_arg(
                [
                    'fmyar_dismiss_notice' => $id,
                ],
                admin_url()
            ),
            'fmyar_dismiss_notice_' . $id
        );

        $icon_map = [
            'info'    => 'dashicons-info',
            'success' => 'dashicons-yes-alt',
            'warning' => 'dashicons-warning',
            'error'   => 'dashicons-dismiss',
        ];
        $icon = $icon_map[$type] ?? 'dashicons-info';
?>
        <div class="notice fmyar-notice fmyar-notice-<?php echo esc_attr($type); ?>" data-notice-id="<?php echo esc_attr($id); ?>">
            <div class="fmyar-notice-icon">
                <span class="dashicons <?php echo esc_attr($icon); ?>"></span>
            </div>
            <div class="fmyar-notice-content">
                <h3 class="fmyar-notice-title"><?php echo esc_html($title); ?></h3>
                <p class="fmyar-notice-message"><?php echo esc_html($message); ?></p>
                <?php if (!empty($actions)) : ?>
                    <div class="fmyar-notice-actions">
                        <?php foreach ($actions as $action) :
                            $style  = $action['style'] ?? 'secondary';
                            $target = $action['target'] ?? '';
                            $is_dismiss = !empty($action['dismiss']);
                            $url = $is_dismiss ? $dismiss_url : ($action['url'] ?? '#');
                            $btn_class = 'fmyar-notice-btn fmyar-notice-btn-' . $style;
                        ?>
                            <a href="<?php echo esc_url($url); ?>"
                                class="<?php echo esc_attr($btn_class); ?>"
                                <?php if ($target) : ?>target="<?php echo esc_attr($target); ?>" rel="noopener noreferrer" <?php endif; ?>
                                <?php if ($is_dismiss) : ?>data-dismiss="true" <?php endif; ?>>
                                <?php echo esc_html($action['label']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" class="fmyar-notice-close" aria-label="<?php esc_attr_e('Dismiss', 'formyar'); ?>" data-dismiss-url="<?php echo esc_url($dismiss_url); ?>">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
<?php
    }

    /**
     * Handle notice dismissal via URL.
     *
     * @since 1.0.0
     * @return void
     */
    public static function handle_dismiss()
    {
        if (!isset($_GET['fmyar_dismiss_notice'])) {
            return;
        }

        $notice_id = sanitize_key(wp_unslash($_GET['fmyar_dismiss_notice']));

        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'] ?? '')), 'fmyar_dismiss_notice_' . $notice_id)) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        self::dismiss_notice(get_current_user_id(), $notice_id);

        wp_safe_redirect(remove_query_arg(['fmyar_dismiss_notice', '_wpnonce']));
        exit;
    }

    /**
     * Dismiss a notice for a specific user.
     *
     * @since 1.0.0
     * @param int    $user_id   User ID.
     * @param string $notice_id Notice ID.
     * @return void
     */
    public static function dismiss_notice($user_id, $notice_id)
    {
        $dismissed = self::get_dismissed_notices($user_id);
        if (!in_array($notice_id, $dismissed, true)) {
            $dismissed[] = $notice_id;
            update_user_meta($user_id, self::META_KEY, $dismissed);
        }
    }

    /**
     * Get dismissed notices for a user.
     *
     * @since 1.0.0
     * @param int $user_id User ID.
     * @return array<int, string> List of dismissed notice IDs.
     */
    private static function get_dismissed_notices($user_id)
    {
        $dismissed = get_user_meta($user_id, self::META_KEY, true);
        return is_array($dismissed) ? $dismissed : [];
    }
}

// Initialize.
FormYar_Admin_Notices::init();
