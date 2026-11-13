<?php

/**
 * FormYar Functions
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

// Register shortcode
add_shortcode('fmyar_form', ['FormYar_Form', 'shortcode']);

/**
 * Register custom shortcodes.
 *
 * Allows Pro to register its own shortcodes.
 *
 * @since 1.1.0
 * @return void
 */
add_action('init', 'fmyar_register_shortcodes');
function fmyar_register_shortcodes()
{
    /**
     * Action: Register custom shortcodes.
     *
     * Pro can register its own shortcodes via this hook.
     *
     * Example usage:
     * add_action('fmyar_register_shortcodes', function() {
     *     add_shortcode('fmyar_poll', 'my_poll_shortcode');
     * });
     *
     * @since 1.1.0
     */
    do_action('fmyar_register_shortcodes');
}

/**
 * Register Gutenberg blocks.
 *
 * Allows Pro to register its own Gutenberg blocks.
 *
 * @since 1.1.0
 * @return void
 */
add_action('init', 'fmyar_register_blocks');
function fmyar_register_blocks()
{
    // Only if Gutenberg is available.
    if (!function_exists('register_block_type')) {
        return;
    }

    /**
     * Action: Register Gutenberg blocks.
     *
     * Pro can register its own blocks via this hook.
     *
     * Example usage:
     * add_action('fmyar_register_blocks', function() {
     *     register_block_type('formyar/poll', [
     *         'render_callback' => 'my_poll_block_render',
     *     ]);
     * });
     *
     * @since 1.1.0
     */
    do_action('fmyar_register_blocks');
}

// Register admin menu
add_action('admin_menu', function () {

    //Menu icon
    $icon_svg = 'data:image/svg+xml;base64,' . base64_encode('<svg width="24" height="24" viewBox="0 0 500 500" version="1.1" xmlns="http://www.w3.org/2000/svg"> <g id="#f27259ff"> <path fill="#f27259" opacity="1.00" d=" M 79.67 0.00 L 287.92 0.00 C 295.71 1.05 303.60 -0.05 311.40 1.01 C 327.82 3.65 343.47 11.03 355.84 22.17 C 359.71 25.60 363.87 28.82 366.86 33.10 C 371.08 38.93 375.67 44.56 379.02 50.96 C 384.47 62.96 388.75 75.72 389.23 89.00 C 389.20 127.00 389.25 165.01 389.16 203.01 C 389.21 205.38 389.45 207.74 389.70 210.09 C 384.13 213.71 378.08 216.59 372.97 220.89 C 362.61 228.94 355.02 239.80 348.42 250.99 C 346.48 250.06 344.38 248.61 344.87 246.12 C 344.88 195.07 344.88 144.01 344.87 92.96 C 344.02 74.07 331.95 55.93 314.40 48.57 C 308.94 46.20 302.98 45.07 297.03 45.15 C 223.99 45.17 150.94 45.14 77.90 45.17 C 70.50 45.18 63.22 47.93 57.50 52.58 C 49.86 58.59 45.57 68.34 45.80 78.00 C 45.88 176.66 45.81 275.32 45.87 373.98 C 45.94 382.72 45.11 391.90 48.59 400.16 C 53.55 412.68 66.50 421.56 79.96 421.72 C 137.64 421.68 195.32 421.75 252.99 421.69 C 255.69 421.73 259.35 421.31 260.69 424.32 C 265.98 433.77 271.60 443.11 278.52 451.48 C 281.23 455.02 284.08 458.46 286.47 462.24 C 280.82 464.05 274.84 463.16 269.02 463.31 C 210.01 463.39 150.99 463.19 91.97 463.30 C 79.23 463.14 66.25 464.43 53.83 460.88 C 38.34 456.78 24.90 446.33 15.65 433.41 C 5.48 420.64 1.04 404.22 0.00 388.14 L 0.00 78.29 C 0.70 60.50 5.76 42.23 17.51 28.50 C 28.36 14.79 44.10 5.29 61.13 1.57 C 67.22 0.16 73.52 0.87 79.67 0.00 Z" /> <path fill="#f27259" opacity="1.00" d=" M 95.27 97.25 C 100.83 96.94 106.41 97.26 111.98 96.92 C 173.01 96.80 234.03 96.85 295.06 96.74 C 303.33 96.61 311.59 102.06 314.13 110.04 C 315.09 114.96 314.68 120.02 314.78 125.01 C 314.61 130.28 315.10 135.59 314.45 140.83 C 313.10 148.36 306.54 154.31 299.12 155.66 C 294.46 156.53 289.71 156.11 285.01 156.17 C 222.66 156.24 160.32 156.64 97.97 156.65 C 88.39 156.93 79.40 148.78 79.17 139.11 C 79.08 131.41 79.11 123.70 79.13 116.00 C 78.80 106.88 86.42 98.60 95.27 97.25 Z" /> <path fill="#f27259" opacity="1.00" d=" M 93.35 189.65 C 95.16 189.16 97.03 188.93 98.90 188.88 C 164.58 188.68 230.26 188.80 295.93 188.59 C 305.11 189.08 314.29 196.52 314.56 206.11 C 314.65 214.03 314.63 221.96 314.55 229.88 C 314.75 237.05 309.80 243.62 303.40 246.47 C 298.01 249.22 291.80 248.00 286.01 248.29 C 224.02 248.36 162.04 248.51 100.06 248.84 C 92.32 249.48 84.43 245.18 80.99 238.19 C 78.57 233.50 79.24 228.07 79.16 222.99 C 79.30 217.17 78.87 211.34 79.33 205.53 C 80.04 197.95 86.25 191.74 93.35 189.65 Z" /> <path fill="#f27259" opacity="1.00" d=" M 415.38 217.55 C 429.41 216.29 443.63 220.73 454.79 229.24 C 466.80 237.50 474.48 250.85 478.12 264.74 C 480.91 278.88 479.13 294.18 471.79 306.73 C 463.80 321.58 449.04 332.01 432.93 336.38 C 421.30 338.78 408.78 338.73 397.67 334.15 C 392.24 331.46 386.27 329.58 381.82 325.28 C 370.11 315.74 361.98 301.90 359.87 286.92 C 357.24 270.56 362.26 253.36 372.60 240.52 C 383.22 227.82 398.59 218.48 415.38 217.55 Z" /> <path fill="#f27259" opacity="1.00" d=" M 96.43 283.62 C 101.83 282.17 107.47 282.62 113.00 282.60 C 171.65 282.45 230.30 282.55 288.95 282.50 C 297.31 282.34 305.71 286.71 310.06 293.91 C 312.47 297.47 313.21 301.86 313.66 306.06 C 313.64 320.71 313.64 335.36 313.65 350.01 C 313.45 356.28 311.71 362.78 307.34 367.46 C 302.17 372.96 294.68 376.33 287.07 375.75 C 226.38 375.90 165.69 375.78 104.99 375.80 C 98.31 376.04 91.32 374.30 86.43 369.52 C 81.02 364.99 78.85 357.74 78.97 350.87 C 78.72 336.25 78.93 321.61 78.86 306.99 C 78.64 296.42 86.40 286.55 96.43 283.62 Z" /> <path fill="#f27259" opacity="1.00" d=" M 475.69 333.80 C 481.01 331.44 487.60 332.14 492.21 335.74 C 496.79 339.27 499.57 344.87 500.00 350.60 L 500.00 354.46 C 499.35 364.43 492.31 372.09 487.04 380.01 C 476.92 393.80 466.93 407.76 455.42 420.43 C 446.03 431.96 435.61 442.64 424.64 452.67 C 411.34 464.67 396.33 475.24 379.31 481.20 C 366.89 485.11 353.18 485.00 340.76 481.15 C 331.50 477.27 322.22 472.96 314.51 466.41 C 307.72 460.61 300.75 454.87 295.20 447.82 C 288.08 439.11 281.16 430.12 276.01 420.07 C 286.53 418.56 295.96 412.76 303.13 405.09 C 325.61 382.80 348.27 360.69 370.84 338.49 C 379.15 345.23 388.92 350.07 399.15 353.12 C 411.28 356.52 424.17 356.11 436.46 353.80 C 445.11 352.06 453.03 347.97 460.72 343.80 C 466.02 340.93 470.09 336.17 475.69 333.80 Z" /> </g> </svg>');

    add_menu_page(
        __('FormYar', 'formyar'),
        __('FormYar', 'formyar'),
        'manage_options',
        'fmyar_forms',
        'fmyar_forms_page',
        $icon_svg,
        6.5
    );

    add_submenu_page(
        'fmyar_forms',
        __('Files Manager', 'formyar'),
        __('Files', 'formyar'),
        'administrator',
        'fmyar_files',
        'fmyar_files_page'
    );

    add_submenu_page(
        'fmyar_forms',
        __('Settings', 'formyar'),
        __('Settings', 'formyar'),
        'administrator',
        'fmyar_settings',
        'fmyar_settings_page'
    );

    // Hidden Welcome page — accessible only via direct URL.
    add_submenu_page(
        null,
        __('Welcome to FormYar', 'formyar'),
        __('Welcome', 'formyar'),
        'manage_options',
        'fmyar_welcome',
        'fmyar_welcome_page'
    );

    // Hidden About page — accessible only via direct URL.
    add_submenu_page(
        null, // No parent — hidden from menu.
        __('About FormYar', 'formyar'),
        __('About', 'formyar'),
        'manage_options',
        'fmyar_about',
        'fmyar_about_page'
    );

    /**
     * Action: Register Pro admin menus.
     *
     * Allows Pro to add its own submenus (License, Analytics, etc.).
     *
     * @since 1.1.0
     */
    do_action('fmyar_admin_menu');
});

/**
 * Register REST API routes.
 *
 * Allows Pro to register its own REST endpoints.
 *
 * @since 1.1.0
 * @return void
 */
add_action('rest_api_init', 'fmyar_register_rest_routes');
function fmyar_register_rest_routes()
{
    /**
     * Action: Register REST API routes.
     *
     * Pro can register its own routes via this hook.
     *
     * Example usage:
     * add_action('fmyar_rest_routes', function() {
     *     register_rest_route('formyar/v1', '/analytics', [
     *         'methods'  => 'GET',
     *         'callback' => 'my_analytics_callback',
     *         'permission_callback' => function() {
     *             return current_user_can('manage_options');
     *         },
     *     ]);
     * });
     *
     * @since 1.1.0
     */
    do_action('fmyar_rest_routes');
}

function fmyar_files_page()
{
    include FORMYAR_PATH . 'admin/views/files-manager.php';
}

function fmyar_settings_page()
{
    include FORMYAR_PATH . 'admin/views/settings-page.php';
}

