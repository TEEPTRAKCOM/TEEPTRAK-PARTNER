<?php
/**
 * Main Index Template
 *
 * @package TeepTrak_Partner_Theme_V3
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="tt-container tt-py-12">
    <?php if (have_posts()) : ?>
        <div class="tt-posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('tt-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="tt-card-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="tt-card-body">
                        <h2 class="tt-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="tt-card-meta">
                            <span><?php echo get_the_date(); ?></span>
                        </div>
                        <div class="tt-card-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="tt-btn tt-btn-secondary tt-btn-sm">
                            <?php esc_html_e('Read More', 'teeptrak-partner'); ?>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(array(
            'prev_text' => teeptrak_icon('chevron-left', 16) . ' ' . __('Previous', 'teeptrak-partner'),
            'next_text' => __('Next', 'teeptrak-partner') . ' ' . teeptrak_icon('chevron-right', 16),
        )); ?>

    <?php else : ?>
        <div class="tt-empty-state">
            <?php echo teeptrak_icon('file-text', 48); ?>
            <h3><?php esc_html_e('No posts found', 'teeptrak-partner'); ?></h3>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
