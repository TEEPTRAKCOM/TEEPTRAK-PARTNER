# TeepTrak Partner Portal - Claude Code Deployment Guide

## 🚀 Quick Start with Claude Code

This guide explains how to deploy the TeepTrak Partner Portal to your WordPress installation using Claude Code connected to your GitHub repository.

## 📋 Prerequisites

1. **GitHub Repository**: `TEEPTRAK-PARTNER` (already connected to Claude Code)
2. **WordPress Server**: WordPress 6.0+ with PHP 8.0+
3. **LMS Plugin**: LearnDash 4.x or Tutor LMS Pro
4. **Database**: MySQL 8.x

## 📁 Project Structure

```
teeptrak-partner-portal/
├── theme/
│   └── teeptrak-partner/          # WordPress Theme
│       ├── style.css
│       ├── functions.php
│       ├── header.php
│       ├── footer.php
│       ├── front-page.php         # Landing page
│       ├── page-dashboard.php     # Partner dashboard
│       ├── assets/
│       │   ├── css/
│       │   ├── js/
│       │   └── images/
│       └── inc/
│           ├── customizer.php
│           ├── template-tags.php
│           └── partner-functions.php
│
├── plugin/
│   └── teeptrak-partner-portal/   # WordPress Plugin
│       ├── teeptrak-partner-portal.php
│       ├── includes/
│       ├── admin/
│       └── public/
│
└── docs/
    ├── PROJECT_OVERVIEW.md
    └── CLAUDE_CODE_GUIDE.md
```

## 🔧 Claude Code Commands

### 1. Clone and Setup

```bash
# In Claude Code terminal, navigate to your WordPress installation
cd /path/to/wordpress/wp-content

# Clone the theme
git clone https://github.com/your-org/TEEPTRAK-PARTNER.git

# Copy theme to themes directory
cp -r TEEPTRAK-PARTNER/theme/teeptrak-partner ./themes/

# Copy plugin to plugins directory
cp -r TEEPTRAK-PARTNER/plugin/teeptrak-partner-portal ./plugins/
```

### 2. Activate Theme and Plugin

Via Claude Code (WP-CLI):
```bash
# Activate theme
wp theme activate teeptrak-partner

# Activate plugin
wp plugin activate teeptrak-partner-portal

# Flush rewrite rules
wp rewrite flush
```

### 3. Create Required Pages

```bash
# Create partner portal pages
wp post create --post_type=page --post_title="Dashboard" --post_name="dashboard" --post_status=publish
wp post create --post_type=page --post_title="Academy" --post_name="academy" --post_status=publish
wp post create --post_type=page --post_title="Deal Registration" --post_name="deals" --post_status=publish
wp post create --post_type=page --post_title="Training" --post_name="training" --post_status=publish
wp post create --post_type=page --post_title="Resources" --post_name="resources" --post_status=publish
wp post create --post_type=page --post_title="Commissions" --post_name="commissions" --post_status=publish
wp post create --post_type=page --post_title="Certification Quiz" --post_name="quiz" --post_status=publish
wp post create --post_type=page --post_title="Agreement" --post_name="agreement" --post_status=publish
wp post create --post_type=page --post_title="Schedule Call" --post_name="schedule" --post_status=publish
wp post create --post_type=page --post_title="Become a Partner" --post_name="become-partner" --post_status=publish
```

### 4. Configure LearnDash Integration

```bash
# If using LearnDash, create courses
wp post create --post_type=sfwd-courses --post_title="Introduction to TeepTrak" --post_status=publish
wp post create --post_type=sfwd-courses --post_title="OEE Fundamentals" --post_status=publish
wp post create --post_type=sfwd-courses --post_title="Product Features & Benefits" --post_status=publish
wp post create --post_type=sfwd-courses --post_title="Sales Methodology" --post_status=publish
wp post create --post_type=sfwd-courses --post_title="Technical Implementation" --post_status=publish
```

## 🔄 GitHub Workflow

### Push Updates

```bash
# Stage changes
git add .

# Commit
git commit -m "feat: Add partner dashboard functionality"

# Push to repository
git push origin main
```

### Pull Latest Changes

