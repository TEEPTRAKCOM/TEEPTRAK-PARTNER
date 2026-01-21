<?php
/**
 * Template part for benefit card
 *
 * @package TeepTrak_Partner_Theme_2026
 *
 * @var array $args {
 *     @type string $icon  Icon name
 *     @type string $title Card title
 *     @type string $text  Card description
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

$icon = isset($args['icon']) ? $args['icon'] : 'star';
$title = isset($args['title']) ? $args['title'] : '';
$text = isset($args['text']) ? $args['text'] : '';
?>

<div class="tt-benefit-card">
    <div class="tt-benefit-icon">
        <?php echo teeptrak_icon($icon, 32); ?>
    </div>
    <h3 class="tt-benefit-title"><?php echo esc_html($title); ?></h3>
    <p class="tt-benefit-text"><?php echo esc_html($text); ?></p>
</div>