// Admin page router
function fmyar_forms_page()
{
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';

    if ($action === 'edit') {
        include FORMYAR_PATH . 'admin/views/form-edit.php';
    } else {
        include FORMYAR_PATH . 'admin/views/forms-list.php';
    }
}

/**
 * Render the Welcome page.
 *
 * @since 1.0.0
 * @return void
 */
function fmyar_welcome_page()
{
    include FORMYAR_PATH . 'admin/views/welcome-page.php';
}

/**
 * Render the About page.
 *
 * @since 1.0.0
 * @return void
 */
function fmyar_about_page()
{
    include FORMYAR_PATH . 'admin/views/about-page.php';
}

// Get edit URL for form
function fmyar_get_form_edit_url($form)
{
    return admin_url('admin.php?page=fmyar_forms&action=edit&id=' . absint($form->get_id()));
}

// Handle file upload for form builder
function fmyar_handle_file_upload($file, $field_config)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed_types = isset($field_config['config']['allowed_types'])
        ? explode(',', str_replace(' ', '', $field_config['config']['allowed_types']))
        : ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

    $max_size = isset($field_config['config']['max_size'])
        ? intval($field_config['config']['max_size']) * 1024 * 1024
        : 5 * 1024 * 1024;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_types) || $file['size'] > $max_size) {
        return false;
    }

    add_filter('upload_dir', 'fmyar_form_upload_dir');
    add_filter('wp_handle_upload_prefilter', 'fmyar_modify_uploaded_file_names');

    $uploaded_file = wp_handle_upload($file, ['test_form' => false]);

    remove_filter('upload_dir', 'fmyar_form_upload_dir');
    remove_filter('wp_handle_upload_prefilter', 'fmyar_modify_uploaded_file_names');

    if (!$uploaded_file || isset($uploaded_file['error'])) {
        return false;
    }

    return $uploaded_file['url'];
}

// Get available field types
function fmyar_get_field_types()
{
    return [
        'text' => [
            'class'       => 'FormYar_Field_Text',
            'label'       => __('Text Input', 'formyar'),
            'icon'        => 'dashicons-editor-textcolor',
            'description' => __('Single line text input', 'formyar'),
            'is_pro'      => false,
        ],
        'textarea' => [
            'class'       => 'FormYar_Field_Textarea',
            'label'       => __('Text Area', 'formyar'),
            'icon'        => 'dashicons-editor-paragraph',
            'description' => __('Multi-line text input', 'formyar'),
            'is_pro'      => false,
        ],
        'number' => [
            'class'       => 'FormYar_Field_Number',
            'label'       => __('Number', 'formyar'),
            'icon'        => 'dashicons-calculator',
            'description' => __('Numeric input with min/max', 'formyar'),
            'is_pro'      => false,
        ],
        'email' => [
            'class'       => 'FormYar_Field_Email',
            'label'       => __('Email', 'formyar'),
            'icon'        => 'dashicons-email',
            'description' => __('Email address input', 'formyar'),
            'is_pro'      => false,
        ],
        'date' => [
            'class'       => 'FormYar_Field_Date',
            'label'       => __('Date', 'formyar'),
            'icon'        => 'dashicons-calendar',
            'description' => __('Date picker input', 'formyar'),
            'is_pro'      => false,
        ],
        'date_jalali' => [
            'class'       => 'FormYar_Field_Date_Jalali',
            'label'       => __('Jalali Date', 'formyar'),
            'icon'        => 'dashicons-calendar-alt',
            'description' => __('Jalali date picker', 'formyar'),
            'is_pro'      => false,
        ],
        'select' => [
            'class'       => 'FormYar_Field_Select',
            'label'       => __('Dropdown', 'formyar'),
            'icon'        => 'dashicons-arrow-down-alt2',
            'description' => __('Dropdown select list', 'formyar'),
            'is_pro'      => false,
        ],
        'checkbox' => [
            'class'       => 'FormYar_Field_Checkbox',
            'label'       => __('Checkbox', 'formyar'),
            'icon'        => 'dashicons-yes-alt',
            'description' => __('Multiple choice checkboxes', 'formyar'),
            'is_pro'      => false,
        ],
        'radio' => [
            'class'       => 'FormYar_Field_Radio',
            'label'       => __('Radio', 'formyar'),
            'icon'        => 'dashicons-marker',
            'description' => __('Single choice radio buttons', 'formyar'),
            'is_pro'      => false,
        ],
        'file' => [
            'class'       => 'FormYar_Field_File',
            'label'       => __('File Upload', 'formyar'),
            'icon'        => 'dashicons-upload',
            'description' => __('File upload field', 'formyar'),
            'is_pro'      => false,
        ],
    ];

    /**
     * Filter the available field types.
     *
     * Allows Pro to add new field types.
     *
     * @since 1.1.0
     * @param array $field_types List of field types.
     */
    return apply_filters('fmyar_field_types', $field_types);
}

// Create field instance from type
function fmyar_create_field($type, $id = null, $config = [], $order = 0)
{
    $field_types = fmyar_get_field_types();

    if (!isset($field_types[$type]) || !class_exists($field_types[$type]['class'])) {
        return false;
    }

    $class = $field_types[$type]['class'];
    return new $class($id, $config, $order);
}

// Parse form structure JSON and return field objects
function fmyar_parse_form_structure($form_json)
{
    $fields = [];

    if (empty($form_json)) {
        return $fields;
    }

    $data = is_string($form_json) ? json_decode($form_json, true) : $form_json;

    if (!isset($data['fields']) || !is_array($data['fields'])) {
        return $fields;
    }

    foreach ($data['fields'] as $field_data) {
        if (!isset($field_data['type'])) {
            continue;
        }

        $field = fmyar_create_field(
            $field_data['type'],
            $field_data['id'] ?? null,
            $field_data['config'] ?? [],
            $field_data['order'] ?? 0
        );

        if ($field) {
            $fields[] = $field;
        }
    }

    usort($fields, function ($a, $b) {
        return $a->get_order() - $b->get_order();
    });

    return $fields;
}

// Save form structure to database
function fmyar_save_form_structure($table, $id_column, $id_value, $fields)
{
    global $wpdb;

    $form_data = [
        'version' => '1.0',
        'fields'  => array_map(function ($field) {
            return $field->to_array();
        }, $fields)
    ];

    return $wpdb->update(
        $table,
        ['form' => wp_json_encode($form_data, JSON_UNESCAPED_UNICODE), 'updated_at' => current_time('mysql')],
        [$id_column => $id_value],
        ['%s', '%s'],
        ['%d']
    );
}

// Render form frontend from JSON structure
function fmyar_render_form_frontend($form_json)
{
    if (empty($form_json)) {
        return '';
    }

    $form_data = is_string($form_json) ? json_decode($form_json, true) : $form_json;

    if (!isset($form_data['fields']) || empty($form_data['fields'])) {
        return '';
    }

    $html = '<div class="fmyar-fields">';

    foreach ($form_data['fields'] as $index => $field_data) {
        $field = fmyar_create_field(
            $field_data['type'],
            $field_data['id'] ?? null,
            $field_data['config'] ?? [],
            $field_data['order'] ?? 0
        );

        if (!$field) continue;

        $number      = $index + 1;
        $is_required = $field->get_config('required');
        $is_required = ($is_required === true || $is_required === '1');
        $field_type  = $field->get_type();
        $label       = $field->get_config('label') ?: __('Untitled', 'formyar');
        $description = $field->get_config('description');

        $html .= '<div class="fmyar-field-card" data-field-index="' . esc_attr($index) . '">';

        // Field Header
        $html .= '<div class="fmyar-field-card-header">';
        $html .= '<span class="fmyar-field-number">' . esc_html($number) . '</span>';
        $html .= '<div class="fmyar-field-card-label-wrap">';
        $html .= '<label class="fmyar-field-card-label" for="fmyar-field-' . esc_attr($field->get_id()) . '">';
        $html .= esc_html($label);
        if ($is_required) {
            $html .= ' <span class="fmyar-required">*</span>';
        }
        $html .= '</label>';
        $html .= '</div>';
        if ($is_required) {
            $html .= '<span class="fmyar-required-badge">' . esc_html__('Required', 'formyar') . '</span>';
        }
        $html .= '</div>';

        // Field Body
        $html .= '<div class="fmyar-field-card-body">';
        $html .= $field->render_frontend();
        $html .= '</div>';

        // Field Footer
        if ($description) {
            $html .= '<div class="fmyar-field-card-footer">';
            $html .= '<span class="fmyar-field-hint-icon">ℹ️</span>';
            $html .= '<span class="fmyar-field-hint-text">' . esc_html($description) . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';
    }

    $html .= '</div>';
    return $html;
}

// Get client IP address
function fmyar_get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
        return sanitize_text_field(trim($ips[0]));
    }

    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }

    return '';
}

// Get CSS class for file icon
function fmyar_get_file_icon_class($ext)
{
    $icons = [
        'pdf'  => 'fmyar-file-icon-pdf',
        'doc'  => 'fmyar-file-icon-doc',
        'docx' => 'fmyar-file-icon-doc',
        'xls'  => 'fmyar-file-icon-spreadsheet',
        'xlsx' => 'fmyar-file-icon-spreadsheet',
        'zip'  => 'fmyar-file-icon-archive',
        'rar'  => 'fmyar-file-icon-archive',
        'mp4'  => 'fmyar-file-icon-video',
        'mp3'  => 'fmyar-file-icon-video',
    ];

    return isset($icons[$ext]) ? $icons[$ext] : 'fmyar-file-icon-default';
}

// Get SVG icon for file type
function fmyar_get_file_icon_svg($ext)
{
    $icons = [
        'pdf'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-3 14H9v-2h2v2zm0-3H9v-5h2v5zm4 3h-2v-2h2v2zm0-3h-2v-5h2v5z"/></svg>',
        'doc'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><text x="7" y="20" font-size="10" font-weight="bold" fill="currentColor">W</text></svg>',
        'docx' => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><text x="7" y="20" font-size="10" font-weight="bold" fill="currentColor">W</text></svg>',
        'xls'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><text x="7" y="20" font-size="10" font-weight="bold" fill="currentColor">X</text></svg>',
        'xlsx' => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><text x="7" y="20" font-size="10" font-weight="bold" fill="currentColor">X</text></svg>',
        'zip'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm0 5v3H8V7h6zm-6 5h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>',
        'rar'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm0 5v3H8V7h6zm-6 5h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>',
        '7z'   => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm0 5v3H8V7h6zm-6 5h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>',
        'mp4'  => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>',
        'webm' => '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>',
    ];

    if (isset($icons[$ext])) {
        return $icons[$ext];
    }

    return '<svg class="fmyar-icon-md" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/></svg>';
}

