<?php

/**
 * Form Builder UI Template
 *
 * @package FormYar
 */

defined('ABSPATH') || exit();

$fields      = $this->get_fields();
$field_types = fmyar_get_field_types();
?>

<div class="fmyar-form-builder"
    data-entity-type="<?php echo esc_attr($this->entity_type); ?>"
    data-entity-id="<?php echo esc_attr($this->entity_id); ?>"
    data-table="<?php echo esc_attr($this->table_name); ?>"
    data-id-column="<?php echo esc_attr($this->id_column); ?>">

    <?php if (!empty($fields)): ?>
        <div id="fmyar-fields-list" class="fmyar-sortable-fields">
            <?php foreach ($fields as $field): ?>
                <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $this->render_field_item($field);
                ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>