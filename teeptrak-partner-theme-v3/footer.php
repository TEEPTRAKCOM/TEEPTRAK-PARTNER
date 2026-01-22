<?php
/**
 * Theme Footer
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php if (teeptrak_is_portal_page()) : ?>
    <?php get_template_part('template-parts/portal', 'footer'); ?>
<?php else : ?>
    <?php get_template_part('template-parts/public', 'footer'); ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
