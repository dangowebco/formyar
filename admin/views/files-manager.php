<?php

/**
 * Files Manager Page
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$upload_dir = wp_upload_dir();
$target_dir = $upload_dir['basedir'] . '/formyar-uploads';
$target_url = $upload_dir['baseurl'] . '/formyar-uploads';

// Get files
$files = [];
if (is_dir($target_dir)) {
    $scan = scandir($target_dir);
    foreach ($scan as $file) {
        if ($file === '.' || $file === '..' || $file === '.htaccess' || $file === 'index.php') continue;
        $file_path = $target_dir . '/' . $file;
        if (is_file($file_path)) {
            $files[] = [
                'name' => $file,
                'size' => filesize($file_path),
                'date' => filemtime($file_path),
                'path' => $file_path,
            ];
        }
    }

    usort($files, function ($a, $b) {
        return $b['date'] - $a['date'];
    });
}

$total_files = count($files);
$total_size  = array_sum(array_column($files, 'size'));
?>

<div class="fmyar-app">
    <header class="fmyar-app-header">
        <div class="fmyar-app-header-inner">
            <?php
            fmyar_render_brand(
                __('Files Manager', 'formyar'),
                sprintf(
                    /* translators: %1$d: number of files, %2$s: total size */
                    __('%1$d files · %2$s total', 'formyar'),
                    $total_files,
                    size_format($total_size)
                )
            );
            ?>
        </div>
    </header>

    <div class="fmyar-main-content">
        <?php if (!empty($files)): ?>
            <div class="fmyar-card fmyar-card-table">
                <div class="fmyar-table-wrapper">
                    <table class="fmyar-table-modern">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('File', 'formyar'); ?></th>
                                <th><?php esc_html_e('Size', 'formyar'); ?></th>
                                <th><?php esc_html_e('Date', 'formyar'); ?></th>
                                <th class="fmyar-table-actions-col"><?php esc_html_e('Actions', 'formyar'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file):
                                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                $download_url = add_query_arg([
                                    'fmyar_file'  => base64_encode($file['path']),
                                    'fmyar_token' => wp_hash($file['path'] . 'fmyar_download'),
                                ], home_url('/'));
                            ?>
                                <tr class="fmyar-table-row" data-file="<?php echo esc_attr($file['name']); ?>">
                                    <td class="fmyar-table-td">
                                        <div class="fmyar-flex fmyar-items-center fmyar-gap-2">
                                            <?php if ($is_image): ?>
                                                <img src="<?php echo esc_url($download_url); ?>" alt="" class="fmyar-file-thumb">
                                            <?php else: ?>
                                                <div class="fmyar-file-preview-icon <?php echo esc_attr(fmyar_get_file_icon_class($ext)); ?>">
                                                    <?php echo fmyar_get_file_icon_svg($ext); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                            <span class="fmyar-file-name" dir="ltr"><?php echo esc_html($file['name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="fmyar-table-td"><?php echo esc_html(size_format($file['size'])); ?></td>
                                    <td class="fmyar-table-td fmyar-table-date"><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $file['date'])); ?></td>
                                    <td class="fmyar-table-td">
                                        <div class="fmyar-table-actions">
                                            <a href="<?php echo esc_url($download_url); ?>" class="fmyar-action-btn" target="_blank" title="<?php esc_attr_e('View', 'formyar'); ?>">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <button type="button" class="fmyar-action-btn fmyar-action-btn-danger fmyar-delete-file" data-file="<?php echo esc_attr($file['name']); ?>" title="<?php esc_attr_e('Delete', 'formyar'); ?>">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="fmyar-empty-state-hero">
                <div class="fmyar-empty-header">
                    <div class="fmyar-empty-logo-small">
                        <span class="dashicons dashicons-portfolio" style="font-size: 32px; width: 32px; height: 32px;"></span>
                    </div>
                    <h2 class="fmyar-empty-title-inline"><?php esc_html_e('No files yet', 'formyar'); ?></h2>
                </div>
                <p class="fmyar-empty-desc-large"><?php esc_html_e('Uploaded form files will appear here.', 'formyar'); ?></p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=fmyar_forms')); ?>" class="fmyar-btn fmyar-btn-primary fmyar-btn-lg" style="margin-top: 20px;">
                    <span class="dashicons dashicons-plus-alt2"></span>
                    <?php esc_html_e('Create a Form', 'formyar'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php fmyar_render_help_widget(); ?>