// Build HTML email from form submission
function fmyar_build_email_message($form, $answers, $user_id)
{
    $fields = $form->get_fields();
    $user   = $user_id ? get_userdata($user_id) : null;

    ob_start();
    include FORMYAR_PATH . 'includes/templates/email-notification.php';
    return ob_get_clean();
}

// Ajax: Create New Form
add_action('wp_ajax_fmyar_create_form', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_admin_modal_nonce')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    $title       = sanitize_text_field(wp_unslash($_POST['title'] ?? ''));
    $type        = sanitize_text_field(wp_unslash($_POST['type'] ?? 'custom'));
    $description = sanitize_textarea_field(wp_unslash($_POST['description'] ?? ''));

    if (empty($title)) {
        wp_send_json_error(['msg' => __('Form title is required.', 'formyar')]);
    }

    $valid_types = ['poll', 'contact', 'registration', 'custom'];
    if (!in_array($type, $valid_types)) {
        $type = 'custom';
    }

    $form = FormYar_Form::create([
        'title'       => $title,
        'type'        => $type,
        'description' => $description,
        'status'      => 'disabled',
    ]);

    if ($form) {
        wp_send_json_success([
            'msg'      => __('Form created successfully.', 'formyar'),
            'redirect' => fmyar_get_form_edit_url($form),
            'form_id'  => $form->get_id(),
        ]);
    }

    wp_send_json_error(['msg' => __('Failed to create form.', 'formyar')]);
});

// Ajax: Delete Form
add_action('wp_ajax_fmyar_delete_form', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_admin_modal_nonce')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    $form_id = intval($_POST['form_id'] ?? 0);
    if (!$form_id) {
        wp_send_json_error(['msg' => __('Form ID is required.', 'formyar')]);
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists()) {
        wp_send_json_error(['msg' => __('Form not found.', 'formyar')]);
    }

    if ($form->delete()) {
        wp_send_json_success(['msg' => __('Form deleted successfully.', 'formyar')]);
    }

    wp_send_json_error(['msg' => __('Failed to delete form.', 'formyar')]);
});

// Ajax: Toggle Form Status
add_action('wp_ajax_fmyar_toggle_form_status', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_admin_modal_nonce')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    $form_id = intval($_POST['form_id'] ?? 0);
    $status  = sanitize_text_field(wp_unslash($_POST['status'] ?? ''));

    if (!$form_id || !in_array($status, ['enable', 'disabled'])) {
        wp_send_json_error(['msg' => __('Invalid parameters.', 'formyar')]);
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists()) {
        wp_send_json_error(['msg' => __('Form not found.', 'formyar')]);
    }

    if ($form->update(['status' => $status])) {
        $status_text = $status === 'enable' ? __('activated', 'formyar') : __('deactivated', 'formyar');
        /* translators: %s: form status (activated/deactivated) */
        wp_send_json_success(['msg' => sprintf(__('Form %s successfully.', 'formyar'), $status_text)]);
    }

    wp_send_json_error(['msg' => __('Failed to update form status.', 'formyar')]);
});

// Ajax: Save Form (Edit Page)
add_action('wp_ajax_fmyar_admin_edit_form', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_edit_form_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    $form_id     = absint($_POST['form_id'] ?? 0);
    $title       = sanitize_text_field(wp_unslash($_POST['title'] ?? ''));
    $description = sanitize_textarea_field(wp_unslash($_POST['description'] ?? ''));
    $status      = sanitize_text_field(wp_unslash($_POST['status'] ?? 'disabled'));

    if (!in_array($status, ['enable', 'disabled'], true)) {
        $status = 'disabled';
    }

    $form_structure = '';
    if (isset($_POST['form_structure'])) {
        $raw_structure = wp_unslash($_POST['form_structure']);
        $decoded = json_decode($raw_structure, true);
        if (is_array($decoded)) {
            $decoded = fmyar_sanitize_deep($decoded);
            $form_structure = wp_json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }
    }

    $settings_json = '{}';
    if (isset($_POST['settings'])) {
        $raw_settings = wp_unslash($_POST['settings']);
        $decoded = json_decode($raw_settings, true);
        if (is_array($decoded)) {
            $decoded = fmyar_sanitize_deep($decoded);
            $settings_json = wp_json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }
    }

    if (!$form_id || empty($title)) {
        wp_send_json_error(['msg' => __('Form ID and title are required.', 'formyar')]);
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists()) {
        wp_send_json_error(['msg' => __('Form not found.', 'formyar')]);
    }

    $result = $form->update([
        'title'       => $title,
        'description' => $description,
        'status'      => $status,
        'form'        => $form_structure,
        'settings'    => $settings_json,
        'updated_at'  => current_time('mysql'),
    ]);

    if ($result !== false) {
        wp_send_json_success(['msg' => __('Form updated successfully.', 'formyar')]);
    }

    wp_send_json_error(['msg' => __('Failed to update form.', 'formyar')]);
});


/**
 * Recursively sanitize a nested array based on key context.
 *
 * Used to sanitize complex JSON structures such as form structure,
 * form settings, and field data. The sanitization method is selected
 * based on the key name to preserve data integrity while preventing
 * XSS and injection attacks.
 *
 * @since 1.0.0
 *
 * @param mixed  $data       The data to sanitize.
 * @param string $parent_key Optional. The parent key name used as context.
 *                           Default empty string.
 * @return mixed The sanitized data.
 */
function fmyar_sanitize_deep($data, $parent_key = '')
{
    // Handle scalar values based on the parent key context.
    if (!is_array($data)) {
        // Sanitize IDs and keys.
        if (in_array($parent_key, ['id', 'field_id', 'form_id', 'entry_id'], true)) {
            return sanitize_key($data);
        }

        // Sanitize email addresses.
        if (strpos($parent_key, 'email') !== false) {
            return sanitize_email($data);
        }

        // Sanitize URLs.
        if (strpos($parent_key, 'url') !== false) {
            return esc_url_raw($data);
        }

        // Sanitize boolean values.
        if (in_array($parent_key, ['required', 'multiple', 'enabled', 'user_reply_enabled'], true)) {
            return ($data === true || $data === '1' || $data === 1 || $data === 'true');
        }

        // Sanitize CSS class names.
        if (strpos($parent_key, 'class') !== false) {
            return sanitize_html_class($data);
        }

        // Sanitize integer values.
        if (in_array($parent_key, ['order', 'max_files', 'rows', 'minlength', 'maxlength', 'max_entries', 'time_limit', 'max_size'], true)) {
            return is_numeric($data) ? intval($data) : 0;
        }

        // Sanitize float values.
        if (in_array($parent_key, ['min', 'max', 'step'], true)) {
            return is_numeric($data) ? floatval($data) : '';
        }

        // Sanitize email subject and message fields.
        if (in_array($parent_key, ['subject', 'user_reply_subject', 'user_reply_message', 'message'], true)) {
            return sanitize_textarea_field($data);
        }

        // Sanitize fields that may contain safe HTML.
        if (in_array($parent_key, ['description', 'label', 'placeholder', 'button_text', 'default_value', 'value'], true)) {
            return wp_kses_post($data);
        }

        // Sanitize plain text option fields.
        if ($parent_key === 'options' || $parent_key === 'allowed_types') {
            return sanitize_text_field($data);
        }

        // Default fallback sanitization.
        return is_string($data) ? sanitize_text_field($data) : $data;
    }

    // Recursively sanitize array values.
    $sanitized = [];
    foreach ($data as $key => $value) {
        // Preserve original keys to avoid breaking field references.
        $sanitized[$key] = fmyar_sanitize_deep($value, is_string($key) ? $key : '');
    }

    return $sanitized;
}

// Ajax: Get Form Entries
add_action('wp_ajax_fmyar_get_form_entries', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    $form_id  = absint($_POST['form_id'] ?? 0);
    $page     = max(1, absint($_POST['page'] ?? 1));
    $per_page = 20;

    $form_id  = intval($_POST['form_id'] ?? 0);
    $page     = max(1, intval($_POST['page'] ?? 1));
    $per_page = 20;

    $form = new FormYar_Form($form_id);
    if (!$form->exists()) {
        wp_send_json_error(['msg' => 'Form not found']);
    }

    $entries = $form->get_entries($per_page, $page);
    $total   = $form->get_entry_count();

    if (empty($entries)) {
        wp_send_json_success(['html' => '', 'pagination' => '', 'total' => 0]);
    }

    ob_start();
    $offset = ($page - 1) * $per_page;
    foreach ($entries as $index => $entry):
        $num  = $offset + $index + 1;
        $user = $entry->display_name ?: __('Guest', 'formyar');
        $date = mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $entry->created_at);
?>
        <tr class="fmyar-table-row">
            <td class="fmyar-table-td"><?php echo esc_html($num); ?></td>
            <td class="fmyar-table-td"><?php echo esc_html($user); ?></td>
            <td class="fmyar-table-td fmyar-table-date"><?php echo esc_html($date); ?></td>
            <td class="fmyar-table-td">
                <div class="fmyar-table-actions">
                    <button type="button" class="fmyar-view-form-entry fmyar-action-btn fmyar-action-btn-default" data-entry-id="<?php echo esc_attr($entry->id); ?>">
                        <svg class="fmyar-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    <button type="button" class="fmyar-delete-form-entry fmyar-action-btn fmyar-action-btn-danger" data-entry-id="<?php echo esc_attr($entry->id); ?>">
                        <svg class="fmyar-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    <?php
    endforeach;
    $html = ob_get_clean();

    $total_pages = ceil($total / $per_page);
    $pagination  = '';
    if ($total_pages > 1) {
        for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i == $page) ? 'fmyar-pagination-num-active' : '';
            $pagination  .= '<a href="#" class="fmyar-form-entry-page fmyar-pagination-num ' . esc_attr($active_class) . '" data-page="' . esc_attr($i) . '">' . esc_html($i) . '</a> ';
        }
    }

    wp_send_json_success(['html' => $html, 'pagination' => $pagination, 'total' => $total]);
});

