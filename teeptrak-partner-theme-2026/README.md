# TeepTrak Partner Theme 2026

A modern WordPress theme for the TeepTrak Partner Portal, featuring deal registration, training management, resource library, and commission tracking.

## Features

- **Partner Dashboard**: Overview of deals, training progress, and earnings
- **Deal Registration**: Register and track deals with 90-day protection period
- **Training Center**: Training modules with LearnPress LMS integration
- **Resource Library**: Tier-gated sales materials and marketing assets
- **Commission Tracking**: View earnings, transactions, and request withdrawals
- **Tier System**: Bronze, Silver, Gold, and Platinum partner levels
- **Responsive Design**: Mobile-first, works on all devices

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Optional: LearnPress plugin for LMS functionality

## Installation

1. Download the theme ZIP file
2. In WordPress admin, go to **Appearance > Themes > Add New > Upload Theme**
3. Select the ZIP file and click **Install Now**
4. Click **Activate** after installation

## Setup

### 1. Create Required Pages

Create the following pages and assign their templates via Page Attributes:

| Page Title | Slug | Template |
|------------|------|----------|
| Dashboard | dashboard | Dashboard |
| Deals | deals | Deal Registration |
| Training | training | Training |
| Resources | resources | Resources |
| Commissions | commissions | Commissions |

### 2. Configure WordPress Settings

1. Go to **Settings > Reading**
2. Set "Your homepage displays" to "A static page"
3. Select the home page (Front Page will use the landing page template automatically)

### 3. Create Navigation Menu

1. Go to **Appearance > Menus**
2. Create a menu named "Primary Menu"
3. Add your main pages
4. Set display location to "Primary Menu"

### 4. LearnPress Integration (Optional)

If using LearnPress for training:

1. Install and activate LearnPress plugin
2. Create courses as normal
3. Link courses to TeepTrak modules using the `_teeptrak_module_id` custom field
4. Mark certification courses with `_teeptrak_is_certification` = 1

### 5. Demo Data

The theme includes demo data for development/testing. Demo data is automatically created when:
- A user logs in
- The demo data hasn't been set up yet

To manually set up demo data for a user:
```php
teeptrak_setup_demo_user($user_id);
```

## Theme Structure

```
teeptrak-partner-theme-2026/
├── assets/
│   ├── css/
│   │   └── (additional styles if needed)
│   ├── images/
│   │   └── (theme images)
│   └── js/
│       └── main.js
├── inc/
│   ├── learnpress-integration.php
│   ├── partner-functions.php
│   └── template-tags.php
├── template-parts/
│   ├── card-benefit.php
│   ├── card-deal.php
│   ├── card-module.php
│   ├── card-resource.php
│   └── card-tier.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page-commissions.php
├── page-dashboard.php
├── page-deals.php
├── page-resources.php
├── page-training.php
├── README.md
├── screenshot.png
└── style.css
```

## User Meta Fields

The theme stores partner data in user meta:

| Meta Key | Description |
|----------|-------------|
| `teeptrak_partner_tier` | Partner tier (bronze, silver, gold, platinum) |
| `teeptrak_partner_score` | Partner score (0-100) |
| `teeptrak_commission_rate` | Commission rate percentage |
| `teeptrak_deals` | Array of registered deals |
| `teeptrak_transactions` | Array of commission transactions |
| `teeptrak_available_balance` | Available balance for withdrawal |
| `teeptrak_pending_commissions` | Pending commission total |
| `teeptrak_total_paid` | Total commissions paid out |
| `teeptrak_onboarding_step` | Current onboarding step (1-7) |
| `teeptrak_training_progress` | Training module progress |

## REST API Endpoints

The theme provides REST API endpoints:

- `GET /wp-json/teeptrak/v1/partner` - Get current partner data
- `GET /wp-json/teeptrak/v1/deals` - Get partner's deals
- `POST /wp-json/teeptrak/v1/deals` - Register new deal
- `GET /wp-json/teeptrak/v1/stats` - Get partner statistics

## AJAX Actions

- `teeptrak_register_deal` - Register a new deal

## CSS Classes

All CSS classes use the `tt-` prefix. Key classes:

- `.tt-btn` - Buttons (with modifiers: `-primary`, `-secondary`, `-sm`, `-lg`)
- `.tt-card` - Card container
- `.tt-badge` - Status badges
- `.tt-form-control` - Form inputs
- `.tt-table` - Data tables

## Tier System

| Tier | Commission Rate | Requirements |
|------|-----------------|--------------|
| Bronze | 15% | New partners |
| Silver | 20% | 2+ closed deals |
| Gold | 25% | 5+ deals, €100K+ pipeline |
| Platinum | 30% | 10+ deals, €250K+ pipeline |

## Deal Stages

1. Registered
2. Qualified
3. Demo Scheduled
4. Proposal Sent
5. Negotiation
6. Closed Won / Closed Lost

## Customization

### Colors

Main colors are defined as CSS variables in `style.css`:

```css
:root {
    --tt-red: #E63946;
    --tt-dark: #1D3557;
    --tt-white: #FFFFFF;
    --tt-gray-50: #F8FAFC;
    /* ... more colors */
}
```

### Icons

SVG icons are output via the `teeptrak_icon()` function:

```php
<?php echo teeptrak_icon('check', 16); ?>
```

Available icons: check, x, arrow-right, briefcase, graduation-cap, folder, dollar-sign, chart-line, users, star, shield, clock, building, user, mail, phone, globe, download, lock, play, file, file-pdf, file-video, file-image

## Internationalization

The theme is translation-ready with text domain `teeptrak-partner`. All user-facing strings use translation functions.

## Security

- All user inputs are sanitized
- Nonces used for AJAX/form submissions
- Capability checks for user actions
- Output escaped with appropriate functions

## Support

For support, contact TeepTrak at partners@teeptrak.com

## License

This theme is proprietary software. All rights reserved.

## Changelog

### 1.0.0 (2026)
- Initial release
- Partner dashboard with KPIs
- Deal registration system
- Training center with LearnPress integration
- Resource library with tier-gating
- Commission tracking
- Tier-based partner system
