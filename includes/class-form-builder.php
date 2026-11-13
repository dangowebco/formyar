<?php

/**
 * Form Builder class
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Form_Builder
{
    private $entity_type;
    private $entity_id;
    private $table_name;
    private $id_column;
    private $fields = [];

    public function __construct($entity_type, $entity_id, $table_name, $id_column = 'id')
    {
        $this->entity_type = $entity_type;
        $this->entity_id   = $entity_id;
        $this->table_name  = $table_name;
        $this->id_column   = $id_column;

        $this->load_fields();
    }

    private function load_fields()
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $form_json = $wpdb->get_var($wpdb->prepare(
            "SELECT form FROM {$this->table_name} WHERE {$this->id_column} = %d",
            $this->entity_id
        ));

        $this->fields = fmyar_parse_form_structure($form_json);
    }

    public function get_fields()
    {
        return $this->fields;
    }

    public function render_builder_ui()
    {
        $field_types = fmyar_get_field_types();
        include FORMYAR_PATH . 'admin/views/builder-ui.php';
    }

    public function render_field_item($field)
    {
        $label       = $field->get_config('label') ?: __('Untitled Field', 'formyar');
        $type_label  = $field->get_label();
        $field_id    = $field->get_id();
        $field_type  = $field->get_type();
        $is_required = $field->get_config('required');
        $config_json = json_encode($field->to_array()['config'], JSON_HEX_APOS | JSON_HEX_TAG);

        ob_start();
?>
        <div class="fmyar-field-item"
            data-field-id="<?php echo esc_attr($field_id); ?>"
            data-type="<?php echo esc_attr($field_type); ?>"
            data-config='<?php echo esc_attr($config_json); ?>'>

            <div class="fmyar-field-drag-handle">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7 4a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM7 10a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM7 16a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                </svg>
            </div>

            <div class="fmyar-field-item-content">
                <div class="fmyar-field-item-header">
                    <span class="fmyar-field-item-icon">
                        <span class="dashicons <?php echo esc_attr($field->get_icon()); ?>"></span>
                    </span>
                    <div class="fmyar-field-item-info">
                        <span class="fmyar-field-item-label"><?php echo esc_html($label); ?></span>
                        <span class="fmyar-field-item-type"><?php echo esc_html($type_label); ?></span>
                    </div>
                    <?php if ($is_required): ?>
                        <span class="fmyar-field-required-badge"><?php esc_html_e('Required', 'formyar'); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="fmyar-field-item-actions">
                <button type="button" class="fmyar-field-action fmyar-edit-field" data-field-id="<?php echo esc_attr($field_id); ?>" title="<?php esc_attr_e('Edit field', 'formyar'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </button>
                <button type="button" class="fmyar-field-action fmyar-duplicate-field" data-field-id="<?php echo esc_attr($field_id); ?>" title="<?php esc_attr_e('Duplicate field', 'formyar'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                        <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                    </svg>
                </button>
                <button type="button" class="fmyar-field-action fmyar-field-action-danger fmyar-delete-field" data-field-id="<?php echo esc_attr($field_id); ?>" title="<?php esc_attr_e('Delete field', 'formyar'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    public function save_fields($fields_data)
    {
        $fields = [];
        foreach ($fields_data as $field_data) {
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

        return fmyar_save_form_structure($this->table_name, $this->id_column, $this->entity_id, $fields);
    }
}
