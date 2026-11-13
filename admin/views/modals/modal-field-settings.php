<?php

/**
 * Modal: Field Settings
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$field_type = $GLOBALS['fmyar_current_field_type'] ?? '';
$field_data = $GLOBALS['fmyar_current_field_data'] ?? null;

if (empty($field_type)) {
    echo '<p class="fmyar-text-danger">' . esc_html__('Field type not specified.', 'formyar') . '</p>';
    return;
}

$field = fmyar_create_field($field_type);

if (!$field) {
    echo '<p class="fmyar-text-danger">' . esc_html__('Invalid field type.', 'formyar') . '</p>';
    return;
}

if ($field_data && isset($field_data['config'])) {
    foreach ($field_data['config'] as $key => $value) {
        $field->set_config($key, $value);
    }
}
?>

<form id="fmyar-field-settings-form" class="fmyar-modal-form">
    <?php $field->render_settings(); ?>

    <?php
    /**
     * Action: Add Pro field settings.
     *
     * Allows Pro to add custom settings after the default field settings.
     *
     * @since 1.1.0
     * @param FormYar_Form_Field $field Current field object.
     */
    do_action('fmyar_field_settings', $field);
    ?>

    <input type="hidden" name="field_type" value="<?php echo esc_attr($field_type); ?>">
    <?php if ($field_data): ?>
        <input type="hidden" name="field_id" value="<?php echo esc_attr($field_data['id'] ?? ''); ?>">
        <input type="hidden" name="field_order" value="<?php echo esc_attr($field_data['order'] ?? '0'); ?>">
    <?php endif; ?>
</form>