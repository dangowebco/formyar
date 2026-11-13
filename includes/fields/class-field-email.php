<?php

/**
 * Email Field
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Email extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'email';
    }

    public function get_label()
    {
        return __('Email', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-email';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
            'placeholder'   => '',
            'description'   => '',
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
                <input type="email" name="config[default_value]" value="<?php echo esc_attr($this->get_config('default_value')); ?>" class="fmyar-input-full" dir="ltr">
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
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
        $default_value = esc_attr($this->get_config('default_value'));
        $required      = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<input type="email" name="fmyar_form_data[' . esc_attr($this->id) . ']" placeholder="' . $placeholder . '" value="' . $default_value . '" ';
        $html .= $required . ' class="fmyar-input-full" dir="ltr">';
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

        if ($answer !== '' && !is_email($answer)) {
            /* translators: %s: field label */
            return sprintf(__('%s must be a valid email address.', 'formyar'), $label);
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return sanitize_email($answer);
    }
}