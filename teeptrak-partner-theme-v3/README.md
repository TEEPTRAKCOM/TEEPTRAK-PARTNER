# TeepTrak Partner Theme V3

A comprehensive WordPress theme for the TeepTrak Partner Portal, enabling channel partners to manage deals, track commissions, access training materials, and collaborate with the TeepTrak team.

## Features

### Core Features
- **Partner Dashboard** - Real-time KPIs, charts, and performance metrics
- **Deal Pipeline Management** - Kanban board with drag-and-drop stage progression
- **Commission Tracking** - Earnings history, forecasts, and payout requests
- **Training Center** - LearnPress integration for partner certification
- **Resource Library** - Sales materials, presentations, and documentation
- **Notification System** - In-app, email, and push notifications

### V3 Enhancements
- **Multilingual Support** - English, French, and Chinese translations
- **Odoo CRM Integration** - Real-time sync with Odoo for deals and contacts
- **PWA Support** - Installable app with offline capabilities
- **White-Label Options** - Customizable branding via WordPress Customizer
- **REST API v2** - Comprehensive API endpoints for external integrations
- **Advanced Charts** - Chart.js powered visualizations

## Requirements

- WordPress 6.0+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+

### Recommended Plugins
- LearnPress (for training features)
- WP Mail SMTP (for email delivery)

## Installation

1. Download the theme ZIP file
2. Go to WordPress Admin → Appearance → Themes
3. Click "Add New" → "Upload Theme"
4. Select the ZIP file and click "Install Now"
5. Activate the theme

### Post-Installation Setup

1. **Configure Customizer Settings**
   - Navigate to Appearance → Customize
   - Set your brand name, logo, and colors
   - Configure Odoo connection if using CRM integration

2. **Create Required Pages**
   Create pages with the following slugs and assign templates:
   - `/dashboard/` - Partner Dashboard template
   - `/deals/` - Partner Deals template
   - `/commissions/` - Partner Commissions template
   - `/training/` - Training Center template
   - `/resources/` - Resource Library template
   - `/profile/` - Partner Profile template
   - `/login/` - Login page
   - `/register/` - Registration page

3. **Set Up User Roles**
   The theme creates a "Partner" user role automatically. Assign this role to partner users.

4. **Configure Email Settings**
   Set up SMTP for reliable email delivery of notifications.

## Configuration

### Customizer Options

Navigate to Appearance → Customize → TeepTrak Settings:

| Setting | Description |
|---------|-------------|
| Brand Name | Your company/product name |
| Logo | Header logo (recommended: 200x50px) |
| Primary Color | Main brand color (default: #E63946) |
| Secondary Color | Secondary brand color (default: #1D3557) |
| Region | Default region for currency/locale |

### Odoo Integration

1. Go to Settings → TeepTrak → Odoo Settings
2. Enter your Odoo instance URL
3. Add database name and API credentials
4. Click "Test Connection" to verify
5. Enable sync options as needed

### Commission Settings

| Setting | Default | Description |
|---------|---------|-------------|
| Minimum Payout | €100 | Minimum amount for payout requests |
| Protection Days | 90 | Deal protection period |
| Auto-Calculate Tier | Yes | Automatically upgrade partner tiers |

## Partner Tiers

| Tier | Commission | Requirements |
|------|------------|--------------|
| Registered | 10% | Entry level |
| Certified | 12% | 3 closed deals + certification |
| Premium | 15% | €100K annual revenue |
| Elite | 20% | €250K annual revenue |

## REST API

The theme provides REST API v2 endpoints at `/wp-json/teeptrak/v2/`:

### Authentication
All endpoints require authentication via WordPress REST API authentication or application passwords.

### Available Endpoints

```
GET    /partner                 - Get current partner profile
PUT    /partner                 - Update partner profile
GET    /partner/stats           - Get partner statistics
GET    /deals                   - List partner deals
POST   /deals                   - Create new deal
GET    /deals/{id}              - Get single deal
PUT    /deals/{id}              - Update deal
PUT    /deals/{id}/stage        - Update deal stage
GET    /commissions             - Get commission data
POST   /commissions/payout      - Request payout
GET    /notifications           - Get notifications
PUT    /notifications/{id}/read - Mark notification as read
```

## Hooks & Filters

### Actions

```php
// After deal is registered
do_action('teeptrak_deal_registered', $deal_id, $user_id);

// After deal stage changes
do_action('teeptrak_deal_stage_changed', $deal_id, $old_stage, $new_stage);

// After commission is earned
do_action('teeptrak_commission_earned', $commission_id, $user_id, $amount);

// After payout is requested
do_action('teeptrak_payout_requested', $payout_id, $user_id, $amount);
```

### Filters

```php
// Modify commission rate
apply_filters('teeptrak_commission_rate', $rate, $tier, $user_id);

// Modify deal protection days
apply_filters('teeptrak_protection_days', $days, $deal_id);

// Customize notification message
apply_filters('teeptrak_notification_message', $message, $type, $data);
```

## File Structure

```
teeptrak-partner-theme-v3/
├── assets/
│   ├── css/
│   ├── js/
│   │   ├── main.js
│   │   ├── charts.js
│   │   ├── deals-kanban.js
│   │   ├── notifications.js
│   │   └── admin.js
│   └── icons/
├── inc/
│   ├── admin/
│   │   ├── admin-dashboard.php
│   │   └── admin-settings.php
│   ├── api/
│   │   ├── rest-api.php
│   │   └── webhooks.php
│   ├── integrations/
│   │   └── odoo-integration.php
│   ├── notifications.php
│   ├── partner-functions.php
│   ├── pwa.php
│   └── template-tags.php
├── languages/
│   └── teeptrak-partner.pot
├── template-parts/
│   ├── portal-header.php
│   ├── portal-footer.php
│   ├── public-header.php
│   ├── public-footer.php
│   └── offline.php
├── front-page.php
├── functions.php
├── header.php
├── footer.php
├── index.php
├── page-dashboard.php
├── page-deals.php
├── page-commissions.php
├── service-worker.js
├── style.css
├── README.md
└── CHANGELOG.md
```

## Translation

The theme is translation-ready with `.pot` file included. To add translations:

1. Copy `languages/teeptrak-partner.pot` to `languages/teeptrak-partner-{locale}.po`
2. Translate strings using Poedit or similar tool
3. Save to generate `.mo` file

Supported locales: `en_US`, `fr_FR`, `zh_CN`

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Support

For support inquiries:
- Email: partners@teeptrak.com
- Documentation: https://docs.teeptrak.com/partner-portal
- GitHub Issues: https://github.com/TEEPTRAKCOM/teeptrak-partner-theme

## License

This theme is proprietary software. All rights reserved by TeepTrak.

## Credits

- Chart.js - https://www.chartjs.org/
- SortableJS - https://sortablejs.github.io/Sortable/
- Feather Icons - https://feathericons.com/
