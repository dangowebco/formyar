<?php

/**
 * Welcome Page
 *
 * Displayed after plugin activation to guide users through initial setup.
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

// Mark welcome page as seen.
update_user_meta(get_current_user_id(), 'fmyar_welcome_seen', '1');

// Prevent direct access and ensure only admins can view this page.
if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have permission to view this page.', 'formyar'));
}

$forms_url    = admin_url('admin.php?page=fmyar_forms');
$settings_url = admin_url('admin.php?page=fmyar_settings');
$files_url    = admin_url('admin.php?page=fmyar_files');

// Check if any form exists to show contextual message.
$form_count = 0;
if (class_exists('FormYar_Form')) {
    $form_count = (int) FormYar_Form::count_all();
}
?>

<div class="fmyar-app fmyar-welcome">

    <div class="fmyar-welcome-hero">
        <div class="fmyar-welcome-logo">
            <svg width="80" height="80" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" aria-hidden="true">
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

        <h1 class="fmyar-welcome-title">
            <?php esc_html_e('Welcome to FormYar', 'formyar'); ?>
        </h1>

        <p class="fmyar-welcome-subtitle">
            <?php esc_html_e('Modern Form Builder for WordPress', 'formyar'); ?>
        </p>

        <p class="fmyar-welcome-description">
            <?php if ($form_count === 0) : ?>
                <?php esc_html_e('Thank you for installing FormYar! Build beautiful forms, surveys, and polls in minutes — no code required.', 'formyar'); ?>
            <?php else : ?>
                <?php
                /* translators: %d: number of forms. */
                printf(esc_html__('You currently have %d form(s). Keep creating beautiful forms!', 'formyar'), (int) $form_count);
                ?>
            <?php endif; ?>
        </p>
    </div>

    <div class="fmyar-welcome-content">

        <div class="fmyar-welcome-grid">

            <div class="fmyar-welcome-card">
                <div class="fmyar-welcome-card-icon">
                    <span class="dashicons dashicons-plus-alt2"></span>
                </div>
                <h3 class="fmyar-welcome-card-title"><?php esc_html_e('Create Your First Form', 'formyar'); ?></h3>
                <p class="fmyar-welcome-card-desc"><?php esc_html_e('Use the drag & drop builder to create a form in minutes.', 'formyar'); ?></p>
                <a href="<?php echo esc_url($forms_url); ?>" class="fmyar-btn fmyar-btn-primary">
                    <?php esc_html_e('Go to Forms', 'formyar'); ?>
                </a>
            </div>

            <div class="fmyar-welcome-card">
                <div class="fmyar-welcome-card-icon">
                    <span class="dashicons dashicons-admin-settings"></span>
                </div>
                <h3 class="fmyar-welcome-card-title"><?php esc_html_e('Configure Settings', 'formyar'); ?></h3>
                <p class="fmyar-welcome-card-desc"><?php esc_html_e('Enable reCAPTCHA and manage plugin preferences.', 'formyar'); ?></p>
                <a href="<?php echo esc_url($settings_url); ?>" class="fmyar-btn fmyar-btn-secondary">
                    <?php esc_html_e('Open Settings', 'formyar'); ?>
                </a>
            </div>

            <div class="fmyar-welcome-card">
                <div class="fmyar-welcome-card-icon">
                    <span class="dashicons dashicons-portfolio"></span>
                </div>
                <h3 class="fmyar-welcome-card-title"><?php esc_html_e('Manage Files', 'formyar'); ?></h3>
                <p class="fmyar-welcome-card-desc"><?php esc_html_e('View and manage all files uploaded through your forms.', 'formyar'); ?></p>
                <a href="<?php echo esc_url($files_url); ?>" class="fmyar-btn fmyar-btn-secondary">
                    <?php esc_html_e('Open Files', 'formyar'); ?>
                </a>
            </div>

        </div>

        <div class="fmyar-welcome-footer">
            <p class="fmyar-welcome-footer-text">
                <?php esc_html_e('Need help getting started? Check out our documentation or reach out to support.', 'formyar'); ?>
            </p>
            <div class="fmyar-welcome-footer-links">
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_about')); ?>" class="fmyar-link">
                    <?php esc_html_e('About FormYar', 'formyar'); ?>
                </a>
                <span class="fmyar-welcome-sep">·</span>
                <a href="https://wordpress.org/support/plugin/formyar/" target="_blank" rel="noopener noreferrer" class="fmyar-link">
                    <?php esc_html_e('Support Forum', 'formyar'); ?>
                </a>
                <span class="fmyar-welcome-sep">·</span>
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-link">
                    <?php esc_html_e('Skip & Start Building', 'formyar'); ?>
                </a>
            </div>
        </div>

    </div>
</div>