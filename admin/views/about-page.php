<?php

/**
 * About Page
 *
 * Displays plugin information, changelog, and useful links.
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

// Restrict access to administrators only.
if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have permission to view this page.', 'formyar'));
}

// Get plugin data.
if (!function_exists('get_plugin_data')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$plugin_data = get_plugin_data(FORMYAR_FILE);

$current_version = $plugin_data['Version'] ?? FORMYAR_VERSION;
$requires_wp     = $plugin_data['RequiresWP'] ?? '5.8';
$requires_php    = $plugin_data['RequiresPHP'] ?? '7.4';

// Changelog 
$changelog = fmyar_get_changelog_from_readme();

$links = [
    'documentation' => [
        'label' => __('Documentation', 'formyar'),
        'url'   => 'https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/',
        'icon'  => 'dashicons-book-alt',
    ],
    'support' => [
        'label' => __('Support Forum', 'formyar'),
        'url'   => 'https://wordpress.org/support/plugin/formyar/',
        'icon'  => 'dashicons-sos',
    ],
    'reviews' => [
        'label' => __('Leave a Review', 'formyar'),
        'url'   => 'https://wordpress.org/support/plugin/formyar/reviews/#new-post',
        'icon'  => 'dashicons-star-filled',
    ],
    'product' => [
        'label' => __('Plugin Page', 'formyar'),
        'url'   => 'https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/',
        'icon'  => 'dashicons-admin-links',
    ],
    'feedback' => [
        'label' => __('Send Feedback', 'formyar'),
        'url'   => 'https://dangoweb.ir/contact/',
        'icon'  => 'dashicons-email-alt',
    ],
];
?>

<div class="fmyar-app fmyar-about">

    <header class="fmyar-app-header">
        <div class="fmyar-app-header-inner">
            <?php
            fmyar_render_brand(
                __('About FormYar', 'formyar'),
                __('Modern Form Builder for WordPress', 'formyar')
            );
            ?>

            <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-btn fmyar-btn-secondary fmyar-btn-sm">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                <?php esc_html_e('Back to Forms', 'formyar'); ?>
            </a>
        </div>
    </header>

    <div class="fmyar-main-content">

        <!-- Hero / Version Info -->
        <div class="fmyar-about-hero">
            <div class="fmyar-about-hero-content">
                <h2 class="fmyar-about-hero-title">
                    <?php
                    /* translators: %s: plugin version. */
                    printf(esc_html__('FormYar %s', 'formyar'), esc_html($current_version));
                    ?>
                </h2>
                <p class="fmyar-about-hero-desc">
                    <?php esc_html_e('A powerful yet lightweight form builder for WordPress — built with love for Persian users.', 'formyar'); ?>
                </p>
                <div class="fmyar-about-meta">
                    <span class="fmyar-about-meta-item">
                        <span class="dashicons dashicons-wordpress"></span>
                        <?php
                        /* translators: %s: WordPress version. */
                        printf(esc_html__('Requires WordPress %s+', 'formyar'), esc_html($requires_wp));
                        ?>
                    </span>
                    <span class="fmyar-about-meta-item">
                        <span class="dashicons dashicons-editor-code"></span>
                        <?php
                        /* translators: %s: PHP version. */
                        printf(esc_html__('Requires PHP %s+', 'formyar'), esc_html($requires_php));
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Useful Links -->
        <div class="fmyar-about-section">
            <h3 class="fmyar-about-section-title"><?php esc_html_e('Useful Links', 'formyar'); ?></h3>
            <div class="fmyar-about-links-grid">
                <?php foreach ($links as $key => $link) : ?>
                    <a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener noreferrer" class="fmyar-about-link-card">
                        <span class="fmyar-about-link-icon dashicons <?php echo esc_attr($link['icon']); ?>"></span>
                        <span class="fmyar-about-link-label"><?php echo esc_html($link['label']); ?></span>
                        <span class="fmyar-about-link-arrow dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Changelog -->
        <?php if (!empty($changelog)): ?>
            <div class="fmyar-about-section">
                <h3 class="fmyar-about-section-title"><?php esc_html_e('Changelog', 'formyar'); ?></h3>
                <div class="fmyar-about-changelog">
                    <?php foreach ($changelog as $release) : ?>
                        <div class="fmyar-changelog-item">
                            <div class="fmyar-changelog-header">
                                <span class="fmyar-changelog-version"><?php echo esc_html($release['version']); ?></span>
                            </div>
                            <ul class="fmyar-changelog-list">
                                <?php foreach ($release['items'] as $item) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Developer Info -->
        <div class="fmyar-about-section">
            <h3 class="fmyar-about-section-title"><?php esc_html_e('Developer', 'formyar'); ?></h3>
            <div class="fmyar-about-developer">
                <div class="fmyar-about-developer-info">
                    <h4><?php esc_html_e('Dango Web', 'formyar'); ?></h4>
                    <p><?php esc_html_e('We build modern WordPress plugins and tools for Persian users.', 'formyar'); ?></p>
                    <div class="fmyar-about-developer-links">
                        <a href="https://dangoweb.ir/" target="_blank" rel="noopener noreferrer" class="fmyar-link">
                            <?php esc_html_e('Website', 'formyar'); ?>
                        </a>
                        <span class="fmyar-welcome-sep">·</span>
                        <a href="https://wordpress.org/plugins/formyar/" target="_blank" rel="noopener noreferrer" class="fmyar-link">
                            <?php esc_html_e('WordPress.org', 'formyar'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>