<?php

/**
 * Date Field (Gregorian)
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Date extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'date';
    }

    public function get_label()
    {
        return __('Date', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-calendar';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
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
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
            </div>

            <div class="fmyar-form-grid">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Min Date', 'formyar'); ?></label>
                    <input type="date" name="config[min_date]" value="<?php echo esc_attr($this->get_config('min_date')); ?>" class="fmyar-input-full" dir="ltr">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max Date', 'formyar'); ?></label>
                    <input type="date" name="config[max_date]" value="<?php echo esc_attr($this->get_config('max_date')); ?>" class="fmyar-input-full" dir="ltr">
                </div>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Default Value', 'formyar'); ?></label>
                <input type="date" name="config[default_value]" value="<?php echo esc_attr($this->get_config('default_value')); ?>" class="fmyar-input-full" dir="ltr">
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
        $description   = esc_html($this->get_config('description'));
        $min_date      = esc_attr($this->get_config('min_date'));
        $max_date      = esc_attr($this->get_config('max_date'));
        $default_value = esc_attr($this->get_config('default_value'));
        $required      = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<input type="date" name="fmyar_form_data[' . esc_attr($this->id) . ']" value="' . $default_value . '" ';

        if ($min_date) $html .= 'min="' . $min_date . '" ';
        if ($max_date) $html .= 'max="' . $max_date . '" ';

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

        if ($answer !== '') {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $answer)) {
                /* translators: %s: field label */
                return sprintf(__('%s must be a valid date.', 'formyar'), $label);
            }

            $min_date = $this->get_config('min_date');
            $max_date = $this->get_config('max_date');

            if ($min_date && $answer < $min_date) {
                /* translators: %1$s: field label, %2$s: date */
                return sprintf(__('%1$s must be on or after %2$s.', 'formyar'), $label, $min_date);
            }

            if ($max_date && $answer > $max_date) {
                /* translators: %1$s: field label, %2$s: date */
                return sprintf(__('%1$s must be on or before %2$s.', 'formyar'), $label, $max_date);
            }
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return sanitize_text_field($answer);
    }
}
