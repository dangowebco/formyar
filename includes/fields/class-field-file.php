<?php

/**
 * File Upload Field
 *
 * Frontend drag & drop and validation handled in assets/js/form-public.js
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

class FormYar_Field_File extends FormYar_Form_Field
{
    public function get_type()
    {
        return 'file';
    }

    public function get_label()
    {
        return __('File Upload', 'formyar');
    }

    public function get_icon()
    {
        return 'dashicons-upload';
    }

    public function get_default_config()
    {
        return [
            'label'         => '',
            'required'      => false,
            'description'   => '',
            'allowed_types' => 'jpg,jpeg,png,pdf,doc,docx',
            'max_size'      => 5,
            'max_files'     => 1,
            'button_text'   => __('Choose File', 'formyar'),
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
                <p class="fmyar-form-hint"><?php esc_html_e('Show help text or accepted file types info', 'formyar'); ?></p>
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Button Text', 'formyar'); ?></label>
                <input type="text" name="config[button_text]" value="<?php echo esc_attr($this->get_config('button_text', __('Choose File', 'formyar'))); ?>" class="fmyar-input-full">
            </div>

            <div class="fmyar-form-group">
                <label class="fmyar-form-label"><?php esc_html_e('Allowed File Types', 'formyar'); ?></label>
                <input type="text" name="config[allowed_types]" value="<?php echo esc_attr($this->get_config('allowed_types', 'jpg,jpeg,png,pdf,doc,docx')); ?>" placeholder="jpg,png,pdf" class="fmyar-input-full" dir="ltr">
                <p class="fmyar-form-hint"><?php esc_html_e('Comma separated file extensions', 'formyar'); ?></p>
            </div>

            <div class="fmyar-form-grid">
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max File Size (MB)', 'formyar'); ?></label>
                    <input type="number" name="config[max_size]" value="<?php echo esc_attr($this->get_config('max_size', 5)); ?>" min="1" max="100" step="0.1" class="fmyar-input-full" dir="ltr">
                </div>
                <div class="fmyar-form-group">
                    <label class="fmyar-form-label"><?php esc_html_e('Max Files', 'formyar'); ?></label>
                    <input type="number" name="config[max_files]" value="<?php echo esc_attr($this->get_config('max_files', 1)); ?>" min="1" max="10" class="fmyar-input-full" dir="ltr">
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
        $description   = esc_html($this->get_config('description'));
        $button_text   = esc_html($this->get_config('button_text', __('Choose File', 'formyar')));
        $allowed_types = esc_attr($this->get_config('allowed_types', 'jpg,jpeg,png,pdf,doc,docx'));
        $max_size      = intval($this->get_config('max_size', 5));
        $max_files     = intval($this->get_config('max_files', 1));
        $required      = $this->get_config('required');

        $html  = '<div class="fmyar-mb-4" data-field-id="' . esc_attr($this->id) . '" data-required="' . ($required ? '1' : '0') . '">';

        // Description
        if ($description) {
            $html .= '<p class="fmyar-form-hint fmyar-mb-2">' . $description . '</p>';
        }

        // Drop Zone
        $html .= '<div class="fmyar-file-drop-zone" ';
        $html .= 'data-allowed-types="' . $allowed_types . '" ';
        $html .= 'data-max-size="' . $max_size . '" ';
        $html .= 'data-max-files="' . $max_files . '">';

        // Upload Icon
        $html .= '<div class="fmyar-file-drop-icon">';
        $html .= '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>';
        $html .= '</svg>';
        $html .= '</div>';

        // Button Text
        $html .= '<p class="fmyar-file-drop-title">' . $button_text . '</p>';

        // Subtitle
        $html .= '<p class="fmyar-file-drop-subtitle">';
        $html .= sprintf(
            /* translators: %1$s: allowed file types, %2$d: max size in MB, %3$d: max number of files */
            esc_html__('Allowed: %1$s | Max: %2$dMB | Max files: %3$d', 'formyar'),
            $allowed_types,
            $max_size,
            $max_files
        );
        $html .= '</p>';

        // Hidden File Input
        $html .= '<input type="file" ';
        $html .= 'name="fmyar_form_data[' . esc_attr($this->id) . ']" ';
        $html .= 'accept=".' . str_replace(',', ',.', $allowed_types) . '" ';
        if ($max_files > 1) {
            $html .= 'multiple ';
        }
        $html .= 'class="fmyar-hidden">';

        $html .= '</div>'; // .fmyar-file-drop-zone

        // File List (previews shown here)
        $html .= '<div class="fmyar-file-list fmyar-mt-2 fmyar-space-y-1"></div>';

        $html .= '</div>'; // .fmyar-mb-4

        return $html;
    }

    protected function validate_answer($answer)
    {
        $label = $this->get_config('label');

        if ($this->get_config('required') && empty($answer)) {
            /* translators: %s: field label */
            return sprintf(__('%s is required.', 'formyar'), $label);
        }

        return true;
    }

    protected function sanitize_answer($answer)
    {
        return $answer;
    }
}
