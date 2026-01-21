<?php
/**
 * Template Name: Dashboard
 *
 * @package TeepTrak_Partner_Theme_2026
 */

get_header();

$partner = teeptrak_get_current_partner();
$deal_stats = teeptrak_get_deal_stats();
$training = teeptrak_get_training_progress();
$onboarding_steps = teeptrak_get_onboarding_steps();
$current_step = $partner['onboarding_step'];
$tier_config = teeptrak_get_tier_config($partner['tier']);
?>

<!-- Page Header -->
<div class="tt-page-header">
    <h1 class="tt-page-title">
        <?php
        printf(
            /* translators: %s: user's first name */
            esc_html__('Welcome back, %s!', 'teeptrak-partner'),
            esc_html($partner['first_name'])
        );
        ?>
    </h1>
    <p class="tt-page-subtitle"><?php esc_html_e("Here's what's happening with your partner account.", 'teeptrak-partner'); ?></p>
</div>

<!-- KPI Cards -->
<div class="tt-kpi-grid tt-mb-8">
    <!-- Partner Score -->
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon" style="background-color: #FEE2E2; color: #E63946;">
            <?php echo teeptrak_icon('activity', 24); ?>
        </div>
        <div class="tt-kpi-value"><?php echo esc_html($partner['partner_score']); ?><span class="tt-text-lg tt-text-gray-400">/100</span></div>
        <div class="tt-kpi-label"><?php esc_html_e('Partner Score', 'teeptrak-partner'); ?></div>
        <div class="tt-mt-2">
            <?php teeptrak_progress_bar($partner['partner_score']); ?>
        </div>
    </div>

    <!-- Active Deals -->
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon" style="background-color: #DBEAFE; color: #3B82F6;">
            <?php echo teeptrak_icon('file-plus', 24); ?>
        </div>
        <div class="tt-kpi-value"><?php echo esc_html($deal_stats['active']); ?></div>
        <div class="tt-kpi-label"><?php esc_html_e('Active Deals', 'teeptrak-partner'); ?></div>
        <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-kpi-link">
            <?php esc_html_e('Register deal', 'teeptrak-partner'); ?> &rarr;
        </a>
    </div>

    <!-- Training Progress -->
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon" style="background-color: #FEF3C7; color: #F59E0B;">
            <?php echo teeptrak_icon('graduation-cap', 24); ?>
        </div>
        <div class="tt-kpi-value"><?php echo esc_html($training['percent']); ?>%</div>
        <div class="tt-kpi-label"><?php esc_html_e('Training Progress', 'teeptrak-partner'); ?></div>
        <div class="tt-text-xs tt-text-gray-500 tt-mt-2">
            <?php
            printf(
                /* translators: 1: completed count, 2: total count */
                esc_html__('%1$d of %2$d modules', 'teeptrak-partner'),
                $training['completed'],
                $training['total']
            );
            ?>
        </div>
    </div>

    <!-- Commission Rate -->
    <div class="tt-kpi-card">
        <div class="tt-kpi-icon" style="background-color: #DCFCE7; color: #22C55E;">
            <?php echo teeptrak_icon('dollar-sign', 24); ?>
        </div>
        <div class="tt-kpi-value"><?php echo esc_html($partner['commission_rate']); ?>%</div>
        <div class="tt-kpi-label"><?php esc_html_e('Commission Rate', 'teeptrak-partner'); ?></div>
        <div class="tt-mt-2">
            <?php teeptrak_tier_badge($partner['tier'], 'sm'); ?>
        </div>
    </div>
</div>

<!-- Onboarding Progress -->
<div class="tt-onboarding tt-mb-8">
    <div class="tt-onboarding-header">
        <h2 class="tt-onboarding-title"><?php esc_html_e('Onboarding Progress', 'teeptrak-partner'); ?></h2>
        <span class="tt-onboarding-progress-text">
            <?php
            printf(
                /* translators: 1: current step, 2: total steps */
                esc_html__('Step %1$d of %2$d', 'teeptrak-partner'),
                $current_step,
                7
            );
            ?>
        </span>
    </div>
    <?php teeptrak_progress_bar(($current_step / 7) * 100, 'tt-progress-lg'); ?>

    <div class="tt-onboarding-steps">
        <?php foreach ($onboarding_steps as $step_num => $step) :
            $status = 'is-pending';
            if ($step_num < $current_step) {
                $status = 'is-completed';
            } elseif ($step_num === $current_step) {
                $status = 'is-current';
            }
        ?>
            <div class="tt-onboarding-step <?php echo esc_attr($status); ?>">
                <div class="tt-onboarding-step-circle <?php echo esc_attr($status); ?>">
                    <?php if ($status === 'is-completed') : ?>
                        <?php echo teeptrak_icon('check', 16); ?>
                    <?php else : ?>
                        <?php echo esc_html($step_num); ?>
                    <?php endif; ?>
                </div>
                <span class="tt-onboarding-step-label"><?php echo esc_html($step['title']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Quick Actions -->
<h2 class="tt-text-xl tt-font-semibold tt-mb-4"><?php esc_html_e('Quick Actions', 'teeptrak-partner'); ?></h2>
<div class="tt-quick-actions">
    <a href="<?php echo esc_url(home_url('/deals/')); ?>" class="tt-quick-action">
        <div class="tt-quick-action-icon" style="background-color: #FEE2E2; color: #E63946;">
            <?php echo teeptrak_icon('file-plus', 24); ?>
        </div>
        <div>
            <div class="tt-quick-action-title"><?php esc_html_e('Register a Deal', 'teeptrak-partner'); ?></div>
            <div class="tt-quick-action-desc"><?php esc_html_e('Get 90-day deal protection', 'teeptrak-partner'); ?></div>
        </div>
    </a>

    <a href="<?php echo esc_url(home_url('/training/')); ?>" class="tt-quick-action">
        <div class="tt-quick-action-icon" style="background-color: #DBEAFE; color: #3B82F6;">
            <?php echo teeptrak_icon('graduation-cap', 24); ?>
        </div>
        <div>
            <div class="tt-quick-action-title"><?php esc_html_e('Continue Training', 'teeptrak-partner'); ?></div>
            <div class="tt-quick-action-desc">
                <?php
                printf(
                    /* translators: 1: completed count, 2: total count */
                    esc_html__('%1$d/%2$d modules completed', 'teeptrak-partner'),
                    $training['completed'],
                    $training['total']
                );
                ?>
            </div>
        </div>
    </a>

    <a href="<?php echo esc_url(home_url('/resources/')); ?>" class="tt-quick-action">
        <div class="tt-quick-action-icon" style="background-color: #DCFCE7; color: #22C55E;">
            <?php echo teeptrak_icon('download', 24); ?>
        </div>
        <div>
            <div class="tt-quick-action-title"><?php esc_html_e('Download Resources', 'teeptrak-partner'); ?></div>
            <div class="tt-quick-action-desc"><?php esc_html_e('Sales materials, case studies', 'teeptrak-partner'); ?></div>
        </div>
    </a>

    <a href="<?php echo esc_url(home_url('/commissions/')); ?>" class="tt-quick-action">
        <div class="tt-quick-action-icon" style="background-color: #FEF3C7; color: #F59E0B;">
            <?php echo teeptrak_icon('dollar-sign', 24); ?>
        </div>
        <div>
            <div class="tt-quick-action-title"><?php esc_html_e('View Commissions', 'teeptrak-partner'); ?></div>
            <div class="tt-quick-action-desc"><?php esc_html_e('Track your earnings', 'teeptrak-partner'); ?></div>
        </div>
    </a>
</div>

<?php get_footer(); ?>
