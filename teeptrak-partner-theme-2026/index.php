<?php
/**
 * The main template file
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

// Redirect to dashboard if logged in, otherwise show landing page
if (is_user_logged_in()) {
    $dashboard_url = home_url('/dashboard/');
    if (!is_page('dashboard')) {
        wp_redirect($dashboard_url);
        exit;
    }
}
?>

<div class="tt-page-content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('tt-card'); ?>>
                <div class="tt-card-body">
                    <h1 class="tt-text-2xl tt-font-bold tt-mb-4"><?php the_title(); ?></h1>
                    <div class="tt-prose">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="tt-empty-state">
            <div class="tt-empty-icon">
                <?php echo teeptrak_icon('file', 80); ?>
            </div>
            <h3 class="tt-empty-title"><?php esc_html_e('No content found', 'teeptrak-partner'); ?></h3>
            <p class="tt-empty-text"><?php esc_html_e('Sorry, no content was found.', 'teeptrak-partner'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