add_action('wp_ajax_fmyar_get_form_entry_details', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    $entry_id = absint($_POST['entry_id'] ?? 0);

    global $wpdb;
    // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    $entry = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT e.*, u.display_name FROM {$wpdb->prefix}fmyar_forms_entries e LEFT JOIN {$wpdb->users} u ON e.user_id = u.ID WHERE e.id = %d",
            $entry_id
        )
    );

    if (!$entry) {
        wp_send_json_error(['msg' => 'Entry not found']);
    }

    $form        = new FormYar_Form($entry->form_id);
    $fields      = $form->get_fields();
    $answers     = json_decode($entry->entry_data, true) ?: [];
    $image_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    ob_start();
    ?>
    <div class="fmyar-space-y-4">
        <div class="fmyar-card">
            <div class="fmyar-order-info-grid">
                <div class="fmyar-order-info-item">
                    <span class="fmyar-order-info-label"><?php esc_html_e('User', 'formyar'); ?></span>
                    <span class="fmyar-order-info-value"><?php echo esc_html($entry->display_name ?: __('Guest', 'formyar')); ?></span>
                </div>
                <div class="fmyar-order-info-item">
                    <span class="fmyar-order-info-label"><?php esc_html_e('Date', 'formyar'); ?></span>
                    <span class="fmyar-order-info-value"><?php echo esc_html(mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $entry->created_at)); ?></span>
                </div>
            </div>
        </div>
        <?php foreach ($fields as $index => $field):
            $field_id = $field->get_id();
            $value    = $answers[$field_id] ?? '';
            if ($value === '' || $value === null || (is_array($value) && empty($value))) continue;
            $field_type  = $field->get_type();
            $field_label = $field->get_config('label') ?: __('Untitled', 'formyar');
        ?>
            <div class="fmyar-file-stat-card">
                <p class="fmyar-file-stat-label fmyar-mb-2"><?php echo esc_html(($index + 1) . '. ' . $field_label); ?></p>
                <?php if ($field_type === 'file'): ?>
                    <div class="fmyar-space-y-2">
                        <?php
                        $files = [];
                        if (is_array($value)) {
                            foreach ($value as $item) {
                                if (is_string($item) && !empty($item)) {
                                    $decoded = json_decode(wp_unslash($item), true);
                                    if (is_array($decoded)) {
                                        $files = array_merge($files, $decoded);
                                    } else {
                                        $files[] = $item;
                                    }
                                }
                            }
                        } elseif (is_string($value) && !empty($value)) {
                            $decoded = json_decode(wp_unslash($value), true);
                            $files = is_array($decoded) ? $decoded : [$value];
                        }
                        $files = array_filter($files);

                        foreach ($files as $file_url):
                            if (empty($file_url) || !is_string($file_url)) continue;
                            $file_name = basename($file_url);
                            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                            $is_image  = in_array($file_ext, $image_types);
                            $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $file_url);

                            $download_url = add_query_arg([
                                'fmyar_file'  => base64_encode($file_path),
                                'fmyar_token' => wp_hash($file_path . 'fmyar_download'),
                            ], home_url('/'));
                        ?>
                            <div class="fmyar-file-preview-row">
                                <?php if ($is_image): ?>
                                    <a href="<?php echo esc_url($download_url); ?>" target="_blank" class="fmyar-file-preview-thumb">
                                        <img src="<?php echo esc_url($download_url); ?>" alt="<?php echo esc_attr($file_name); ?>" class="fmyar-file-preview-img">
                                    </a>
                                <?php else: ?>
                                    <div class="fmyar-file-preview-icon <?php echo esc_attr(fmyar_get_file_icon_class($file_ext)); ?>">
                                        <?php echo fmyar_get_file_icon_svg($file_ext); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <div class="fmyar-flex-1 fmyar-min-w-0">
                                    <p class="fmyar-file-preview-name" dir="ltr"><?php echo esc_html($file_name); ?></p>
                                </div>
                                <a href="<?php echo esc_url($download_url); ?>" class="fmyar-btn fmyar-btn-primary fmyar-btn-xs" target="_blank">
                                    <?php esc_html_e('Download', 'formyar'); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($files)): ?>
                            <p class="fmyar-text-muted fmyar-text-sm fmyar-italic"><?php esc_html_e('File data is empty or corrupted.', 'formyar'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php elseif ($field_type === 'textarea'): ?>
                    <div class="fmyar-file-stat-card"><?php echo nl2br(esc_html($value)); ?></div>
                <?php elseif ($field_type === 'checkbox' && is_array($value)): ?>
                    <div class="fmyar-flex fmyar-flex-wrap fmyar-gap-1">
                        <?php foreach ($value as $val): ?>
                            <?php if (!empty($val)): ?>
                                <span class="fmyar-badge fmyar-badge-success fmyar-badge-xs"><?php echo esc_html($val); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="fmyar-text-sm"><?php echo is_array($value) ? esc_html(implode(', ', $value)) : nl2br(esc_html($value)); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (empty($fields)): ?>
            <p class="fmyar-text-gray-500 fmyar-text-center fmyar-py-4"><?php esc_html_e('No fields found.', 'formyar'); ?></p>
        <?php endif; ?>
    </div>
<?php
    wp_send_json_success(['html' => ob_get_clean()]);
});

// Ajax: Delete Entry
add_action('wp_ajax_fmyar_delete_form_entry', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    $entry_id = absint($_POST['entry_id'] ?? 0);
    if (!$entry_id) {
        wp_send_json_error(['msg' => __('Entry ID required.', 'formyar')]);
    }

    global $wpdb;
    $result = $wpdb->delete(FORMYAR_TABLE_FORMS_ENTRIES, ['id' => $entry_id]);

    if ($result) {
        wp_send_json_success(['msg' => 'Entry deleted']);
    } else {
        wp_send_json_error(['msg' => 'Failed to delete entry']);
    }
});

// Ajax: Export Entries
add_action('wp_ajax_fmyar_export_form_entries', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    $form_id = intval($_REQUEST['form_id'] ?? 0);
    $nonce   = sanitize_text_field(wp_unslash($_REQUEST['nonce'] ?? ''));

    if (!$form_id || !wp_verify_nonce($nonce, 'fmyar_admin_modal_nonce')) {
        wp_die('Security check failed');
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists()) {
        wp_die('Form not found');
    }

    $entries = $form->get_entries(9999, 1);
    $fields  = $form->get_fields();

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="form-entries-' . $form_id . '.csv"');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    $headers = ['#', 'User', 'Date'];
    foreach ($fields as $f) {
        $headers[] = $f->get_config('label') ?: 'Untitled';
    }
    fputcsv($output, $headers);

    foreach ($entries as $i => $entry) {
        $answers = json_decode($entry->entry_data, true) ?: [];
        $row = [$i + 1, $entry->display_name ?: 'Guest', $entry->created_at];
        foreach ($fields as $f) {
            $val = $answers[$f->get_id()] ?? '';
            $row[] = is_array($val) ? implode(' | ', array_map('basename', $val)) : $val;
        }
        fputcsv($output, $row);
    }

    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
    fclose($output);
    exit;
});

// Ajax: Submit Form Entry (Frontend)
add_action('wp_ajax_nopriv_fmyar_submit_form', 'fmyar_submit_form_handler');
add_action('wp_ajax_fmyar_submit_form', 'fmyar_submit_form_handler');

function fmyar_submit_form_handler()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_form_submit')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    // reCAPTCHA verification
    if (get_option('fmyar_recaptcha_enabled', '0') === '1') {
        $token  = isset($_POST['recaptcha_token']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_token'])) : '';
        $secret = get_option('fmyar_recaptcha_secret_key', '');

        if (empty($token)) {
            wp_send_json_error(['msg' => __('Security check failed. Please refresh the page and try again.', 'formyar')]);
        }

        if (!empty($secret)) {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret'   => $secret,
                    'response' => $token,
                ],
            ]);

            $result = json_decode(wp_remote_retrieve_body($response), true);

            if (empty($result['success']) || ($result['score'] ?? 0) < 0.5) {
                wp_send_json_error(['msg' => __('Spam detected. Please try again.', 'formyar')]);
            }
        }
    }

    $form_id = intval($_POST['form_id'] ?? 0);
    if (!$form_id) {
        wp_send_json_error(['msg' => __('Form ID is required.', 'formyar')]);
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists() || !$form->is_active()) {
        wp_send_json_error(['msg' => __('Form not found or inactive.', 'formyar')]);
    }

    $user_id    = get_current_user_id();
    $validation = $form->validate_submission($user_id);
    if (!$validation['valid']) {
        wp_send_json_error(['msg' => $validation['msg']]);
    }

    $fields = $form->get_fields();
    if (empty($fields)) {
        wp_send_json_error(['msg' => __('This form has no fields.', 'formyar')]);
    }

    $answers   = [];
    $errors    = [];
    $form_data = isset($_POST['fmyar_form_data']) ? wp_unslash($_POST['fmyar_form_data']) : [];

    foreach ($fields as $field) {
        $field_id     = $field->get_id();
        $field_type   = $field->get_type();
        $is_required  = $field->get_config('required');
        $is_required  = ($is_required === true || $is_required === '1');
        $field_label  = $field->get_config('label') ?: $field_id;

        $value = $form_data[$field_id] ?? '';

        if ($field_type === 'file') {
            $file_data = isset($_POST['fmyar_files']) ? wp_unslash($_POST['fmyar_files']) : [];
            $raw_value = $file_data[$field_id] ?? '';
            $files     = [];

            if (is_string($raw_value) && !empty($raw_value)) {
                $decoded = json_decode($raw_value, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $url) {
                        if (!is_string($url)) continue;
                        $clean_url = esc_url_raw($url);

                        $upload_dir = wp_upload_dir();
                        $base_url   = $upload_dir['baseurl'] . '/formyar-uploads/';
                        if (strpos($clean_url, $base_url) === 0) {
                            $files[] = $clean_url;
                        }
                    }
                }
            }

            if ($is_required && empty($files)) {
                /* translators: %s: field label */
                $errors[] = sprintf(__('%s is required.', 'formyar'), $field_label);
            }

            $answers[$field_id] = $files;
            continue;
        }

        $value = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field(trim($value));

        if ($is_required && empty($value)) {
            /* translators: %s: field label */
            $errors[] = sprintf(__('%s is required.', 'formyar'), $field_label);
        }

        $answers[$field_id] = $value;
    }

    if (!empty($errors)) {
        wp_send_json_error(['msg' => implode('<br>', $errors)]);
    }

    // Before saving entry.
    $validation_errors = [];

    /**
     * Filter: Validate form submission.
     *
     * Allows Pro to add custom validation (e.g., payment verification).
     *
     * @since 1.1.0
     * @param array            $validation_errors Array of error messages.
     * @param FormYar_Form     $form              Current form object.
     * @param array            $answers           Submitted answers.
     * @param int              $user_id           Current user ID.
     */
    $validation_errors = apply_filters('fmyar_form_validation', $validation_errors, $form, $answers, $user_id);

    if (!empty($validation_errors)) {
        wp_send_json_error(['msg' => implode('<br>', $validation_errors)]);
    }

    // Save entry.
    $entry_id = $form->save_entry($answers, $user_id);

    if ($entry_id) {
        $form->track_submission($user_id);
        do_action('fmyar_form_submitted', $form_id, $entry_id, $answers, $user_id);
        wp_send_json_success(['msg' => __('Your response has been submitted successfully.', 'formyar')]);
    }

    wp_send_json_error(['msg' => __('Failed to save your response. Please try again.', 'formyar')]);
}

