<?php

/**
 * Select (Dropdown) Field
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Select extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'select';
    }

    public function get_label()
    {
        return __('Dropdown', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-arrow-down-alt2';
    }

    public function get_default_config()
    {
        return [
            'label'       => '',
            'required'    => false,
            'placeholder' => '',
            'description' => '',
            'options'     => [
                ['value' => '', 'label' => __('Option 1', 'formyar')],
                ['value' => '', 'label' => __('Option 2', 'formyar')],
            ],
        ];
    }

    public function render_settings()
    {
        $options = $this->get_config('options', []);
?>
        <div class="fmyar-field-settings fmyar-card-form" data-field-type="select">
            <div class="fmyar-form-group">
                <label class="fmyar-form-label">
                    <?php esc_html_e('Field Label', 'formyar'); ?> <span class="fmyar-required">*</span>
                </label>
                <input type="text" name="config[label]" value="<?php echo esc_attr($this->get_config('label')); ?>" class="fmyar-input-full" required>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Placeholder (First Empty Option)', 'formyar'); ?></label>
                <input type="text" name="config[placeholder]" value="<?php echo esc_attr($this->get_config('placeholder', __('Select an option', 'formyar'))); ?>" class="fmyar-input-full">
                <p class="fmyar-form-hint"><?php esc_html_e('Leave empty for no placeholder', 'formyar'); ?></p>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
            </div>

            <div class="fmyar-form-group">
                <div class="fmyar-options-header">
                    <label class="fmyar-form-label"><?php esc_html_e('Options', 'formyar'); ?> <span class="fmyar-required">*</span></label>
                    <button type="button" class="fmyar-add-option fmyar-filter-btn fmyar-filter-btn-active">+ <?php esc_html_e('Add Option', 'formyar'); ?></button>
                </div>
                <div class="fmyar-options-container fmyar-space-y-2">
                    <?php foreach ($options as $index => $option): ?>
                        <div class="fmyar-option-row">
                            <input type="text" name="config[options][<?php echo esc_attr($index); ?>][label]" value="<?php echo esc_attr($option['label']); ?>" placeholder="<?php esc_html_e('Label', 'formyar'); ?>" class="fmyar-input-full">
                            <input type="text" name="config[options][<?php echo esc_attr($index); ?>][value]" value="<?php echo esc_attr($option['value']); ?>" placeholder="<?php esc_html_e('Value (optional)', 'formyar'); ?>" class="fmyar-input-full" dir="ltr">
                            <button type="button" class="fmyar-remove-option fmyar-action-btn fmyar-action-btn-danger">
                                <svg class="fmyar-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="fmyar-toggle-row">
                <input type="checkbox" name="config[required]" id="field_required" value="1" <?php checked($this->get_config('required'), true); ?> class="fmyar-checkbox">
                <label for="field_required" class="fmyar-toggle-label"><?php esc_html_e('This field is required', 'formyar'); ?></label>
            </div>
        </div>
<?php
    }

    public function render_frontend_html()
    {
        $label       = esc_html($this->get_config('label'));
        $placeholder = $this->get_config('placeholder', '');
        $description = esc_html($this->get_config('description'));
        $options     = $this->get_config('options', []);
        $required    = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<select name="fmyar_form_data[' . esc_attr($this->id) . ']" ' . $required . ' class="fmyar-select-full">';

        if ($placeholder) {
            $html .= '<option value="">' . esc_html($placeholder) . '</option>';
        }

        foreach ($options as $option) {
            $value = !empty($option['value']) ? $option['value'] : $option['label'];
            $html .= '<option value="' . esc_attr($value) . '">' . esc_html($option['label']) . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    protected function validate_answer($answer)
    {
        $answer = trim($answer);
        $label  = $this->get_config('label');

        if ($this->get_config('required') && $answer === '') {
            /* translators: %s: field label */
            return sprintf(__('%s is required.', 'formyar'), $label);
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return sanitize_text_field($answer);
    }
}
