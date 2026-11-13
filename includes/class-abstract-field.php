<?php

/**
 * Abstract Field - base class for all form field types
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

abstract class FormYar_Form_Field
{
    protected $id;
    protected $type;
    protected $order;
    protected $config = [];

    public function __construct($id = null, $config = [], $order = 0)
    {
        $this->id     = $id ?: $this->generate_id();
        $this->config = wp_parse_args($config, $this->get_default_config());
        $this->order  = $order;
    }

    protected function generate_id()
    {
        return 'fmyar_' . time() . '_' . substr(md5(uniqid()), 0, 8);
    }

    abstract public function get_type();
    abstract public function get_label();
    abstract public function get_icon();
    abstract public function get_default_config();
    abstract public function render_settings();
    /**
     * Render the field HTML for the frontend.
     *
     * @since 1.0.0
     * @return string Field HTML.
     */
    abstract protected function render_frontend_html();

    /**
     * Get the field HTML for the frontend with filter support.
     *
     * @since 1.1.0
     * @return string Filtered field HTML.
     */
    public function render_frontend()
    {
        $html = $this->render_frontend_html();

        /**
         * Filter the frontend field HTML.
         *
         * Allows Pro to modify or extend field rendering.
         *
         * @since 1.1.0
         * @param string             $html  Field HTML.
         * @param FormYar_Form_Field $field Current field object.
         */
        return apply_filters('fmyar_field_frontend', $html, $this);
    }
    abstract protected function validate_answer($answer);

    /**
     * Validate the field answer with filter support.
     *
     * @since 1.1.0
     * @param mixed $answer The answer to validate.
     * @return bool|string True if valid, error message if not.
     */
    public function validate($answer)
    {
        $result = $this->validate_answer($answer);

        /**
         * Filter: Validate field answer.
         *
         * Allows Pro to add custom validation.
         *
         * @since 1.1.0
         * @param bool|string        $result Validation result.
         * @param mixed              $answer The answer.
         * @param FormYar_Form_Field $field  Current field.
         */
        return apply_filters('fmyar_field_validate', $result, $answer, $this);
    }

    abstract protected function sanitize_answer($answer);

    /**
     * Sanitize the field answer with filter support.
     *
     * @since 1.1.0
     * @param mixed $answer The answer to sanitize.
     * @return mixed Sanitized answer.
     */
    public function sanitize($answer)
    {
        $sanitized = $this->sanitize_answer($answer);

        /**
         * Filter: Sanitize field answer.
         *
         * Allows Pro to add custom sanitization.
         *
         * @since 1.1.0
         * @param mixed              $sanitized Sanitized value.
         * @param mixed              $answer    Original answer.
         * @param FormYar_Form_Field $field     Current field.
         */
        return apply_filters('fmyar_field_sanitize', $sanitized, $answer, $this);
    }

    public function to_array()
    {
        return [
            'id'     => $this->id,
            'type'   => $this->get_type(),
            'order'  => $this->order,
            'config' => $this->config,
        ];
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_order()
    {
        return $this->order;
    }

    public function set_order($order)
    {
        $this->order = $order;
    }

    public function get_config($key, $default = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    public function set_config($key, $value)
    {
        $this->config[$key] = $value;

        /**
         * Action: Field config set.
         *
         * Allows Pro to track configuration changes.
         *
         * @since 1.1.0
         * @param string             $key   Config key.
         * @param mixed              $value Config value.
         * @param FormYar_Form_Field $field Current field.
         */
        do_action('fmyar_field_config_set', $key, $value, $this);
    }
}
