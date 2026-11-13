<?php

/**
 * Jalali (Shamsi) Date Field
 *
 * Requires a Jalali DatePicker initialized on input[data-jdp] in the frontend.
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Date_Jalali extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'date_jalali';
    }

    public function get_label()
    {
        return __('Jalali Date', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-calendar-alt';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
            'placeholder'   => '',
            'description'   => '',
            'min_date'      => '',
            'max_date'      => '',
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
                <input type="text" name="config[placeholder]" value="<?php echo esc_attr($this->get_config('placeholder', 'مثال: ۱۴۰۳/۰۶/۱۵')); ?>" class="fmyar-input-full">
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
            </div>

            <div class="fmyar-form-grid">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Min Date (Jalali)', 'formyar'); ?></label>
                    <input type="text" name="config[min_date]" value="<?php echo esc_attr($this->get_config('min_date')); ?>" data-jdp data-jdp-only-date dir="ltr" class="fmyar-input-full">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max Date (Jalali)', 'formyar'); ?></label>
                    <input type="text" name="config[max_date]" value="<?php echo esc_attr($this->get_config('max_date')); ?>" data-jdp data-jdp-only-date dir="ltr" class="fmyar-input-full">
                </div>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Default Value (Jalali)', 'formyar'); ?></label>
                <input type="text" name="config[default_value]" value="<?php echo esc_attr($this->get_config('default_value')); ?>" data-jdp data-jdp-only-date dir="ltr" class="fmyar-input-full">
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
        $placeholder   = esc_attr($this->get_config('placeholder', 'مثال: ۱۴۰۳/۰۶/۱۵'));
        $description   = esc_html($this->get_config('description'));
        $default_value = esc_attr($this->get_config('default_value'));
        $required      = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<input type="text" name="fmyar_form_data[' . esc_attr($this->id) . ']" placeholder="' . $placeholder . '" value="' . $default_value . '" data-jdp data-jdp-only-date dir="ltr" ';

        if ($this->get_config('min_date')) $html .= 'data-jdp-min-date="' . esc_attr($this->get_config('min_date')) . '" ';
        if ($this->get_config('max_date')) $html .= 'data-jdp-max-date="' . esc_attr($this->get_config('max_date')) . '" ';

        $html .= $required . ' class="fmyar-input-full">';
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
            if (!preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $answer)) {
                /* translators: %s: field label */
                return sprintf(__('%s must be a valid date (YYYY/MM/DD).', 'formyar'), $label);
            }
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return sanitize_text_field($answer);
    }
}