// Ajax: Upload File for Form Builder
add_action('wp_ajax_fmyar_upload_form_file', 'fmyar_handle_form_file_upload');
add_action('wp_ajax_nopriv_fmyar_upload_form_file', 'fmyar_handle_form_file_upload');

function fmyar_handle_form_file_upload()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_form_submit')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    if (empty($_FILES['file'])) {
        wp_send_json_error(['msg' => __('No file uploaded.', 'formyar')]);
    }

    $file     = $_FILES['file'];
    $form_id  = intval($_POST['form_id'] ?? 0);
    $field_id = sanitize_text_field(wp_unslash($_POST['field_id'] ?? ''));

    if (!$form_id || !$field_id) {
        wp_send_json_error(['msg' => __('Invalid request.', 'formyar')]);
    }

    $form = new FormYar_Form($form_id);
    if (!$form->exists() || !$form->is_active()) {
        wp_send_json_error(['msg' => __('Form not found or inactive.', 'formyar')]);
    }

    $fields       = $form->get_fields();
    $field_config = null;
    foreach ($fields as $f) {
        if ($f->get_id() === $field_id) {
            $field_config = [
                'id'     => $f->get_id(),
                'type'   => $f->get_type(),
                'config' => $f->to_array()['config']
            ];
            break;
        }
    }

    if (!$field_config || $field_config['type'] !== 'file') {
        wp_send_json_error(['msg' => __('Invalid field.', 'formyar')]);
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(['msg' => __('Upload failed. Error code: ', 'formyar') . $file['error']]);
    }

    $uploaded_url = fmyar_handle_file_upload($file, $field_config);

    if ($uploaded_url) {
        wp_send_json_success([
            'url'  => $uploaded_url,
            'name' => basename($uploaded_url),
            'msg'  => __('File uploaded successfully.', 'formyar'),
        ]);
    }

    wp_send_json_error(['msg' => __('Failed to upload file. Check file type and size.', 'formyar')]);
}

// Hook: Form Submitted - Send Notifications
add_action('fmyar_form_submitted', function ($form_id, $entry_id, $answers, $user_id) {
    $form          = new FormYar_Form($form_id);
    $settings      = $form->get_settings_array();
    $notifications = $settings['notifications'] ?? [];

    if (!empty($notifications['enabled']) && $notifications['enabled'] === '1') {
        $admin_email = !empty($notifications['admin_email']) ? $notifications['admin_email'] : get_option('admin_email');
        /* translators: %s: form title */
        $subject     = $notifications['subject'] ?? sprintf(__('New Form Submission: %s', 'formyar'), $form->get_title());
        $message     = fmyar_build_email_message($form, $answers, $user_id);
        $headers     = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($admin_email, $subject, $message, $headers);
    }

    if (!empty($notifications['user_reply_enabled']) && $notifications['user_reply_enabled'] === '1') {
        $user_email = '';

        if ($user_id > 0) {
            $user = get_userdata($user_id);
            if ($user && !empty($user->user_email)) {
                $user_email = $user->user_email;
            }
        }

        if (empty($user_email)) {
            $email_field_id = $notifications['user_email_field'] ?? '';
            if (!empty($email_field_id) && isset($answers[$email_field_id])) {
                $user_email = is_array($answers[$email_field_id]) ? $answers[$email_field_id][0] : $answers[$email_field_id];
            }
        }

        if (empty($user_email)) {
            foreach ($form->get_fields() as $field) {
                if ($field->get_type() === 'email' && isset($answers[$field->get_id()]) && !empty($answers[$field->get_id()])) {
                    $val = $answers[$field->get_id()];
                    $user_email = is_array($val) ? $val[0] : $val;
                    break;
                }
            }
        }

        if (!empty($user_email) && is_email($user_email)) {
            $reply_subject = $notifications['user_reply_subject'] ?? __('Thank you for your submission', 'formyar');
            $reply_message = $notifications['user_reply_message'] ?? __('We have received your submission. Thank you!', 'formyar');

            $email_body = '<!DOCTYPE html><html dir="ltr"><head><meta charset="UTF-8"></head><body style="font-family:Tahoma,sans-serif;background:#f9fafb;padding:20px"><div style="max-width:500px;margin:0 auto;background:#fff;border-radius:12px;padding:24px;text-align:center"><div style="font-size:40px;margin-bottom:16px">&#10004;</div><h2 style="color:#111827;margin:0 0 8px">' . esc_html($reply_subject) . '</h2><p style="color:#6b7280;font-size:14px;line-height:1.6">' . nl2br(esc_html($reply_message)) . '</p></div></body></html>';

            wp_mail($user_email, $reply_subject, $email_body, ['Content-Type: text/html; charset=UTF-8']);
        }
    }
}, 10, 4);

// Ajax: Load Field Types Modal Content
add_action('wp_ajax_fmyar_get_field_types_modal', function () {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    ob_start();
    include FORMYAR_PATH . 'admin/views/modals/modal-field-types.php';
    wp_send_json_success(['html' => ob_get_clean()]);
});

// Ajax: Get Field Settings HTML
add_action('wp_ajax_fmyar_get_field_settings', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_admin_modal_nonce')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    $field_type     = sanitize_text_field(wp_unslash($_POST['field_type'] ?? ''));
    $field_data_raw = isset($_POST['field_data']) ? wp_unslash($_POST['field_data']) : null;

    if (empty($field_type)) {
        wp_send_json_error(['msg' => __('Field type not specified.', 'formyar')]);
    }

    $valid_types = array_keys(fmyar_get_field_types());
    if (!in_array($field_type, $valid_types, true)) {
        wp_send_json_error(['msg' => __('Invalid field type.', 'formyar')]);
    }

    $field_data = null;
    if ($field_data_raw) {
        $decoded = is_string($field_data_raw) ? json_decode($field_data_raw, true) : $field_data_raw;
        if (is_array($decoded)) {
            $field_data = fmyar_sanitize_deep($decoded);
        }
    }

    $GLOBALS['fmyar_current_field_type'] = $field_type;
    $GLOBALS['fmyar_current_field_data'] = $field_data;

    ob_start();
    include FORMYAR_PATH . 'admin/views/modals/modal-field-settings.php';
    wp_send_json_success(['html' => ob_get_clean()]);
});

// Ajax: Delete File
add_action('wp_ajax_fmyar_delete_file', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_admin_modal_nonce')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    $file_name = sanitize_text_field(wp_unslash($_POST['file'] ?? ''));
    if (empty($file_name)) {
        wp_send_json_error(['msg' => __('Invalid file.', 'formyar')]);
    }

    $upload_dir = wp_upload_dir();
    $target_dir = $upload_dir['basedir'] . '/formyar-uploads';
    $file_path  = $target_dir . '/' . $file_name;

    if (!file_exists($file_path) || strpos(realpath($file_path), realpath($target_dir)) !== 0) {
        wp_send_json_error(['msg' => __('File not found.', 'formyar')]);
    }

    if (wp_delete_file($file_path)) {
        wp_send_json_success(['msg' => __('File deleted.', 'formyar')]);
    }

    wp_send_json_error(['msg' => __('Failed to delete file.', 'formyar')]);
});

// Ajax: Save Settings
add_action('wp_ajax_fmyar_save_settings', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')]);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fmyar_settings')) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')]);
    }

    update_option('fmyar_recaptcha_enabled', sanitize_text_field(wp_unslash($_POST['fmyar_recaptcha_enabled'] ?? '0')));
    update_option('fmyar_recaptcha_site_key', sanitize_text_field(wp_unslash($_POST['fmyar_recaptcha_site_key'] ?? '')));
    update_option('fmyar_recaptcha_secret_key', sanitize_text_field(wp_unslash($_POST['fmyar_recaptcha_secret_key'] ?? '')));
    update_option('fmyar_delete_data', sanitize_text_field(wp_unslash($_POST['fmyar_delete_data'] ?? '0')));

    wp_send_json_success(['msg' => __('Settings saved.', 'formyar')]);
});