```bash
# Pull latest from repository
git pull origin main

# Copy updated files to WordPress
rsync -av --exclude='.git' ./ /path/to/wordpress/wp-content/themes/teeptrak-partner/
```

## ⚙️ Configuration

### Environment Variables (wp-config.php)

```php
// TeepTrak Partner Portal Settings
define('TEEPTRAK_ENV', 'production'); // or 'development'
define('TEEPTRAK_DEBUG', false);
define('TEEPTRAK_ACADEMY_URL', 'https://academy.teeptrak.net');
define('TEEPTRAK_API_KEY', 'your-api-key-here');
```

### Multilingual Setup (WPML/Polylang)

```bash
# Install WPML or Polylang
wp plugin install polylang --activate

# Configure languages
wp pll lang create English en_US
wp pll lang create Français fr_FR
wp pll lang create Chinese zh_CN
```

## 🗄️ Database Setup

The plugin automatically creates these tables on activation:

- `wp_teeptrak_partners` - Partner profiles
- `wp_teeptrak_deals` - Deal registration
- `wp_teeptrak_commissions` - Commission tracking
- `wp_teeptrak_resources` - Resource library
- `wp_teeptrak_badges` - Partner badges
- `wp_teeptrak_scheduled_calls` - PSM meetings
- `wp_teeptrak_activity_log` - Audit log
- `wp_teeptrak_notifications` - Notifications

To manually run migrations:

```bash
wp eval 'teeptrak_portal()->activate();'
```

## 🔐 Security Configuration

### Add to wp-config.php:

```php
// Security headers
define('TEEPTRAK_SECURE_COOKIES', true);

// API Rate limiting
define('TEEPTRAK_API_RATE_LIMIT', 100); // requests per hour
```

### Configure .htaccess:

```apache
# Protect plugin files
<FilesMatch "^(teeptrak-partner-portal\.php)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
</FilesMatch>
```

## 📊 Testing

### Run Tests with Claude Code

```bash
# PHPUnit tests
./vendor/bin/phpunit --testsuite=teeptrak

# Check coding standards
./vendor/bin/phpcs --standard=WordPress ./theme/teeptrak-partner/

# JavaScript tests
npm test
```

## 🚀 Deployment Checklist

- [ ] WordPress 6.0+ installed
- [ ] PHP 8.0+ configured
- [ ] MySQL 8.x database ready
- [ ] SSL certificate installed
- [ ] Theme uploaded to `/themes/teeptrak-partner/`
- [ ] Plugin uploaded to `/plugins/teeptrak-partner-portal/`
- [ ] Theme activated
- [ ] Plugin activated
- [ ] Database tables created
- [ ] Pages created
- [ ] LearnDash/LMS configured
- [ ] WPML/Polylang configured
- [ ] Email configuration tested
- [ ] Cron jobs verified
- [ ] Security headers configured
- [ ] Backup system configured
- [ ] Monitoring setup

## 📝 Common Claude Code Tasks

### Create New Partner (Admin)

```bash
wp user create john.smith john@example.com --role=partner --display_name="John Smith"
wp eval '
    teeptrak_create_partner(
        get_user_by("email", "john@example.com")->ID,
        array(
            "company_name" => "Smith Industries",
            "company_country" => "US"
        )
    );
'
```

### Update Partner Tier

```bash
wp eval 'teeptrak_update_partner_tier(1);' # Partner ID = 1
```

### Clear Notifications

```bash
wp eval '
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->prefix}teeptrak_notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
'
```

### Export Partners Report

```bash
wp db query "SELECT p.company_name, u.user_email, p.tier, p.partner_score 
FROM wp_teeptrak_partners p 
JOIN wp_users u ON p.user_id = u.ID 
ORDER BY p.partner_score DESC" --allow-root > partners_report.csv
```

## 🔗 Useful Links

- [TeepTrak Documentation](https://docs.teeptrak.com)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)
- [LearnDash Documentation](https://www.learndash.com/support/docs/)
- [WP-CLI Commands](https://developer.wordpress.org/cli/commands/)

## 📞 Support

For technical support:
- Email: tech@teeptrak.com
- Slack: #partner-portal-dev
