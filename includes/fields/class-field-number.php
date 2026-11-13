<?php

/**
 * Number Field
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_Number extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'number';
    }

    public function get_label()
    {
        return __('Number', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-calculator';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
            'placeholder'   => '',
            'description'   => '',
            'min'           => '',
            'max'           => '',
            'step'          => '1',
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
                <input type="number" name="config[default_value]" value="<?php echo esc_attr($this->get_config('default_value')); ?>" class="fmyar-input-full">
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Description', 'formyar'); ?></label>
                <textarea name="config[description]" rows="2" class="fmyar-textarea"><?php echo esc_textarea($this->get_config('description')); ?></textarea>
            </div>

            <div class="fmyar-form-grid fmyar-form-grid-3">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Min', 'formyar'); ?></label>
                    <input type="number" name="config[min]" value="<?php echo esc_attr($this->get_config('min')); ?>" class="fmyar-input-full">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max', 'formyar'); ?></label>
                    <input type="number" name="config[max]" value="<?php echo esc_attr($this->get_config('max')); ?>" class="fmyar-input-full">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Step', 'formyar'); ?></label>
                    <input type="number" name="config[step]" value="<?php echo esc_attr($this->get_config('step', '1')); ?>" min="0.01" step="0.01" class="fmyar-input-full">
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
        $min           = esc_attr($this->get_config('min'));
        $max           = esc_attr($this->get_config('max'));
        $step          = esc_attr($this->get_config('step', '1'));
        $default_value = esc_attr($this->get_config('default_value'));
        $required      = $this->get_config('required') ? 'required' : '';

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '">';

        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        $html .= '<input type="number" name="fmyar_form_data[' . esc_attr($this->id) . ']" placeholder="' . $placeholder . '" value="' . $default_value . '" ';

        if ($min !== '') $html .= 'min="' . $min . '" ';
        if ($max !== '') $html .= 'max="' . $max . '" ';
        if ($step !== '') $html .= 'step="' . $step . '" ';

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
            if (!is_numeric($answer)) {
                /* translators: %s: field label */
                return sprintf(__('%s must be a number.', 'formyar'), $label);
            }

            $min = $this->get_config('min');
            $max = $this->get_config('max');

            if ($min !== '' && floatval($answer) < floatval($min)) {
                /* translators: %1$s: field label, %2$s: minimum value */
                return sprintf(__('%1$s must be at least %2$s.', 'formyar'), $label, $min);
            }

            if ($max !== '' && floatval($answer) > floatval($max)) {
                /* translators: %1$s: field label, %2$s: maximum value */
                return sprintf(__('%1$s must be at most %2$s.', 'formyar'), $label, $max);
            }
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return floatval($answer);
    }
}