// Modal container for admin views
function fmyar_modal_container()
{
?>
    <div id="fmyar-dynamic-modal" class="fmyar-modal-overlay fmyar-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fmyar-modal-title"
        aria-describedby="fmyar-modal-body">
        <div class="fmyar-modal-container">
            <div class="fmyar-modal-header">
                <h3 id="fmyar-modal-title" class="fmyar-modal-title"></h3>
                <button type="button"
                    class="fmyar-modal-close"
                    aria-label="<?php esc_attr_e('Close modal', 'formyar'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div id="fmyar-modal-body" class="fmyar-modal-body"></div>
            <div id="fmyar-modal-footer" class="fmyar-modal-footer fmyar-hidden"></div>
        </div>
    </div>

    <div id="fmyar-entry-details-modal" class="fmyar-modal-overlay fmyar-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fmyar-modal-title"
        aria-describedby="fmyar-modal-body">
        <div class="fmyar-modal-container fmyar-modal-lg">
            <div class="fmyar-modal-header">
                <h3 class="fmyar-modal-title"><?php esc_html_e('Entry Details', 'formyar'); ?></h3>
                <button type="button" class="fmyar-modal-close">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div id="fmyar-entry-details-body" class="fmyar-modal-body"></div>
        </div>
    </div>

    <div id="fmyar-field-settings-modal" class="fmyar-modal-overlay fmyar-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fmyar-modal-title"
        aria-describedby="fmyar-modal-body">
        <div class="fmyar-modal-container fmyar-modal-lg">
            <div class="fmyar-modal-header">
                <h3 id="fmyar-field-settings-title" class="fmyar-modal-title"><?php esc_html_e('Field Settings', 'formyar'); ?></h3>
                <button type="button" class="fmyar-modal-close">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div id="fmyar-field-settings-body" class="fmyar-modal-body"></div>
            <div id="fmyar-field-settings-footer" class="fmyar-modal-footer">
                <button type="button" class="fmyar-btn fmyar-btn-secondary fmyar-modal-close-btn"><?php esc_html_e('Cancel', 'formyar'); ?></button>
                <button type="button" id="fmyar-save-field-settings" class="fmyar-btn fmyar-btn-primary"><?php esc_html_e('Save Field', 'formyar'); ?></button>
            </div>
        </div>
    </div>

    <div id="fmyar-template-modal" class="fmyar-modal-overlay fmyar-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fmyar-modal-title"
        aria-describedby="fmyar-modal-body">
        <div class="fmyar-modal-container fmyar-modal-lg">
            <div class="fmyar-modal-header">
                <h3 class="fmyar-modal-title"><?php esc_html_e('Start from a Template', 'formyar'); ?></h3>
                <button type="button" class="fmyar-modal-close">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="fmyar-modal-body">
                <p class="fmyar-card-description fmyar-mb-4">
                    <?php esc_html_e('Select a JSON file exported from FormYar. Supports both single form and bulk export files. All imported forms will be disabled by default.', 'formyar'); ?>
                </p>

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
    </div>
    <div id="fmyar-import-modal" class="fmyar-modal-overlay fmyar-hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="fmyar-modal-title"
        aria-describedby="fmyar-modal-body">
        <div class="fmyar-modal-container fmyar-modal-lg">
            <div class="fmyar-modal-header">
                <h3 class="fmyar-modal-title"><?php esc_html_e('Import Form', 'formyar'); ?></h3>
                <button type="button" class="fmyar-modal-close">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="fmyar-modal-body">
                <p class="fmyar-card-description fmyar-mb-4">
                    <?php esc_html_e('Select a JSON file exported from FormYar. The imported form will be disabled by default — you can enable it after reviewing.', 'formyar'); ?>
                </p>

                <div class="fmyar-form-group">
                    <label class="fmyar-form-label" for="fmyar-import-file">
                        <?php esc_html_e('JSON File', 'formyar'); ?> <span class="fmyar-required">*</span>
                    </label>
                    <input type="file"
                        id="fmyar-import-file"
                        name="import_file"
                        accept=".json,application/json"
                        class="fmyar-input-full">
                    <p class="fmyar-form-hint">
                        <?php esc_html_e('Maximum file size: 1 MB.', 'formyar'); ?>
                    </p>
                </div>

                <div id="fmyar-import-status" class="fmyar-import-status fmyar-hidden"></div>
            </div>
            <div class="fmyar-modal-footer">
                <button type="button" class="fmyar-btn fmyar-btn-secondary fmyar-modal-close-btn">
                    <?php esc_html_e('Cancel', 'formyar'); ?>
                </button>
                <button type="button" id="fmyar-do-import" class="fmyar-btn fmyar-btn-primary">
                    <?php esc_html_e('Import Form', 'formyar'); ?>
                </button>
            </div>
        </div>
    </div>
<?php
}

/**
 * Render the FormYar brand header block.
 *
 * Outputs the plugin logo and title/subtitle in a consistent layout
 * across all admin pages.
 *
 * @since 1.0.0
 *
 * @param string $title    The brand title text.
 * @param string $subtitle Optional. The brand subtitle text. Default empty.
 * @return void
 */
function fmyar_render_brand($title, $subtitle = '')
{
    /**
     * Filter: Brand name.
     *
     * Allows Pro to customize the brand name (white label).
     *
     * @since 1.1.0
     * @param string $title    Brand title.
     * @param string $subtitle Brand subtitle.
     */
    $title = apply_filters('fmyar_brand_name', $title, $subtitle);

    /**
     * Filter: Brand subtitle.
     *
     * @since 1.1.0
     * @param string $subtitle Brand subtitle.
     * @param string $title    Brand title.
     */
    $subtitle = apply_filters('fmyar_brand_subtitle', $subtitle, $title);
    ?>
    <div class="fmyar-brand fmyar-brand-logo">
        <div class="fmyar-brand-icon" aria-hidden="true">
            <svg width="32" height="32" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
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
        <div>
            <h1 class="fmyar-brand-name"><?php echo esc_html($title); ?></h1>
            <?php if ($subtitle !== '') : ?>
                <p class="fmyar-brand-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php
}

// Custom upload directory for form files
add_filter('upload_dir', 'fmyar_form_upload_dir');
function fmyar_form_upload_dir($upload)
{
    if (isset($_POST['action']) && sanitize_text_field(wp_unslash($_POST['action'])) === 'fmyar_upload_form_file') {
        $upload['subdir'] = '/formyar-uploads';
        $upload['path']   = $upload['basedir'] . $upload['subdir'];
        $upload['url']    = $upload['baseurl'] . $upload['subdir'];
    }
    return $upload;
}

// Modify uploaded file names for security
add_filter('wp_handle_upload_prefilter', 'fmyar_modify_uploaded_file_names');
function fmyar_modify_uploaded_file_names($file)
{
    if (isset($_POST['action']) && sanitize_text_field(wp_unslash($_POST['action'])) === 'fmyar_upload_form_file') {
        $info = pathinfo($file['name']);
        $file['name'] = time() . '-' . sanitize_file_name($info['filename']) . '.' . ($info['extension'] ?? '');
    }
    return $file;
}

/**
 * Get the list of built-in form templates.
 *
 * Each template contains a predefined set of fields that can be used
 * as a starting point when creating a new form.
 *
 * @since 1.0.0
 *
 * @return array<string, array<string, mixed>> List of templates keyed by slug.
 */
