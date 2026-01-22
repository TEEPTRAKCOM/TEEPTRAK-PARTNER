# Changelog

All notable changes to the TeepTrak Partner Theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2026-01-22

### Added

#### Multilingual Support
- Full internationalization (i18n) with `__()`, `_e()`, and `esc_html__()` functions
- Language switcher component in header
- Support for English (en_US), French (fr_FR), and Chinese (zh_CN)
- POT file template for translations

#### Odoo CRM Integration
- Real-time deal synchronization with Odoo CRM
- Partner profile sync
- Webhook handlers for external events
- Stage mapping between WordPress and Odoo
- Bulk sync functionality with cron scheduling
- Connection testing in admin settings

#### Enhanced Dashboard
- Chart.js powered visualizations
- Pipeline distribution doughnut chart
- Commission trend line chart
- Performance bar charts
- Sparkline mini-charts for KPI cards
- Real-time data via AJAX

#### Deals Kanban View
- SortableJS drag-and-drop interface
- Visual deal cards with protection indicators
- Stage columns with value totals
- Quick filters and search
- List view toggle option
- Deal modal for view/edit

#### Advanced Commission Tracking
- Commission history table
- Forecast by stage and time period
- Payout request system
- Multiple payment methods (bank transfer, PayPal)
- Status tracking (pending, approved, paid)
- Minimum payout threshold

#### Notification System
- In-app notification bell with dropdown
- Real-time polling for new notifications
- Email notifications on key events
- Push notification support (PWA)
- Mark as read functionality
- Notification preferences

#### PWA Support
- Web app manifest for installability
- Service worker for offline caching
- Offline fallback page
- Background sync for deal submissions
- Push notification capability
- App shortcuts

#### REST API v2
- Comprehensive `/teeptrak/v2/` endpoints
- Partner profile CRUD
- Deal management endpoints
- Commission and payout endpoints
- Notification management
- Chart data endpoints
- Proper authentication and authorization

#### Admin Dashboard
- Partner management interface
- Deal pipeline overview
- Commission and payout management
- Training reports
- Odoo sync controls
- Webhook log viewer

#### White-Label Options
- Brand name customization
- Logo upload
- Primary/secondary color pickers
- Region selection
- Footer customization

### Changed
- Complete theme rewrite with modern architecture
- CSS custom properties for theming
- Modular JavaScript structure
- Improved responsive design
- Enhanced security with nonce verification
- Better code organization with `/inc/` directory structure

### Improved
- Performance optimizations
- Accessibility improvements
- Mobile-first responsive design
- Error handling and user feedback
- Code documentation

## [2.0.0] - 2025-06-15

### Added
- Initial partner portal functionality
- Basic deal registration
- Commission tracking
- Training integration
- Resource library

### Changed
- Theme structure organization
- CSS architecture

## [1.0.0] - 2025-01-01

### Added
- Initial release
- Basic theme setup
- Landing page template
- Authentication pages
