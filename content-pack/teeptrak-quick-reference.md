# TeepTrak Partner Portal - Quick Reference Card

## BRAND ESSENTIALS

**Primary Color:** #E63946 (Red)
**Dark:** #1D3557
**Success:** #22C55E | **Warning:** #F59E0B | **Info:** #3B82F6

**Key Stats to Use:**
- 360+ plants in 30+ countries
- 5-30% productivity gains
- ROI in 6-12 months
- Clients: Stellantis, Alstom, Thales, Renault, Airbus

---

## PAGES OVERVIEW

| Page | URL | Template | Access |
|------|-----|----------|--------|
| Landing | `/` | `front-page.php` | Public |
| Dashboard | `/dashboard/` | `page-dashboard.php` | Auth |
| Deals | `/deals/` | `page-deals.php` | Auth |
| Training | `/training/` | `page-training.php` | Auth |
| Resources | `/resources/` | `page-resources.php` | Auth |
| Commissions | `/commissions/` | `page-commissions.php` | Auth |

---

## TIER STRUCTURE

| Tier | Commission | Color | Requirements |
|------|------------|-------|--------------|
| Bronze | 15% | #CD7F32 | Basic certification |
| Silver | 20% | #A8A8A8 | 2+ deals, advanced cert |
| **Gold** | 25% | #FFA500 | 5+ deals, €100K+ pipeline |
| Platinum | 30% | #6B7280 | 10+ deals, €250K+ pipeline |

---

## KEY HEADLINES BY PAGE

### Front Page
- **Hero:** "Become a TeepTrak Partner"
- **Benefits:** "Partner Benefits"
- **Tiers:** "Partner Tiers"
- **CTA:** "Ready to Partner with TeepTrak?"

### Dashboard
- **Welcome:** "Welcome back, [First Name]!"
- **KPIs:** Partner Score, Active Deals, Training Progress, Commission Rate

### Deals
- **Header:** "Deal Registration"
- **Subhead:** "Register opportunities and get 90-day deal protection"
- **Stats:** Total Deals, Active Deals, Pipeline Value

### Training
- **Header:** "Training Center"
- **Modules:** 8 total (Basic → Intermediate → Advanced)
- **Certifications:** 3 levels (Partner, Sales, Implementation)

### Resources
- **Header:** "Resource Library"
- **Categories:** Sales, Technical, Marketing, Case Studies
- **Types:** PDF, XLSX, PPTX, ZIP, VIDEO

### Commissions
- **Header:** "My Commissions"
- **Cards:** Available Balance, Pending, Total Paid
- **Min Withdrawal:** €500

---

## DEAL STAGES (with colors)

```
registered     → Gray    #9CA3AF
qualified      → Blue    #3B82F6
demo_scheduled → Amber   #F59E0B
proposal_sent  → Purple  #8B5CF6
negotiation    → Orange  #F97316
closed_won     → Green   #22C55E
closed_lost    → Red     #DC2626
```

---

## ICON MAPPING (Use Feather/Lucide icons)

| Feature | Icon |
|---------|------|
| Dashboard | grid |
| Deals | file-plus |
| Training | graduation-cap |
| Resources | folder |
| Commissions | dollar-sign |
| Protection | shield |
| Success | check-circle |
| Download | download |
| Logout | log-out |

---

## CSS CLASS NAMING CONVENTION

```
.tt-*              → All TeepTrak classes
.tt-btn            → Buttons
.tt-btn-primary    → Red button
.tt-btn-secondary  → Gray button
.tt-card           → Card container
.tt-card-body      → Card content
.tt-card-header    → Card header
.tt-badge          → Status badges
.tt-badge-success  → Green badge
.tt-badge-warning  → Amber badge
.tt-progress       → Progress bar
.tt-table          → Table styling
.tt-alert          → Notifications
.tt-grid           → CSS Grid
.tt-grid-cols-{n}  → Column count
.tt-flex           → Flexbox
.tt-gap-{n}        → Gap spacing
.tt-text-gray-500  → Gray text
.tt-font-bold      → Bold text
.tt-mb-{n}         → Margin bottom
```

---

## USER META KEYS

```php
teeptrak_partner_tier      // bronze|silver|gold|platinum
teeptrak_partner_score     // 0-100
teeptrak_commission_rate   // 15|20|25|30
teeptrak_onboarding_step   // 1-7
teeptrak_deals             // serialized array
```

---

## QUICK COPY SNIPPETS

**90-Day Protection Message:**
> "Your registered opportunities are protected for 90 days—no channel conflict"

**Commission Highlight:**
> "Earn 15-30% commission on every deal based on your partner tier"

**Training Value:**
> "Free training program covering OEE fundamentals, product features, and sales methodology"

**Partner CTA:**
> "Join 50+ partners across 30+ countries helping manufacturers achieve operational excellence"

**Empty State - Deals:**
> Headline: "No deals registered yet"
> Subhead: "Register your first opportunity to get 90-day protection"

**Empty State - Commissions:**
> Headline: "No commissions yet"
> Subhead: "Close your first deal to start earning commissions"

---

## FILES TO REFERENCE

1. `teeptrak-partner-content-guide.md` - Complete content documentation
2. `teeptrak-partner-content.json` - Structured data for parsing
3. Design images - Main TeepTrak website patterns

---

## IMPLEMENTATION PRIORITY

1. ✅ Front Page (public, SEO critical)
2. ✅ Dashboard (first partner experience)
3. ✅ Deal Registration (core feature)
4. ✅ Resources (sales enablement)
5. ✅ Training (LearnPress integration)
6. ✅ Commissions (financial tracking)