function fmyar_get_form_templates()
{
    return [
        'contact' => [
            'title'       => __('Contact Form', 'formyar'),
            'description' => __('A basic contact form with name, email, subject, and message fields.', 'formyar'),
            'fields'      => [
                [
                    'type'   => 'text',
                    'config' => [
                        'label'       => __('Your Name', 'formyar'),
                        'placeholder' => __('Enter your name', 'formyar'),
                        'required'    => true,
                    ],
                ],
                [
                    'type'   => 'email',
                    'config' => [
                        'label'       => __('Your Email', 'formyar'),
                        'placeholder' => __('example@email.com', 'formyar'),
                        'required'    => true,
                    ],
                ],
                [
                    'type'   => 'text',
                    'config' => [
                        'label'       => __('Subject', 'formyar'),
                        'placeholder' => __('What is this about?', 'formyar'),
                        'required'    => true,
                    ],
                ],
                [
                    'type'   => 'textarea',
                    'config' => [
                        'label'       => __('Your Message', 'formyar'),
                        'placeholder' => __('Write your message here...', 'formyar'),
                        'required'    => true,
                        'rows'        => 5,
                    ],
                ],
            ],
        ],
        'survey' => [
            'title'       => __('Survey', 'formyar'),
            'description' => __('Collect user opinions with multiple choice and rating questions.', 'formyar'),
            'fields'      => [
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Your Name', 'formyar'),
                        'required' => false,
                    ],
                ],
                [
                    'type'   => 'radio',
                    'config' => [
                        'label'    => __('How would you rate our service?', 'formyar'),
                        'required' => true,
                        'options'  => [
                            ['value' => 'excellent', 'label' => __('Excellent', 'formyar')],
                            ['value' => 'good', 'label' => __('Good', 'formyar')],
                            ['value' => 'average', 'label' => __('Average', 'formyar')],
                            ['value' => 'poor', 'label' => __('Poor', 'formyar')],
                        ],
                    ],
                ],
                [
                    'type'   => 'checkbox',
                    'config' => [
                        'label'    => __('Which features do you use most?', 'formyar'),
                        'required' => false,
                        'options'  => [
                            ['value' => 'forms', 'label' => __('Form Builder', 'formyar')],
                            ['value' => 'polls', 'label' => __('Polls', 'formyar')],
                            ['value' => 'surveys', 'label' => __('Surveys', 'formyar')],
                        ],
                    ],
                ],
                [
                    'type'   => 'textarea',
                    'config' => [
                        'label'       => __('Any additional feedback?', 'formyar'),
                        'placeholder' => __('We value your opinion...', 'formyar'),
                        'required'    => false,
                        'rows'        => 4,
                    ],
                ],
            ],
        ],
        'employment' => [
            'title'       => __('Job Application', 'formyar'),
            'description' => __('Collect job applications with resume upload and contact information.', 'formyar'),
            'fields'      => [
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Full Name', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'email',
                    'config' => [
                        'label'    => __('Email', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Phone Number', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Position Applied For', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'file',
                    'config' => [
                        'label'         => __('Upload Resume (PDF)', 'formyar'),
                        'required'      => true,
                        'allowed_types' => 'pdf,doc,docx',
                        'max_size'      => 5,
                        'max_files'     => 1,
                    ],
                ],
                [
                    'type'   => 'textarea',
                    'config' => [
                        'label'    => __('Cover Letter', 'formyar'),
                        'required' => false,
                        'rows'     => 5,
                    ],
                ],
            ],
        ],
        'reservation' => [
            'title'       => __('Reservation', 'formyar'),
            'description' => __('Take reservations with date, time, and party size.', 'formyar'),
            'fields'      => [
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Full Name', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'email',
                    'config' => [
                        'label'    => __('Email', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'text',
                    'config' => [
                        'label'    => __('Phone Number', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'date_jalali',
                    'config' => [
                        'label'    => __('Reservation Date', 'formyar'),
                        'required' => true,
                    ],
                ],
                [
                    'type'   => 'number',
                    'config' => [
                        'label'    => __('Number of Guests', 'formyar'),
                        'required' => true,
                        'min'      => 1,
                        'max'      => 20,
                    ],
                ],
                [
                    'type'   => 'textarea',
                    'config' => [
                        'label'    => __('Special Requests', 'formyar'),
                        'required' => false,
                        'rows'     => 3,
                    ],
                ],
            ],
        ],
        'complete' => [
            'title'       => __('Complete Form (All Fields)', 'formyar'),
            'description' => __('Sample form with all 10 field types. Use it to explore FormYar\'s capabilities.', 'formyar'),
            'fields'      => [
                // 1. Text
                [
                    'type'   => 'text',
                    'config' => [
                        'label'       => __('Full Name', 'formyar'),
                        'placeholder' => __('Enter your full name', 'formyar'),
                        'required'    => true,
                    ],
                ],
                // 2. Email
                [
                    'type'   => 'email',
                    'config' => [
                        'label'       => __('Email Address', 'formyar'),
                        'placeholder' => __('example@email.com', 'formyar'),
                        'required'    => true,
                    ],
                ],
                // 3. Number
                [
                    'type'   => 'number',
                    'config' => [
                        'label'    => __('Age', 'formyar'),
                        'required' => true,
                        'min'      => 18,
                        'max'      => 100,
                    ],
                ],
                // 4. Date (Gregorian)
                [
                    'type'   => 'date',
                    'config' => [
                        'label'    => __('Birth Date (Gregorian)', 'formyar'),
                        'required' => true,
                    ],
                ],
                // 5. Date (Jalali)
                [
                    'type'   => 'date_jalali',
                    'config' => [
                        'label'    => __('Birth Date (Jalali)', 'formyar'),
                        'required' => true,
                    ],
                ],
                // 6. Select
                [
                    'type'   => 'select',
                    'config' => [
                        'label'    => __('Country', 'formyar'),
                        'required' => true,
                        'options'  => [
                            ['value' => 'iran', 'label' => __('Iran', 'formyar')],
                            ['value' => 'turkey', 'label' => __('Turkey', 'formyar')],
                            ['value' => 'germany', 'label' => __('Germany', 'formyar')],
                            ['value' => 'usa', 'label' => __('USA', 'formyar')],
                        ],
                    ],
                ],
                // 7. Radio
                [
                    'type'   => 'radio',
                    'config' => [
                        'label'    => __('Gender', 'formyar'),
                        'required' => true,
                        'options'  => [
                            ['value' => 'male', 'label' => __('Male', 'formyar')],
                            ['value' => 'female', 'label' => __('Female', 'formyar')],
                        ],
                    ],
                ],
                // 8. Checkbox
                [
                    'type'   => 'checkbox',
                    'config' => [
                        'label'    => __('Interests', 'formyar'),
                        'required' => false,
                        'options'  => [
                            ['value' => 'sports', 'label' => __('Sports', 'formyar')],
                            ['value' => 'music', 'label' => __('Music', 'formyar')],
                            ['value' => 'travel', 'label' => __('Travel', 'formyar')],
                            ['value' => 'books', 'label' => __('Books', 'formyar')],
                            ['value' => 'tech', 'label' => __('Technology', 'formyar')],
                        ],
                    ],
                ],
                // 9. Textarea
                [
                    'type'   => 'textarea',
                    'config' => [
                        'label'       => __('About Me', 'formyar'),
                        'placeholder' => __('Tell us about yourself...', 'formyar'),
                        'required'    => true,
                        'rows'        => 5,
                    ],
                ],
                // 10. File
                [
                    'type'   => 'file',
                    'config' => [
                        'label'         => __('Profile Picture', 'formyar'),
                        'required'      => false,
                        'allowed_types' => 'jpg,jpeg,png',
                        'max_size'      => 5,
                        'max_files'     => 1,
                    ],
                ],
            ],
        ],
    ];
}

/**
 * Ajax: Create a new form from a template.
 *
 * @since 1.0.0
 * @return void
 */
add_action('wp_ajax_fmyar_create_form_from_template', function () {
    // Verify nonce.
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    // Check permissions.
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    // Sanitize input.
    $template_key = sanitize_key(wp_unslash($_POST['template'] ?? ''));
    if (empty($template_key)) {
        wp_send_json_error(['msg' => __('Template not specified.', 'formyar')]);
    }

    // Load templates.
    $templates = fmyar_get_form_templates();
    if (!isset($templates[$template_key])) {
        wp_send_json_error(['msg' => __('Template not found.', 'formyar')]);
    }

    $template = $templates[$template_key];

    // Build form structure.
    $fields = [];
    $order  = 0;
    foreach ($template['fields'] as $field_data) {
        $field_id = 'fmyar_' . time() . '_' . wp_generate_password(6, false, false);
        $fields[] = [
            'id'     => $field_id,
            'type'   => $field_data['type'],
            'order'  => $order++,
            'config' => $field_data['config'],
        ];
    }

    $form_structure = wp_json_encode([
        'version' => '1.0',
        'fields'  => $fields,
    ], JSON_UNESCAPED_UNICODE);

    // Create the form.
    $form = FormYar_Form::create([
        'title'       => $template['title'],
        'description' => $template['description'],
        'form'        => $form_structure,
        'type'        => 'custom',
        'status'      => 'disabled',
    ]);

    if ($form) {
        wp_send_json_success([
            'msg'      => __('Form created from template successfully.', 'formyar'),
            'redirect' => fmyar_get_form_edit_url($form),
            'form_id'  => $form->get_id(),
        ]);
    }

    wp_send_json_error(['msg' => __('Failed to create form from template.', 'formyar')]);
});

/**
 * Ajax: Duplicate a form.
 *
 * Creates a copy of an existing form including its fields and settings.
 *
 * @since 1.0.0
 * @return void
 */
add_action('wp_ajax_fmyar_duplicate_form', function () {
    // Verify nonce.
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    // Check permissions.
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    // Sanitize input.
    $form_id = absint($_POST['form_id'] ?? 0);
    if (!$form_id) {
        wp_send_json_error(['msg' => __('Form ID is required.', 'formyar')]);
    }

    // Load original form.
    $original = new FormYar_Form($form_id);
    if (!$original->exists()) {
        wp_send_json_error(['msg' => __('Form not found.', 'formyar')]);
    }

    // Prepare duplicate data.
    $duplicate_title = sprintf(
        /* translators: %s: original form title */
        __('%s (Copy)', 'formyar'),
        $original->get_title()
    );

    $form_data = [
        'title'       => $duplicate_title,
        'description' => $original->get_description(),
        'form'        => $original->get_structure(),
        'type'        => $original->get_type(),
        'settings'    => $original->get_settings(),
        'status'      => 'disabled', // Always disabled for safety.
    ];

    // Create duplicate.
    $new_form = FormYar_Form::create($form_data);

    if ($new_form) {
        wp_send_json_success([
            'msg'      => __('Form duplicated successfully.', 'formyar'),
            'form_id'  => $new_form->get_id(),
            'edit_url' => fmyar_get_form_edit_url($new_form),
        ]);
    }

    wp_send_json_error(['msg' => __('Failed to duplicate form.', 'formyar')]);
});

/**
 * Ajax: Import a form from a JSON file.
 *
 * @since 1.0.0
 * @return void
 */

add_action('wp_ajax_fmyar_import_form', function () {
    // Verify nonce.
    if (!isset($_POST['nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['nonce'])),
        'fmyar_admin_modal_nonce'
    )) {
        wp_send_json_error(['msg' => __('Security check failed.', 'formyar')], 403);
    }

    // Check permissions.
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['msg' => __('Access denied.', 'formyar')], 403);
    }

    // Check file.
    if (empty($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(['msg' => __('No file uploaded or upload failed.', 'formyar')]);
    }

    $file = $_FILES['import_file'];

    // Check file size (max 1 MB).
    if ($file['size'] > 1024 * 1024) {
        wp_send_json_error(['msg' => __('File is too large. Maximum size is 1 MB.', 'formyar')]);
    }

    // Check file extension.
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'json') {
        wp_send_json_error(['msg' => __('Invalid file type. Please upload a JSON file.', 'formyar')]);
    }

    // Read file content.
    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
    $content = file_get_contents($file['tmp_name']);
    if ($content === false) {
        wp_send_json_error(['msg' => __('Could not read the file.', 'formyar')]);
    }

    // Decode JSON.
    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error(['msg' => __('Invalid JSON file.', 'formyar')]);
    }

    // Validate structure.
    if (
        !isset($data['plugin'])
        || $data['plugin'] !== 'formyar'
    ) {
        wp_send_json_error(['msg' => __('This file was not exported from FormYar.', 'formyar')]);
    }

    // Detect import type.
    $import_type = $data['type'] ?? 'single';
    $is_bulk = ($import_type === 'bulk' && isset($data['forms']) && is_array($data['forms']));

    if ($is_bulk) {
        // ─────────────────────────────────────────
        // Bulk import
        // ─────────────────────────────────────────
        $imported = [];
        $failed   = 0;

        foreach ($data['forms'] as $form_data) {
            if (empty($form_data['title']) || !isset($form_data['structure'])) {
                $failed++;
                continue;
            }

            $title       = sanitize_text_field($form_data['title']);
            $description = sanitize_textarea_field($form_data['description'] ?? '');
            $type        = sanitize_text_field($form_data['type'] ?? 'custom');

            $structure = is_array($form_data['structure']) ? $form_data['structure'] : [];
            $structure = fmyar_sanitize_deep($structure);

            $settings = is_array($form_data['settings'] ?? null) ? $form_data['settings'] : [];
            $settings = fmyar_sanitize_deep($settings);

            // Add "(Imported)" suffix.
            $title = sprintf(
                /* translators: %s: original form title */
                __('%s (Imported)', 'formyar'),
                $title
            );

            $new_form = FormYar_Form::create([
                'title'       => $title,
                'description' => $description,
                'type'        => $type,
                'status'      => 'disabled',
                'form'        => wp_json_encode($structure, JSON_UNESCAPED_UNICODE),
                'settings'    => wp_json_encode($settings, JSON_UNESCAPED_UNICODE),
            ]);

            if ($new_form) {
                $imported[] = $new_form->get_id();
            } else {
                $failed++;
            }
        }

        if (empty($imported)) {
            wp_send_json_error(['msg' => __('No forms could be imported.', 'formyar')]);
        }

        wp_send_json_success([
            'msg' => sprintf(
                /* translators: %1$d: number of imported forms, %2$d: number of failed forms */
                __('%1$d form(s) imported successfully. %2$d failed. All imported forms are disabled.', 'formyar'),
                count($imported),
                $failed
            ),
            'imported_count' => count($imported),
            'failed_count'   => $failed,
            'is_bulk'        => true,
        ]);
    }

    // ─────────────────────────────────────────
    // Single import (existing code)
    // ─────────────────────────────────────────
    if (empty($data['form']['title']) || !isset($data['form']['structure'])) {
        wp_send_json_error(['msg' => __('Invalid form data in the file.', 'formyar')]);
    }

    $form_data = $data['form'];

    $title       = sanitize_text_field($form_data['title']);
    $description = sanitize_textarea_field($form_data['description'] ?? '');
    $type        = sanitize_text_field($form_data['type'] ?? 'custom');

    $structure = is_array($form_data['structure']) ? $form_data['structure'] : [];
    $structure = fmyar_sanitize_deep($structure);

    $settings = is_array($form_data['settings'] ?? null) ? $form_data['settings'] : [];
    $settings = fmyar_sanitize_deep($settings);

    $title = sprintf(
        /* translators: %s: original form title */
        __('%s (Imported)', 'formyar'),
        $title
    );

    $new_form = FormYar_Form::create([
        'title'       => $title,
        'description' => $description,
        'type'        => $type,
        'status'      => 'disabled',
        'form'        => wp_json_encode($structure, JSON_UNESCAPED_UNICODE),
        'settings'    => wp_json_encode($settings, JSON_UNESCAPED_UNICODE),
    ]);

    if ($new_form) {
        wp_send_json_success([
            'msg'      => __('Form imported successfully. It is disabled until you enable it.', 'formyar'),
            'form_id'  => $new_form->get_id(),
            'edit_url' => fmyar_get_form_edit_url($new_form),
            'form'     => [
                'id'          => $new_form->get_id(),
                'title'       => $new_form->get_title(),
                'description' => $new_form->get_description(),
                'status'      => $new_form->get_status(),
                'is_active'   => $new_form->is_active(),
                'entry_count' => 0,
                'created_at'  => $new_form->get_created_at('M j, Y'),
                'edit_url'    => fmyar_get_form_edit_url($new_form),
            ],
        ]);
    }

    wp_send_json_error(['msg' => __('Failed to create the form.', 'formyar')]);
});

