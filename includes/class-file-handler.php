<?php

/**
 * File download handler
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

// Handle file download requests
add_action('init', 'fmyar_handle_file_download');
function fmyar_handle_file_download()
{
    if (!isset($_GET['fmyar_file']) || !isset($_GET['fmyar_token'])) {
        return;
    }

    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $file_path = base64_decode(wp_unslash($_GET['fmyar_file']));
    $token     = sanitize_text_field(wp_unslash($_GET['fmyar_token']));

    if ($token !== wp_hash($file_path . 'fmyar_download')) {
        wp_die(esc_html__('Invalid download link.', 'formyar'));
    }

    if (!file_exists($file_path)) {
        wp_die(esc_html__('File not found.', 'formyar'));
    }

    $upload_dir   = wp_upload_dir();
    $allowed_path = $upload_dir['basedir'] . '/formyar-uploads';
    if (strpos(realpath($file_path), realpath($allowed_path)) !== 0) {
        wp_die(esc_html__('Access denied.', 'formyar'));
    }

    $file_name = basename($file_path);
    $file_size = filesize($file_path);
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $mime_types = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'zip'  => 'application/zip',
        'mp4'  => 'video/mp4',
        'mp3'  => 'audio/mpeg',
    ];

    $mime_type = $mime_types[$file_ext] ?? 'application/octet-stream';

    header('Content-Type: ' . $mime_type);
    header('Content-Length: ' . $file_size);
    header('Cache-Control: private, max-age=3600');
    header('X-Content-Type-Options: nosniff');

    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
        header('Content-Disposition: inline; filename="' . $file_name . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
    }

    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
    readfile($file_path);
    exit;
}