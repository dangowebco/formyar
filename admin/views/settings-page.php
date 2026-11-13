<?php

/**
 * Settings Page
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$recaptcha_enabled    = get_option('fmyar_recaptcha_enabled', '0');
$recaptcha_site_key   = get_option('fmyar_recaptcha_site_key', '');
$recaptcha_secret_key = get_option('fmyar_recaptcha_secret_key', '');
$delete_data          = get_option('fmyar_delete_data', '0');
?>

<div class="fmyar-app">
    <header class="fmyar-app-header">
        <div class="fmyar-app-header-inner">
            <?php
            fmyar_render_brand(
                __('Settings', 'formyar'),
                __('Configure FormYar', 'formyar')
            );
            ?>
        </div>
    </header>

    <div class="fmyar-main-content" id="fmyar-main-content">
        <form id="fmyar-settings-form">
            <?php wp_nonce_field('fmyar_settings', 'fmyar_settings_nonce'); ?>

            <div class="fmyar-settings-grid">

                <div class="fmyar-settings-card">
                    <div class="fmyar-settings-card-header">
                        <div class="fmyar-settings-icon fmyar-settings-icon-success">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="fmyar-settings-card-title"><?php esc_html_e('reCAPTCHA', 'formyar'); ?></h4>
                            <p class="fmyar-settings-card-desc"><?php esc_html_e('Protect forms from spam', 'formyar'); ?></p>
                        </div>
                    </div>
                    <div class="fmyar-settings-card-body">
                        <div class="fmyar-toggle-row">
                            <div>
                                <label class="fmyar-form-label"><?php esc_html_e('Enable reCAPTCHA', 'formyar'); ?></label>
                                <p class="fmyar-form-hint"><?php esc_html_e('Google reCAPTCHA v3 - invisible spam protection', 'formyar'); ?></p>
                            </div>
                            <!-- reCAPTCHA Toggle -->
                            <label class="fmyar-toggle-switch">
                                <input type="checkbox" class="fmyar-toggle-checkbox" value="1" <?php checked($recaptcha_enabled, '1'); ?>>
                                <span class="fmyar-toggle-track"><span class="fmyar-toggle-thumb"></span></span>
                            </label>
                            <input type="hidden" name="fmyar_recaptcha_enabled" id="recaptcha-input" value="<?php echo esc_attr($recaptcha_enabled); ?>">
                        </div>
                        <div class="fmyar-form-group">
                            <label class="fmyar-form-label"><?php esc_html_e('Site Key', 'formyar'); ?></label>
                            <input type="text" name="fmyar_recaptcha_site_key" value="<?php echo esc_attr($recaptcha_site_key); ?>" class="fmyar-input-full" dir="ltr">
                        </div>
                        <div class="fmyar-form-group">
                            <label class="fmyar-form-label"><?php esc_html_e('Secret Key', 'formyar'); ?></label>
                            <input type="text" name="fmyar_recaptcha_secret_key" value="<?php echo esc_attr($recaptcha_secret_key); ?>" class="fmyar-input-full" dir="ltr">
                        </div>
                        <p class="fmyar-form-hint">
                            <?php esc_html_e('Get keys from', 'formyar'); ?>
                            <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener">google.com/recaptcha</a>
                        </p>
                    </div>
                </div>

                <div class="fmyar-settings-card">
                    <div class="fmyar-settings-card-header">
                        <div class="fmyar-settings-icon fmyar-settings-icon-warning">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="fmyar-settings-card-title"><?php esc_html_e('Data Cleanup', 'formyar'); ?></h4>
                            <p class="fmyar-settings-card-desc"><?php esc_html_e('What happens when you delete the plugin', 'formyar'); ?></p>
                        </div>
                    </div>
                    <div class="fmyar-settings-card-body">
                        <div class="fmyar-toggle-row">
                            <div>
                                <label class="fmyar-form-label"><?php esc_html_e('Delete all data on uninstall', 'formyar'); ?></label>
                                <p class="fmyar-form-hint"><?php esc_html_e('Remove forms, entries, and settings when plugin is deleted', 'formyar'); ?></p>
                            </div>
                            <!-- Data Cleanup Toggle -->
                            <label class="fmyar-toggle-switch">
                                <input type="checkbox" class="fmyar-toggle-checkbox" value="1" <?php checked($delete_data, '1'); ?>>
                                <span class="fmyar-toggle-track"><span class="fmyar-toggle-thumb"></span></span>
                            </label>
                            <input type="hidden" name="fmyar_delete_data" id="delete-data-input" value="<?php echo esc_attr($delete_data); ?>">
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="fmyar-btn fmyar-btn-primary fmyar-mt-4">
                <?php esc_html_e('Save Settings', 'formyar'); ?>
            </button>
        </form>
    </div>
</div>
<?php fmyar_render_help_widget(); ?>