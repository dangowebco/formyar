<?php
/**
 * Email notification template
 *
 * This is an HTML email template. Inline styles are required because
 * WordPress emails cannot use enqueued stylesheets.
 *
 * @package FormYar
 *
 * @var FormYar_Form $form     Form object
 * @var array        $answers  Submitted answers
 * @var WP_User|null $user     User object or null
 * @var array        $fields   Form fields
 */

defined('ABSPATH') || exit();
?>
<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Tahoma, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #2563EB;
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .body {
            padding: 24px;
        }

        .info {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .info p {
            margin: 4px 0;
            font-size: 13px;
            color: #6b7280;
        }

        .field {
            border-bottom: 1px solid #f3f4f6;
            padding: 12px 0;
        }

        .field:last-child {
            border-bottom: none;
        }

        .field-label {
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .field-value {
            font-size: 14px;
            color: #111827;
        }

        .footer {
            background: #f9fafb;
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><?php
                /* translators: %s: form title */
                echo esc_html(sprintf(__('New Form Submission: %s', 'formyar'), $form->get_title()));
                ?></h2>
            <p><?php echo esc_html(current_time('Y/m/d H:i')); ?></p>
        </div>
        <div class="body">
            <?php if ($user): ?>
                <div class="info">
                    <p><strong><?php esc_html_e('User:', 'formyar'); ?></strong> <?php echo esc_html($user->display_name); ?> (<?php echo esc_html($user->user_email); ?>)</p>
                </div>
            <?php else: ?>
                <div class="info">
                    <p><strong><?php esc_html_e('User:', 'formyar'); ?></strong> <?php esc_html_e('Guest', 'formyar'); ?></p>
                </div>
            <?php endif; ?>
            <?php foreach ($fields as $field):
                $field_id    = $field->get_id();
                $value       = $answers[$field_id] ?? '';
                if ($value === '' || $value === null || (is_array($value) && empty($value))) continue;
                $field_label = $field->get_config('label') ?: __('Untitled', 'formyar');
            ?>
                <div class="field">
                    <div class="field-label"><?php echo esc_html($field_label); ?></div>
                    <div class="field-value">
                        <?php
                        if (is_array($value)) {
                            if (isset($value[0]) && filter_var($value[0], FILTER_VALIDATE_URL)) {
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo implode('<br>', array_map(function ($url) {
                                    return '<a href="' . esc_url($url) . '">' . esc_html(basename($url)) . '</a>';
                                }, $value));
                            } else {
                                echo esc_html(implode(', ', $value));
                            }
                        } else {
                            echo nl2br(esc_html($value));
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="footer">
            <?php
            /* translators: %s: site name */
            echo sprintf(esc_html__('Sent from %s', 'formyar'), esc_html(get_bloginfo('name')));
            ?>
        </div>
    </div>
</body>

</html>