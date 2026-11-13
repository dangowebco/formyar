<?php

/**
 * Form class - manage individual forms
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Form
{
    private $id;
    private $data;
    private $exists = false;

    public function __construct($form_id = 0)
    {
        $this->id = intval($form_id);
        if ($this->id > 0) {
            $this->load();
        }
    }

    private function load()
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $this->data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $this->id
        ));

        if ($this->data) {
            $this->exists = true;
        }
    }

    public function exists()
    {
        return $this->exists;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_title()
    {
        return $this->data->title ?? '';
    }

    public function get_description()
    {
        return $this->data->description ?? '';
    }

    public function get_type()
    {
        return $this->data->type ?? 'custom';
    }

    public function get_status()
    {
        return $this->data->status ?? 'disabled';
    }

    public function is_active()
    {
        return $this->get_status() === 'enable';
    }

    public function get_structure()
    {
        return $this->data->form ?? '';
    }

    public function get_structure_array()
    {
        $structure = $this->get_structure();
        if (empty($structure)) {
            return ['version' => '1.0', 'fields' => []];
        }

        $data = json_decode($structure, true);
        return is_array($data) ? $data : ['version' => '1.0', 'fields' => []];
    }

    public function get_fields()
    {
        $structure = $this->get_structure_array();

        if (empty($structure['fields'])) {
            return [];
        }

        $fields = [];
        foreach ($structure['fields'] as $field_data) {
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

        return $fields;
    }

    public function get_settings()
    {
        return $this->data->settings ?? '';
    }

    public function get_settings_array()
    {
        $settings = $this->get_settings();
        if (empty($settings)) {
            return [];
        }

        $data = json_decode($settings, true);
        return is_array($data) ? $data : [];
    }

    public function get_setting($key, $default = null)
    {
        $settings = $this->get_settings_array();
        return $settings[$key] ?? $default;
    }

    public function get_created_at($format = null)
    {
        if (!$this->data || !$this->data->created_at) {
            return '';
        }
        return $format ? mysql2date($format, $this->data->created_at) : $this->data->created_at;
    }

    public function get_updated_at($format = null)
    {
        if (!$this->data || !$this->data->updated_at) {
            return '';
        }
        return $format ? mysql2date($format, $this->data->updated_at) : $this->data->updated_at;
    }

    public function get_created_by()
    {
        return $this->data->created_by ?? 0;
    }

    public function get_entry_count()
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS_ENTRIES;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        return intval($wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE form_id = %d",
            $this->id
        )));
    }

    public function get_entries($per_page = 20, $page = 1)
    {
        global $wpdb;
        $table  = FORMYAR_TABLE_FORMS_ENTRIES;
        $users  = $wpdb->users;
        $offset = ($page - 1) * $per_page;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        return $wpdb->get_results($wpdb->prepare(
            "SELECT e.*, u.display_name, u.user_email
             FROM {$table} e
             LEFT JOIN {$users} u ON e.user_id = u.ID
             WHERE e.form_id = %d
             ORDER BY e.created_at DESC
             LIMIT %d OFFSET %d",
            $this->id,
            $per_page,
            $offset
        ));
    }

    public function get_entry($entry_id)
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS_ENTRIES;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d AND form_id = %d",
            $entry_id,
            $this->id
        ));
    }

    public function get_entry_data($entry_id)
    {
        $entry = $this->get_entry($entry_id);
        if (!$entry || empty($entry->entry_data)) {
            return [];
        }

        $data = json_decode($entry->entry_data, true);
        return is_array($data) ? $data : [];
    }

    public static function create($data)
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS;

        $defaults = [
            'title'       => __('Untitled Form', 'formyar'),
            'description' => '',
            'form'        => json_encode(['version' => '1.0', 'fields' => []]),
            'type'        => 'custom',
            'settings'    => '{}',
            'created_by'  => get_current_user_id(),
            'created_at'  => current_time('mysql'),
            'updated_at'  => current_time('mysql'),
            'status'      => 'disabled',
        ];

        $insert_data = wp_parse_args($data, $defaults);

        $result = $wpdb->insert($table, $insert_data, ['%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s']);

        if ($result) {
            return new self($wpdb->insert_id);
        }

        return false;
    }

    public function update($data)
    {
        if (!$this->exists) {
            return false;
        }

        global $wpdb;
        $table = FORMYAR_TABLE_FORMS;

        $data['updated_at'] = current_time('mysql');

        $result = $wpdb->update($table, $data, ['id' => $this->id]);

        if ($result !== false) {
            $this->load();
            return true;
        }

        return false;
    }

    public function update_structure($structure)
    {
        if (is_array($structure)) {
            $structure = wp_json_encode($structure, JSON_UNESCAPED_UNICODE);
        }
        return $this->update(['form' => $structure]);
    }

    public function update_settings($settings)
    {
        if (is_array($settings)) {
            $settings = wp_json_encode($settings, JSON_UNESCAPED_UNICODE);
        }
        return $this->update(['settings' => $settings]);
    }

    public function delete()
    {
        if (!$this->exists) {
            return false;
        }

        global $wpdb;
        $wpdb->delete(FORMYAR_TABLE_FORMS_ENTRIES, ['form_id' => $this->id], ['%d']);
        return $wpdb->delete(FORMYAR_TABLE_FORMS, ['id' => $this->id], ['%d']);
    }

    public function save_entry($entry_data, $user_id = 0)
    {
        if (!$this->exists) {
            return false;
        }

        global $wpdb;
        $table = FORMYAR_TABLE_FORMS_ENTRIES;

        $result = $wpdb->insert($table, [
            'form_id'    => $this->id,
            'user_id'    => $user_id ?: get_current_user_id(),
            'entry_data' => is_array($entry_data) ? wp_json_encode($entry_data, JSON_UNESCAPED_UNICODE) : $entry_data,
            'status'     => 1,
            'created_at' => current_time('mysql'),
        ], ['%d', '%d', '%s', '%d', '%s']);

        $entry_id = $result ? $wpdb->insert_id : false;

        if ($entry_id) {
            /**
             * Action: Entry saved.
             *
             * Fires after an entry is saved to the database.
             * Pro can use this for additional processing.
             *
             * @since 1.1.0
             * @param int          $entry_id   The new entry ID.
             * @param FormYar_Form $form       Current form object.
             * @param array        $entry_data The entry data.
             * @param int          $user_id    User ID (0 for guests).
             */
            do_action('fmyar_entry_saved', $entry_id, $this, $entry_data, $user_id);
        }

        return $entry_id;
    }

    public function delete_entry($entry_id)
    {
        global $wpdb;
        return $wpdb->delete(FORMYAR_TABLE_FORMS_ENTRIES, ['id' => $entry_id, 'form_id' => $this->id], ['%d', '%d']);
    }

    public static function get_all($args = [])
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS;

        $defaults = [
            'status'   => '',
            'orderby'  => 'id',
            'search'   => '',
            'order'    => 'DESC',
            'per_page' => 20,
            'page'     => 1,
        ];

        $args = wp_parse_args($args, $defaults);

        $where  = ["type != 'poll'"];
        $params = [];

        if (!empty($args['status'])) {
            $where[]  = "status = %s";
            $params[] = $args['status'];
        }

        // Search
        if (!empty($args['search'])) {
            $search_term = '%' . $wpdb->esc_like($args['search']) . '%';
            $where[]  = "(title LIKE %s OR description LIKE %s)";
            $params[] = $search_term;
            $params[] = $search_term;
        }

        $where_clause = 'WHERE ' . implode(' AND ', $where);
        $offset       = ($args['page'] - 1) * $args['per_page'];

        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $query    = "SELECT * FROM {$table} {$where_clause} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d";
        $params[] = $args['per_page'];
        $params[] = $offset;

        return $wpdb->get_results($wpdb->prepare($query, $params));
    }

    public static function count_all($status = '', $search = '')
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
        $sql    = "SELECT COUNT(*) FROM {$table} WHERE type != 'poll'";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND status = %s";
            $params[] = $status;
        }

        // Search
        if (!empty($search)) {
            $search_term = '%' . $wpdb->esc_like($search) . '%';
            $sql .= " AND (title LIKE %s OR description LIKE %s)";
            $params[] = $search_term;
            $params[] = $search_term;
        }

        if (!empty($params)) {
            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            return intval($wpdb->get_var($wpdb->prepare($sql, $params)));
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        return intval($wpdb->get_var($sql));
    }

    public function render()
    {
        if (!$this->is_active()) {
            return '<div class="fmyar-message fmyar-message-warning">'
                . esc_html__('This form is currently inactive.', 'formyar') . '</div>';
        }

        /**
         * Action: Form viewed.
         *
         * Fires when a form is rendered on the frontend.
         * Pro can use this for analytics tracking.
         *
         * @since 1.1.0
         * @param FormYar_Form $form Current form object.
         * @param int          $user_id Current user ID (0 for guests).
         */
        do_action('fmyar_form_viewed', $this, get_current_user_id());

        $settings       = $this->get_settings_array();
        $who_can_submit = $settings['limitation']['who_can_submit'] ?? 'all';

        if ($who_can_submit === 'logged_in' && !is_user_logged_in()) {
            return '<div class="fmyar-message fmyar-message-warning">'
                . esc_html__('Please log in to submit this form.', 'formyar') . '</div>';
        }

        $user_id    = get_current_user_id();
        $validation = $this->validate_submission($user_id);

        if (!$validation['valid']) {
            return '<div class="fmyar-message fmyar-message-warning">'
                . esc_html($validation['msg']) . '</div>';
        }

        $form_structure = $this->get_structure();
        $structure_data = json_decode($form_structure, true);
        $fields_count   = count($structure_data['fields'] ?? []);

        if (empty($form_structure)) {
            return '<p class="fmyar-text-gray-500 fmyar-text-center fmyar-py-8">'
                . esc_html__('This form has no fields yet.', 'formyar') . '</p>';
        }

        ob_start();

        /**
         * Action: Before form wrapper.
         *
         * Allows Pro to inject content before the form.
         *
         * @since 1.1.0
         * @param FormYar_Form $form Current form object.
         */
        do_action('fmyar_before_form_render', $this);

?>
        <div class="fmyar-form-wrapper" data-form-id="<?php echo esc_attr($this->id); ?>">

            <?php
            /**
             * Action: Before form header.
             *
             * @since 1.1.0
             * @param FormYar_Form $form Current form object.
             */
            do_action('fmyar_before_form_header', $this);
            ?>

            <!-- Form Header -->
            <div class="fmyar-form-header">
                <h2 class="fmyar-form-title"><?php echo esc_html($this->get_title()); ?></h2>
                <?php if ($this->get_description()): ?>
                    <p class="fmyar-form-description"><?php echo esc_html($this->get_description()); ?></p>
                <?php endif; ?>
            </div>

            <?php
            /**
             * Action: After form header.
             *
             * @since 1.1.0
             * @param FormYar_Form $form Current form object.
             */
            do_action('fmyar_after_form_header', $this);
            ?>

            <?php if ($fields_count > 0): ?>
                <!-- Progress Bar -->
                <div class="fmyar-form-progress" data-total="<?php echo esc_attr($fields_count); ?>">
                    <div class="fmyar-form-progress-bar" style="width: 0%"></div>
                </div>
                <div class="fmyar-form-progress-text">
                    <span class="fmyar-form-progress-count">0</span>
                    /
                    <span class="fmyar-form-progress-total"><?php echo esc_html($fields_count); ?></span>
                    <?php echo esc_html__('fields completed', 'formyar'); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST"
                id="fmyar-form-<?php echo esc_attr($this->id); ?>"
                class="fmyar-form"
                enctype="multipart/form-data"
                data-form-id="<?php echo esc_attr($this->id); ?>">

                <input type="hidden" name="fmyar_form_id" value="<?php echo esc_attr($this->id); ?>">
                <?php echo wp_nonce_field('fmyar_form_submit', '_wpnonce', true, false); ?>

                <?php
                /**
                 * Action: Before form fields.
                 *
                 * Allows Pro to inject content before fields (e.g., multi-step navigation).
                 *
                 * @since 1.1.0
                 * @param FormYar_Form $form Current form object.
                 */
                do_action('fmyar_before_form_fields', $this);
                ?>

                <?php echo fmyar_render_form_frontend($form_structure); ?>

                <?php
                /**
                 * Action: After form fields.
                 *
                 * Allows Pro to inject content after fields (e.g., multi-step navigation).
                 *
                 * @since 1.1.0
                 * @param FormYar_Form $form Current form object.
                 */
                do_action('fmyar_after_form_fields', $this);
                ?>

                <!-- Form Footer -->
                <div class="fmyar-form-footer">
                    <?php
                    /**
                     * Action: Before submit button.
                     *
                     * @since 1.1.0
                     * @param FormYar_Form $form Current form object.
                     */
                    do_action('fmyar_before_submit_button', $this);
                    ?>

                    <button type="submit" class="fmyar-btn fmyar-btn-primary fmyar-btn-block fmyar-btn-lg">
                        <span class="fmyar-btn-text"><?php echo esc_html__('Submit Form', 'formyar'); ?></span>
                        <span class="fmyar-btn-spinner fmyar-hidden"></span>
                    </button>

                    <p class="fmyar-form-footer-note">
                        <?php echo esc_html__('Your information is safe with us. We never share your data.', 'formyar'); ?>
                    </p>

                    <?php
                    /**
                     * Action: After submit button.
                     *
                     * @since 1.1.0
                     * @param FormYar_Form $form Current form object.
                     */
                    do_action('fmyar_after_submit_button', $this);
                    ?>
                </div>

                <?php
                /**
                 * Action: After form tag.
                 *
                 * @since 1.1.0
                 * @param FormYar_Form $form Current form object.
                 */
                do_action('fmyar_after_form_tag', $this);
                ?>

            </form>
        </div>

<?php

        /**
         * Action: After form wrapper.
         *
         * Allows Pro to inject content after the form.
         *
         * @since 1.1.0
         * @param FormYar_Form $form Current form object.
         */
        do_action('fmyar_after_form_render', $this);

        return ob_get_clean();
    }

    public static function shortcode($atts)
    {
        $atts = shortcode_atts(['id' => 0], $atts);

        $form_id = intval($atts['id']);

        if (!$form_id) {
            return '<p class="fmyar-text-error">' . esc_html__('Form ID is required.', 'formyar') . '</p>';
        }

        $form = new self($form_id);

        if (!$form->exists()) {
            return '<p class="fmyar-text-error">' . esc_html__('Form not found.', 'formyar') . '</p>';
        }

        return $form->render();
    }

    public function validate_submission($user_id = 0)
    {
        $settings   = $this->get_settings_array();
        $limitation = $settings['limitation'] ?? ['type' => 'none'];

        $who_can_submit = $limitation['who_can_submit'] ?? 'all';
        if ($who_can_submit === 'logged_in' && !is_user_logged_in()) {
            return ['valid' => false, 'msg' => __('You must be logged in to submit this form.', 'formyar')];
        }

        if (empty($limitation['type']) || $limitation['type'] === 'none') {
            return ['valid' => true];
        }

        $max_entries = intval($limitation['max_entries'] ?? 1);
        $time_limit  = intval($limitation['time_limit'] ?? 0);
        $message     = $limitation['message'] ?? __('You have already submitted this form.', 'formyar');

        global $wpdb;
        $table       = FORMYAR_TABLE_FORMS_TRACKING;
        $where       = $wpdb->prepare("form_id = %d", $this->id);
        $where_parts = [];

        if (in_array($limitation['type'], ['user', 'both']) && $user_id > 0) {
            $where_parts[] = $wpdb->prepare("user_id = %d", $user_id);
        }

        if (in_array($limitation['type'], ['ip', 'both'])) {
            $where_parts[] = $wpdb->prepare("ip_address = %s", fmyar_get_client_ip());
        }

        if ($limitation['type'] === 'cookie') {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $cookie_hash = isset($_COOKIE['fmyar_form_' . $this->id]) ? sanitize_text_field(wp_unslash($_COOKIE['fmyar_form_' . $this->id])) : '';
            if (!empty($cookie_hash)) {
                $where_parts[] = $wpdb->prepare("cookie_hash = %s", $cookie_hash);
            }
        }

        if (empty($where_parts)) {
            return ['valid' => true];
        }

        $where .= ' AND (' . implode(' OR ', $where_parts) . ')';

        if ($time_limit > 0) {
            $where .= $wpdb->prepare(" AND created_at > DATE_SUB(NOW(), INTERVAL %d SECOND)", $time_limit);
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE {$where}");

        if ($count >= $max_entries) {
            return ['valid' => false, 'msg' => $message, 'count' => $count, 'max' => $max_entries];
        }

        return ['valid' => true];
    }

    public function track_submission($user_id = 0)
    {
        global $wpdb;
        $table = FORMYAR_TABLE_FORMS_TRACKING;

        $cookie_hash = uniqid('fmyar_', true);

        $wpdb->insert($table, [
            'form_id'     => $this->id,
            'user_id'     => $user_id,
            'ip_address'  => fmyar_get_client_ip(),
            'cookie_hash' => $cookie_hash,
            'created_at'  => current_time('mysql'),
        ]);

        if (!isset($_COOKIE['fmyar_form_' . $this->id])) {
            setcookie('fmyar_form_' . $this->id, $cookie_hash, time() + (365 * 24 * 60 * 60), '/');
        }
    }
}
