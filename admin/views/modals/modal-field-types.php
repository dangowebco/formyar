<?php

/**
 * Modal: Select Field Type
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$field_types = fmyar_get_field_types();
?>

<p class="fmyar-card-description fmyar-mb-4"><?php esc_html_e('Choose the type of field you want to add to your form.', 'formyar'); ?></p>

<?php if (!empty($field_types)): ?>
    <div class="fmyar-field-types-grid">
        <?php foreach ($field_types as $type => $config): ?>
            <button type="button" class="fmyar-field-type-option" data-field-type="<?php echo esc_attr($type); ?>">
                <div class="fmyar-field-type-icon">
                    <span class="dashicons <?php echo esc_attr($config['icon']); ?>"></span>
                </div>
                <div class="fmyar-field-type-label"><?php echo esc_html($config['label']); ?></div>
                <div class="fmyar-field-type-desc"><?php echo esc_html($config['description'] ?? ''); ?></div>
            </button>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="fmyar-text-center fmyar-text-gray-500 fmyar-py-8"><?php esc_html_e('No field types available.', 'formyar'); ?></p>
<?php endif; ?>