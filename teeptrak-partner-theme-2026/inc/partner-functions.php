<?php
/**
 * Partner Functions for TeepTrak Partner Theme
 *
 * @package TeepTrak_Partner_Theme_2026
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get deal stats for current partner
 */
function teeptrak_get_deal_stats($user_id = null) {
    $deals = teeptrak_get_partner_deals($user_id);

    $stats = array(
        'total'          => count($deals),
        'active'         => 0,
        'pipeline_value' => 0,
        'closed_won'     => 0,
        'closed_lost'    => 0,
    );

    foreach ($deals as $deal) {
        $stage = isset($deal['stage']) ? $deal['stage'] : 'registered';
        $value = isset($deal['deal_value']) ? (float) $deal['deal_value'] : 0;

        if ($stage === 'closed_won') {
            $stats['closed_won']++;
        } elseif ($stage === 'closed_lost') {
            $stats['closed_lost']++;
        } else {
            $stats['active']++;
            $stats['pipeline_value'] += $value;
        }
    }

    return $stats;
}

/**
 * Update deal stage
 */
function teeptrak_update_deal_stage($deal_id, $new_stage, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $deals = teeptrak_get_partner_deals($user_id);
    $updated = false;

    foreach ($deals as $key => $deal) {
        if ($deal['id'] === $deal_id) {
            $deals[$key]['stage'] = $new_stage;
            $deals[$key]['updated_at'] = current_time('Y-m-d H:i:s');
            $updated = true;
            break;
        }
    }

    if ($updated) {
        update_user_meta($user_id, 'teeptrak_deals', $deals);
    }

    return $updated;
}

/**
 * Get commission rate for tier
 */
function teeptrak_get_commission_rate($tier) {
    $rates = array(
        'bronze'   => 15,
        'silver'   => 20,
        'gold'     => 25,
        'platinum' => 30,
    );

    return isset($rates[$tier]) ? $rates[$tier] : 15;
}

/**
 * Check if partner can access tier-gated content
 */
function teeptrak_can_access_tier_content($required_tier, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return false;
    }

    $partner = teeptrak_get_current_partner();
    $user_tier = $partner['tier'];

    return teeptrak_tier_has_access($user_tier, $required_tier);
}

/**
 * Get partner's completed training modules
 */
function teeptrak_get_completed_modules($user_id = null) {
    $progress = teeptrak_get_training_progress($user_id);
    $completed = array();

    foreach ($progress['modules'] as $module_id => $percent) {
        if ($percent >= 100) {
            $completed[] = $module_id;
        }
    }

    return $completed;
}

/**
 * Check if module is unlocked
 */
function teeptrak_is_module_unlocked($module, $user_id = null) {
    if (!isset($module['prerequisite'])) {
        return true;
    }

    $completed = teeptrak_get_completed_modules($user_id);
    $prerequisite_level = $module['prerequisite'];

    // Check if all modules of the prerequisite level are completed
    $modules = teeptrak_get_training_modules();
    foreach ($modules as $m) {
        if ($m['cert_level'] === $prerequisite_level) {
            if (!in_array($m['id'], $completed)) {
                return false;
            }
        }
    }

    return true;
}

/**
 * Get module status
 */
function teeptrak_get_module_status($module_id, $user_id = null) {
    $progress = teeptrak_get_training_progress($user_id);
    $modules = $progress['modules'];

    $percent = isset($modules[$module_id]) ? $modules[$module_id] : 0;

    if ($percent >= 100) {
        return 'completed';
    } elseif ($percent > 0) {
        return 'in_progress';
    }

    // Check if locked
    $all_modules = teeptrak_get_training_modules();
    foreach ($all_modules as $m) {
        if ($m['id'] === $module_id) {
            if (!teeptrak_is_module_unlocked($m, $user_id)) {
                return 'locked';
            }
            break;
        }
    }

    return 'not_started';
}

/**
 * Add commission for partner
 */
