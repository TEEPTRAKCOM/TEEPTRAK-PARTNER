<?php
/**
 * Theme Header
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if (teeptrak_is_portal_page()) : ?>
    <?php get_template_part('template-parts/portal', 'header'); ?>
<?php else : ?>
    <?php get_template_part('template-parts/public', 'header'); ?>
<?php endif; ?>