/**
 * Handle bulk actions before any output.
 *
 * This runs on the `load-{page}` hook, which is called before
 * the admin header is rendered. This allows us to send headers
 * for file downloads or perform redirects safely.
 *
 * @since 1.1.0
 * @return void
 */
add_action('load-toplevel_page_fmyar_forms', 'fmyar_handle_forms_bulk_actions');
add_action('load-formyar_page_fmyar_forms', 'fmyar_handle_forms_bulk_actions');
function fmyar_handle_forms_bulk_actions()
{
    // Check if a bulk action was submitted.
    if (
        !isset($_POST['bulk_action'])
        || !isset($_POST['_wpnonce'])
        || !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['_wpnonce'])),
            'fmyar_forms_bulk'
        )
    ) {
        return;
    }

    // Check permissions.
    if (!current_user_can('manage_options')) {
        return;
    }

    $action   = sanitize_text_field(wp_unslash($_POST['action'] ?? ''));
    $selected = isset($_POST['selected'])
        ? array_map('absint', wp_unslash($_POST['selected']))
        : [];

    if (empty($selected)) {
        return;
    }

    // ─────────────────────────────────────────
    // Built-in bulk actions
    // ─────────────────────────────────────────
    $built_in_actions = ['delete', 'enable', 'disable', 'export'];

    if (in_array($action, $built_in_actions, true)) {

        // Bulk Export — direct download.
        if ($action === 'export') {
            $export_forms = [];
            foreach ($selected as $form_id) {
                $form = new FormYar_Form($form_id);
                if (!$form->exists()) {
                    continue;
                }

                $export_forms[] = [
                    'title'       => $form->get_title(),
                    'description' => $form->get_description(),
                    'type'        => $form->get_type(),
                    'status'      => $form->get_status(),
                    'structure'   => $form->get_structure_array(),
                    'settings'    => $form->get_settings_array(),
                ];
            }

            if (empty($export_forms)) {
                return;
            }

            $export_data = [
                'plugin'      => 'formyar',
                'version'     => FORMYAR_VERSION,
                'type'        => 'bulk',
                'exported_at' => current_time('mysql'),
                'site_url'    => home_url(),
                'forms_count' => count($export_forms),
                'forms'       => $export_forms,
            ];

            $json = wp_json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $filename = sanitize_file_name(
                'formyar-bulk-export-' . gmdate('Y-m-d-His') . '.json'
            );

            // Clean output buffer (if any).
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Send headers and output.
            nocache_headers();
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($json));

            echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            exit;
        }

        // Delete / Enable / Disable.
        foreach ($selected as $form_id) {
            $form = new FormYar_Form($form_id);
            if (!$form->exists()) {
                continue;
            }

            if ($action === 'delete') {
                $form->delete();
            } elseif ($action === 'enable') {
                $form->update(['status' => 'enable']);
            } elseif ($action === 'disable') {
                $form->update(['status' => 'disabled']);
            }
        }

        return;
    }

    // ─────────────────────────────────────────
    // Pro bulk actions
    // ─────────────────────────────────────────
    /**
     * Action: Handle Pro bulk actions.
     *
     * Allows Pro to handle its own bulk actions.
     *
     * Example usage:
     * add_action('fmyar_handle_bulk_action', function($action, $selected) {
     *     if ($action === 'duplicate_all') {
     *         foreach ($selected as $form_id) {
     *             $form = new FormYar_Form($form_id);
     *             if ($form->exists()) {
     *                 FormYar_Form::create([
     *                     'title' => $form->get_title() . ' (Copy)',
     *                     'form'  => $form->get_structure(),
     *                 ]);
     *             }
     *         }
     *     }
     * }, 10, 2);
     *
     * @since 1.1.0
     * @param string $action   The bulk action name.
     * @param array  $selected Array of selected form IDs.
     */
    do_action('fmyar_handle_bulk_action', $action, $selected);
}

/**
 * Render the floating help widget.
 *
 * Displays a floating button with a popup menu containing links to
 * documentation, tutorials, support, and contact.
 *
 * @since 1.1.0
 * @return void
 */
function fmyar_render_help_widget()
{
    $links = [
        [
            'label'  => __('About', 'formyar'),
            'url'    => esc_url(admin_url('admin.php?page=fmyar_about')),
            'icon'   => 'dashicons-info-outline',
            'target' => '_blank',
        ],
        [
            'label'  => __('Documentation', 'formyar'),
            'url'    => 'https://dangoweb.ir/product/formyar-wordpress-form-builder-plugin/',
            'icon'   => 'dashicons-book-alt',
            'target' => '_blank',
        ],
        [
            'label'  => __('Support Forum', 'formyar'),
            'url'    => 'https://wordpress.org/support/plugin/formyar/',
            'icon'   => 'dashicons-sos',
            'target' => '_blank',
        ],
        [
            'label'  => __('FAQ', 'formyar'),
            'url'    => 'https://wordpress.org/plugins/formyar/#faq',
            'icon'   => 'dashicons-editor-help',
            'target' => '_blank',
        ],
        [
            'label'  => __('Contact Us', 'formyar'),
            'url'    => 'https://dangoweb.ir/panel/?dwv=tickets',
            'icon'   => 'dashicons-email-alt',
            'target' => '_blank',
        ],
    ];

    /**
     * Filter the help widget links.
     *
     * @since 1.1.0
     * @param array $links List of help links.
     */
    $links = apply_filters('fmyar_help_widget_links', $links);
?>
    <div id="fmyar-help-widget" class="fmyar-help-widget">
        <div id="fmyar-help-menu" class="fmyar-help-menu fmyar-hidden">
            <div class="fmyar-help-menu-header">
                <h4 class="fmyar-help-menu-title"><?php esc_html_e('Need Help?', 'formyar'); ?></h4>
                <p class="fmyar-help-menu-subtitle"><?php esc_html_e('We\'re here to help you.', 'formyar'); ?></p>
            </div>
            <ul class="fmyar-help-menu-list">
                <?php foreach ($links as $link) : ?>
                    <li>
                        <a href="<?php echo esc_url($link['url']); ?>"
                            class="fmyar-help-menu-link"
                            <?php if (!empty($link['target'])) : ?>
                            target="<?php echo esc_attr($link['target']); ?>"
                            rel="noopener noreferrer"
                            <?php endif; ?>>
                            <span class="dashicons <?php echo esc_attr($link['icon']); ?>"></span>
                            <span><?php echo esc_html($link['label']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <button type="button"
            id="fmyar-help-toggle"
            class="fmyar-help-toggle"
            aria-label="<?php esc_attr_e('Toggle help menu', 'formyar'); ?>"
            aria-expanded="false"
            aria-controls="fmyar-help-menu">
            <span class="dashicons dashicons-editor-help"></span>
        </button>
    </div>
<?php
}

/**
 * Parse changelog from readme.txt file.
 *
 * Reads the readme.txt file and extracts the Changelog section,
 * then parses it into an array structure for display.
 *
 * Results are cached using transients for performance. Cache is
 * automatically invalidated when the plugin version changes.
 *
 * @since 1.1.0
 * @return array<int, array<string, mixed>> List of changelog releases.
 */
function fmyar_get_changelog_from_readme()
{
    // Build cache key based on plugin version.
    // When version changes, the cache key changes, so old cache is bypassed.
    $cache_key = 'fmyar_changelog_' . md5(FORMYAR_VERSION);
    $cached    = get_transient($cache_key);

    // Return cached value if available.
    if ($cached !== false) {
        return $cached;
    }

    $readme_path = FORMYAR_PATH . 'readme.txt';
    $releases    = [];

    // Check if readme.txt exists.
    if (!file_exists($readme_path)) {
        // Cache empty result for a short time to avoid repeated checks.
        set_transient($cache_key, $releases, HOUR_IN_SECONDS);
        return $releases;
    }

    // Read the file using WP_Filesystem.
    WP_Filesystem();
    global $wp_filesystem;

    if (!$wp_filesystem) {
        return $releases;
    }

    $content = $wp_filesystem->get_contents($readme_path);

    if (empty($content)) {
        set_transient($cache_key, $releases, HOUR_IN_SECONDS);
        return $releases;
    }

    // Find the Changelog section.
    if (!preg_match('/==\s*Changelog\s*==(.*?)(==|\z)/is', $content, $matches)) {
        set_transient($cache_key, $releases, HOUR_IN_SECONDS);
        return $releases;
    }

    $changelog_text = trim($matches[1]);

    if (empty($changelog_text)) {
        set_transient($cache_key, $releases, HOUR_IN_SECONDS);
        return $releases;
    }

    // Parse changelog entries.
    // Pattern supports: = 1.1.0 = or = 1.1.0 - 2026-10-10 =
    $pattern = '/=\s*([0-9]+\.[0-9]+(?:\.[0-9]+)?)(?:\s*-\s*(\d{4}-\d{2}-\d{2}))?\s*=\s*(.*?)(?=\n=\s*[0-9]|\z)/is';

    if (preg_match_all($pattern, $changelog_text, $version_matches, PREG_SET_ORDER)) {
        foreach ($version_matches as $version_match) {
            $version = trim($version_match[1]);
            $date    = trim($version_match[2] ?? '');
            $body    = trim($version_match[3]);

            // Parse each line as a changelog item.
            $items = [];
            $lines = explode("\n", $body);

            foreach ($lines as $line) {
                $line = trim($line);

                // Match "* Item text" or "- Item text".
                if (preg_match('/^[\*\-]\s+(.+)$/', $line, $item_match)) {
                    $items[] = trim($item_match[1]);
                }
            }

            if (!empty($items)) {
                $releases[] = [
                    'version' => $version,
                    'date'    => $date,
                    'items'   => $items,
                ];
            }
        }
    }

    // Cache the result for 12 hours.
    set_transient($cache_key, $releases, 12 * HOUR_IN_SECONDS);

    return $releases;
}

/**
 * Clear the changelog cache.
 *
 * Deletes the cached changelog transient. Called when readme.txt
 * changes or when the plugin is updated.
 *
 * @since 1.1.0
 * @return void
 */
function fmyar_clear_changelog_cache()
{
    // Clear cache for current version.
    delete_transient('fmyar_changelog_' . md5(FORMYAR_VERSION));
}
