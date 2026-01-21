# TeepTrak Partner Portal - WordPress Import Files

This folder contains all the files you need to set up the TeepTrak Partner Portal on your WordPress installation.

## Files Included

| File | Description | Size |
|------|-------------|------|
| `teeptrak-partner-theme.zip` | WordPress theme | ~30 KB |
| `teeptrak-partner-portal-plugin.zip` | WordPress plugin | ~10 KB |
| `teeptrak-partner-demo-content.xml` | Demo pages and content (WXR format) | ~15 KB |
| `teeptrak-sample-data.sql` | Sample partners, deals, commissions data | ~10 KB |

---

## Installation Steps

### Step 1: Install the Plugin (REQUIRED - Do This First!)

1. Go to **WordPress Admin > Plugins > Add New**
2. Click **Upload Plugin**
3. Select `teeptrak-partner-portal-plugin.zip`
4. Click **Install Now**
5. Click **Activate Plugin**

The plugin will automatically create the required database tables on activation.

### Step 2: Install the Theme

1. Go to **WordPress Admin > Appearance > Themes**
2. Click **Add New > Upload Theme**
3. Select `teeptrak-partner-theme.zip`
4. Click **Install Now**
5. Click **Activate**

### Step 3: Import Demo Content (Optional)

1. Go to **WordPress Admin > Tools > Import**
2. Click **WordPress** (install the importer if prompted)
3. Select `teeptrak-partner-demo-content.xml`
4. Click **Upload file and import**
5. Assign posts to an existing user (admin recommended)
6. Check **Download and import file attachments** if you want

This creates the following pages:
- Partner Portal Home
- Partner Dashboard
- Deal Registration
- Commissions
- Resource Center
- Training & Certification
- Partner Registration
- My Profile
- Partner Support

### Step 4: Import Sample Data (Optional)

To populate the database with sample partners, deals, and commissions:

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select your WordPress database
3. Go to **Import** tab
4. Select `teeptrak-sample-data.sql`
5. Click **Go**

**Option B: Using WP-CLI**
```bash
wp db import teeptrak-sample-data.sql
```

**Option C: Using MySQL Command Line**
```bash
mysql -u username -p database_name < teeptrak-sample-data.sql
```

> **Note:** The SQL file assumes WordPress table prefix is `wp_`. If your prefix is different, edit the SQL file and replace `wp_` with your prefix.

---

## After Installation

### Set Up Homepage

1. Go to **Settings > Reading**
2. Select **A static page**
3. Set **Homepage** to "Partner Portal Home"
4. Save changes

### Configure Menus

1. Go to **Appearance > Menus**
2. Create a new menu called "Partner Portal Menu"
3. Add the imported pages
4. Assign to "Primary Menu" location
5. Save

### Create User Roles

The plugin creates two custom roles:
- **Partner** - Standard partner access
- **PSM (Partner Success Manager)** - Admin-level partner management

To assign roles:
1. Go to **Users > All Users**
2. Edit a user
3. Change role to "Partner" or "PSM"

---

## Requirements

- WordPress 6.0+
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+

### Recommended Plugins

- **LearnDash** or **Tutor LMS Pro** - For training/certification features
- **WooCommerce** (optional) - For payment processing

---

## Shortcodes Reference

The plugin provides these shortcodes for use in pages:

| Shortcode | Description |
|-----------|-------------|
| `[teeptrak_dashboard]` | Full partner dashboard |
| `[teeptrak_deal_list]` | List of partner's deals |
| `[teeptrak_deal_form]` | New deal registration form |
| `[teeptrak_commission_summary]` | Commission summary cards |
| `[teeptrak_commission_list]` | List of commissions |
| `[teeptrak_resources category="sales"]` | Resource library (filter by category) |
| `[teeptrak_badges]` | Partner's earned badges |
| `[teeptrak_courses]` | Available training courses |
| `[teeptrak_partner_profile]` | Partner profile editor |
| `[teeptrak_partner_registration]` | New partner registration form |
| `[teeptrak_psm_contact]` | Contact PSM widget |
| `[teeptrak_schedule_call]` | Schedule call with PSM |

---

## Troubleshooting

### Tables not created
Deactivate and reactivate the plugin to trigger table creation.

### Styles not loading
1. Check theme is activated
2. Clear any caching plugins
3. Visit **Appearance > Customize** and save

### Demo content not showing
1. Check pages are published (not drafts)
2. Clear cache
3. Check permalinks: **Settings > Permalinks > Save**

---

## Support

For questions or issues:
- Check `/docs/CLAUDE_CODE_GUIDE.md` for deployment help
- Review `/docs/PROJECT_OVERVIEW.md` for architecture details

---

## File Structure After Installation

```
wp-content/
├── themes/
│   └── teeptrak-partner/          # Theme files
│       ├── style.css
│       ├── functions.php
│       ├── header.php
│       ├── footer.php
│       ├── front-page.php
│       ├── page-dashboard.php
│       ├── inc/
│       └── assets/
└── plugins/
    └── teeptrak-partner-portal/   # Plugin files
        └── teeptrak-partner-portal.php
```

## Database Tables Created

The plugin creates these tables:
- `wp_teeptrak_partners` - Partner profiles
- `wp_teeptrak_deals` - Deal registrations
- `wp_teeptrak_commissions` - Commission tracking
- `wp_teeptrak_resources` - Resource library
- `wp_teeptrak_badges` - Certifications/badges
- `wp_teeptrak_scheduled_calls` - PSM meetings
- `wp_teeptrak_activity_log` - Audit trail
- `wp_teeptrak_notifications` - Notifications
