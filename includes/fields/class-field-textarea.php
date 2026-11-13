<?php

/**
 * Textarea Field
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Textarea extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'textarea';
    }

    public function get_label()
    {
        return __('Text Area', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-editor-paragraph';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
            'placeholder'   => '',
            'description'   => '',
            'rows'          => 4,
            'maxlength'     => 500,
            'default_value' => '',
        ];
    }

    public function render_settings()
    {
        ?>
        <div class="fmyar-field-settings fmyar-card-form">
            <div class="fmyar-form-group">
                <label class="fmyar-form-label">
                    <?php esc_html_e('Field Label', 'formyar'); ?> <span class="fmyar-required">*</span>
                </label>
                <input type="text" name="config[label]" value="<?php echo esc_attr($this->get_config('label')); ?>" class="fmyar-input-full" required>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Placeholder', 'formyar'); ?></label>
                <input type="text" name="config[placeholder]" value="<?php echo esc_attr($this->get_config('placeholder')); ?>" class="fmyar-input-full">
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Default Value', 'formyar'); ?></label>
                <textarea name="config[default_value]" rows="3" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('default_value')); ?></textarea>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
                <p class="fmyar-form-hint"><?php esc_html_e('Help text shown below the field', 'formyar'); ?></p>
            </div>

            <div class="fmyar-form-grid">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Rows', 'formyar'); ?></label>
                    <input type="number" name="config[rows]" value="<?php echo esc_attr($this->get_config('rows', 4)); ?>" min="1" max="20" class="fmyar-input-full">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max Length', 'formyar'); ?></label>
                    <input type="number" name="config[maxlength]" value="<?php echo esc_attr($this->get_config('maxlength', 500)); ?>" min="1" max="5000" class="fmyar-input-full">
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
        $label         = esc_html($this->get_config('label'));
        $placeholder   = esc_attr($this->get_config('placeholder'));
        $description   = esc_html($this->get_config('description'));
        $rows          = intval($this->get_config('rows', 4));
        $maxlength     = intval($this->get_config('maxlength', 500));
        $default_value = esc_textarea($this->get_config('default_value'));
        $required      = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<textarea name="fmyar_form_data[' . esc_attr($this->id) . ']" placeholder="' . $placeholder . '" rows="' . $rows . '" ';

        if ($maxlength > 0) {
            $html .= 'maxlength="' . $maxlength . '" ';
        }

        $html .= $required . ' class="fmyar-textarea">';
        $html .= $default_value;
        $html .= '</textarea>';
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

        if ($answer !== '') {
            $maxlength = intval($this->get_config('maxlength', 500));
            $length    = mb_strlen($answer);

            if ($maxlength > 0 && $length > $maxlength) {
/* translators: %1$s: field label, %2$d: maximum characters */
return sprintf(__('%1$s must be less than %2$d characters.', 'formyar'), $label, $maxlength);
            }
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return sanitize_textarea_field($answer);
    }
}