function teeptrak_add_commission($user_id, $deal_id, $amount, $description = '') {
    $transactions = get_user_meta($user_id, 'teeptrak_transactions', true);
    if (!is_array($transactions)) {
        $transactions = array();
    }

    $transaction = array(
        'id'          => 'txn_' . uniqid(),
        'date'        => current_time('Y-m-d'),
        'type'        => 'commission',
        'deal_id'     => $deal_id,
        'description' => $description,
        'amount'      => $amount,
        'status'      => 'pending',
    );

    $transactions[] = $transaction;
    update_user_meta($user_id, 'teeptrak_transactions', $transactions);

    // Update pending commissions total
    $pending = (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true);
    update_user_meta($user_id, 'teeptrak_pending_commissions', $pending + $amount);

    return $transaction;
}

/**
 * Approve commission (move from pending to available)
 */
function teeptrak_approve_commission($user_id, $transaction_id) {
    $transactions = get_user_meta($user_id, 'teeptrak_transactions', true);
    if (!is_array($transactions)) {
        return false;
    }

    $amount = 0;
    foreach ($transactions as $key => $txn) {
        if ($txn['id'] === $transaction_id && $txn['status'] === 'pending') {
            $transactions[$key]['status'] = 'approved';
            $amount = $txn['amount'];
            break;
        }
    }

    if ($amount > 0) {
        update_user_meta($user_id, 'teeptrak_transactions', $transactions);

        // Update balances
        $pending = (float) get_user_meta($user_id, 'teeptrak_pending_commissions', true);
        $available = (float) get_user_meta($user_id, 'teeptrak_available_balance', true);

        update_user_meta($user_id, 'teeptrak_pending_commissions', max(0, $pending - $amount));
        update_user_meta($user_id, 'teeptrak_available_balance', $available + $amount);

        return true;
    }

    return false;
}

/**
 * Update partner score
 */
function teeptrak_update_partner_score($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $score = 0;

    // Training progress (40% weight)
    $training = teeptrak_get_training_progress($user_id);
    $score += ($training['percent'] / 100) * 40;

    // Deal activity (30% weight)
    $deals = teeptrak_get_partner_deals($user_id);
    $deal_score = min(30, count($deals) * 5);
    $score += $deal_score;

    // Onboarding completion (20% weight)
    $onboarding_step = (int) get_user_meta($user_id, 'teeptrak_onboarding_step', true);
    $score += ($onboarding_step / 7) * 20;

    // Commission activity (10% weight)
    $total_paid = (float) get_user_meta($user_id, 'teeptrak_total_paid', true);
    $commission_score = min(10, ($total_paid / 10000) * 10);
    $score += $commission_score;

    $score = round(min(100, max(0, $score)));
    update_user_meta($user_id, 'teeptrak_partner_score', $score);

    return $score;
}

/**
 * Check and update partner tier based on performance
 */
function teeptrak_check_tier_upgrade($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    $deals = teeptrak_get_partner_deals($user_id);
    $deal_stats = teeptrak_get_deal_stats($user_id);
    $training = teeptrak_get_training_progress($user_id);

    $current_tier = get_user_meta($user_id, 'teeptrak_partner_tier', true) ?: 'bronze';
    $new_tier = 'bronze';

    // Platinum: 10+ deals, €250K+ pipeline
    if ($deal_stats['total'] >= 10 && $deal_stats['pipeline_value'] >= 250000) {
        $new_tier = 'platinum';
    }
    // Gold: 5+ deals, €100K+ pipeline
    elseif ($deal_stats['total'] >= 5 && $deal_stats['pipeline_value'] >= 100000) {
        $new_tier = 'gold';
    }
    // Silver: 2+ closed deals
    elseif ($deal_stats['closed_won'] >= 2) {
        $new_tier = 'silver';
    }

    if ($new_tier !== $current_tier) {
        update_user_meta($user_id, 'teeptrak_partner_tier', $new_tier);
        $config = teeptrak_get_tier_config($new_tier);
        update_user_meta($user_id, 'teeptrak_commission_rate', $config['commission_rate']);
    }

    return $new_tier;
}
