# TeepTrak Partner Portal - Complete Content & Implementation Guide

## Overview for Claude Code

This document contains all content, copy, and implementation instructions for the TeepTrak Partner WordPress theme. Follow the design patterns from the main TeepTrak website (see reference images) while implementing this partner portal.

**Brand Voice:** Professional, technical, results-driven. Speak to industrial professionals who understand manufacturing KPIs. Lead with quantifiable outcomes (OEE improvements, ROI, productivity gains). Avoid fluffy marketing language—be direct and specific.

**Key Value Props to Emphasize Throughout:**
- 360+ plants in 30+ countries
- Clients: Stellantis, Alstom, Thales, Renault, Airbus
- 5-30% productivity improvements
- ROI within 6-12 months
- Plug-and-play implementation
- Real-time OEE/TRS tracking

---

## COLOR PALETTE & DESIGN TOKENS

```css
:root {
  --tt-red: #E63946;           /* Primary brand red */
  --tt-coral: #FF6B6B;         /* Accent coral */
  --tt-dark: #1D3557;          /* Dark blue-gray */
  --tt-gray-100: #F8F9FA;      /* Light background */
  --tt-gray-200: #E5E7EB;      /* Borders */
  --tt-gray-500: #6B7280;      /* Secondary text */
  --tt-gray-600: #4B5563;      /* Body text */
  --tt-success: #22C55E;       /* Green */
  --tt-warning: #F59E0B;       /* Amber */
  --tt-info: #3B82F6;          /* Blue */
  --tt-error: #DC2626;         /* Red for errors */
}
```

---

## PAGE 1: FRONT PAGE (Landing Page)

### URL: `/` or `/partner/`

### Hero Section

```
HEADLINE:
Become a TeepTrak Partner

SUBHEADLINE:
Join our partner ecosystem and help manufacturers achieve 5-30% productivity gains with proven Industrial IoT solutions deployed in 360+ plants worldwide.

CTA BUTTONS:
[Apply Now] (primary, red)
[Partner Login] (outline)
```

### Client Logos Bar
```
TRUSTED BY INDUSTRY LEADERS:
[STELLANTIS] [ALSTOM] [THALES] [RENAULT] [AIRBUS] [HUTCHINSON]
```

### Section: Why Partner with TeepTrak?

```
SECTION TITLE:
Partner Benefits

SECTION SUBTITLE:
Everything you need to sell, implement, and support TeepTrak solutions

BENEFIT CARDS (6 cards, 3x2 grid):

1. Attractive Commissions
   Icon: Dollar sign
   Color: Red background (#FEE2E2)
   Text: Earn 15-30% commission on every deal based on your partner tier
   
2. 90-Day Deal Protection
   Icon: Shield
   Color: Blue background (#DBEAFE)
   Text: Your registered opportunities are protected for 90 days—no channel conflict
   
3. Complete Certification
   Icon: Graduation cap
   Color: Green background (#DCFCE7)
   Text: Free training program covering OEE fundamentals, product features, and sales methodology
   
4. Sales Enablement
   Icon: Folder
   Color: Amber background (#FEF3C7)
   Text: Brochures, case studies, ROI calculators, and ready-to-use email templates
   
5. Dedicated Support
   Icon: Users
   Color: Purple background (#F3E8FF)
   Text: Personal Partner Success Manager for Gold and Platinum partners
   
6. Real-Time Dashboard
   Icon: Pulse/Chart
   Color: Cyan background (#CFFAFE)
   Text: Track deals, commissions, training progress, and performance in one place
```

### Section: Partner Tiers

```
SECTION TITLE:
Partner Tiers

SECTION SUBTITLE:
Grow with us and unlock more benefits at every level

TIER CARDS (4 columns):

BRONZE TIER
• Commission Rate: 15%
• Requirements: Complete basic certification
• Benefits:
  ✓ Deal Registration & Protection
  ✓ Basic Training Access
  ✓ Sales Resource Library
  ✓ Email Support (48h response)

SILVER TIER
• Commission Rate: 20%
• Requirements: 2+ closed deals, advanced certification
• Benefits:
  ✓ Everything in Bronze
  ✓ Co-Marketing Materials
  ✓ Priority Technical Support
  ✓ Quarterly Business Reviews

GOLD TIER (Featured/Highlighted)
• Commission Rate: 25%
• Requirements: 5+ deals, €100K+ pipeline, full certification
• Benefits:
  ✓ Everything in Silver
  ✓ Dedicated Partner Success Manager
  ✓ Lead Sharing from TeepTrak Marketing
  ✓ Joint Customer Presentations
  ✓ Early Access to New Features

PLATINUM TIER
• Commission Rate: 30%
• Requirements: 10+ deals, €250K+ pipeline, strategic alignment
• Benefits:
  ✓ Everything in Gold
  ✓ Strategic Planning Sessions
  ✓ Executive Access & Sponsorship
  ✓ Custom Marketing Campaigns
  ✓ Preferred Implementation Partner Status
```

### Section: How It Works

```
SECTION TITLE:
How the Program Works

STEPS (horizontal timeline with icons):

STEP 1: Apply
Icon: Clipboard/Form
Description: Submit your application with company details and target industries

STEP 2: Onboard
Icon: Rocket
Description: Complete certification training and access the partner portal

STEP 3: Register Deals
Icon: Document with plus
Description: Submit opportunities through the portal for 90-day protection

STEP 4: Close & Earn
Icon: Handshake
Description: Work with our sales team to close deals and earn commissions

STEP 5: Grow
Icon: Chart trending up
Description: Hit milestones to advance tiers and unlock more benefits

STEP 6: Renew
Icon: Refresh/Loop
Description: Annual renewal with performance review and tier assessment
```

### Section: Ideal Partners

```
SECTION TITLE:
Who We're Looking For

SUBTITLE:
We partner with organizations that understand manufacturing operations

PARTNER TYPE CARDS (3 columns):

SYSTEMS INTEGRATORS
• MES/ERP implementation specialists
• Industrial automation companies
• Smart factory consultants
• Minimum 3+ years in manufacturing IT

VALUE-ADDED RESELLERS
• Industrial equipment distributors
• Technology solution providers
• Regional IT specialists
• Strong manufacturing client base

CONSULTANTS & ADVISORS
• Lean/Six Sigma practitioners
• Operations excellence consultants
• Industry 4.0 advisors
• OEE/TPM specialists
```

### Section: Results

```
SECTION TITLE:
Results Our Partners Deliver

STATS ROW (4 columns):

+23%
Average OEE improvement
(Across partner-led implementations)

<6 months
Average ROI timeline
(From deployment to payback)

360+
Plants worldwide
(In 30+ countries)

98%
Customer retention
(Annual renewal rate)
```

### Section: Testimonials (Partner Quotes)

```
SECTION TITLE:
What Partners Say

TESTIMONIAL 1:
"TeepTrak's partner program gave us the tools and support to expand into manufacturing. The training was excellent, and deal protection means we can invest in opportunities without fear of channel conflict."
— [Partner Name], [Company], [Country]

TESTIMONIAL 2:
"We've closed 8 deals in our first year. The ROI calculator alone has been invaluable in conversations with plant managers."
— [Partner Name], [Company], [Country]
```

### CTA Section (Dark Background)

```
BACKGROUND: var(--tt-dark) (#1D3557)

HEADLINE:
Ready to Partner with TeepTrak?

SUBHEADLINE:
Join 50+ partners across 30+ countries helping manufacturers achieve operational excellence.

CTA BUTTON:
[Apply to Become a Partner] (primary red)

SECONDARY TEXT:
Questions? Contact our partner team at partners@teeptrak.com
```

### Footer

```
COLUMNS:

TEEPTRAK
Industrial IoT solutions for manufacturing performance.
Offices: Paris | Shenzhen | Chicago

PARTNER PROGRAM
• Benefits
• Tiers
• Resources
• Training
• FAQ

RESOURCES
• Partner Portal Login
• Case Studies
• Product Brochures
• ROI Calculator

CONTACT
partners@teeptrak.com
+33 X XX XX XX XX

SOCIAL LINKS:
[LinkedIn] [YouTube]

COPYRIGHT:
© 2026 TeepTrak. All rights reserved.
Privacy Policy | Terms of Service | Partner Agreement
```

---

## PAGE 2: DASHBOARD (Logged-in Partner Home)

### URL: `/dashboard/`
### Template: `page-dashboard.php`
### Access: Authenticated partners only

### Welcome Section

```
HEADLINE:
Welcome back, [First Name]!

SUBHEADLINE:
Here's what's happening with your partner account.

BADGE: [Current Tier Badge - Bronze/Silver/Gold/Platinum]
```

### KPI Cards (4 columns)

```
CARD 1: Partner Score
Value: [Score]/100
Visual: Progress bar
Color: Red
Tooltip: Your overall partner health score based on training, deal activity, and engagement

CARD 2: Active Deals
Value: [Number]
Color: Blue
Link: "Register deal →" (links to /deals/)

CARD 3: Training Progress
Value: [Percentage]%
Visual: Progress bar
Color: Amber
Subtext: [X] of [Y] modules completed

CARD 4: Commission Rate
Value: [Percentage]%
Color: Green
Subtext: [Tier Name] Tier
```

### Onboarding Progress Section

```
SECTION TITLE:
Onboarding Progress

PROGRESS BAR: Visual timeline showing completed vs remaining steps

STEPS:
1. Application ✓
2. Agreement Signed ✓
3. Account Created ✓
4. Basic Training (Current/In Progress)
5. First Deal Registered
6. Certification Complete
7. First Deal Closed

CURRENT STEP HIGHLIGHT:
Step [X]: [Step Name]
Action: [Relevant CTA button]
```

### Quick Actions Grid (2x2)

```
CARD 1: Register a Deal
Icon: Document with plus (red background)
Subtext: Get 90-day deal protection
Link: /deals/

CARD 2: Continue Training
Icon: Graduation cap (blue background)
Subtext: [X]/[Y] modules completed
Link: /training/

CARD 3: Download Resources
Icon: Download (green background)
Subtext: Sales materials, case studies
Link: /resources/

CARD 4: View Commissions
Icon: Dollar sign (amber background)
Subtext: Track your earnings
Link: /commissions/
```

### Recent Activity Feed (Optional Enhancement)

```
SECTION TITLE:
Recent Activity

ACTIVITY ITEMS:
• [Date] - Deal "[Company Name]" moved to Qualified stage
• [Date] - Training module "OEE Fundamentals" completed
• [Date] - Commission of €[Amount] approved for [Deal Name]
• [Date] - New resource available: "2026 Product Roadmap"

LINK: View all activity →
```

### Announcements Banner (Optional)

```
BANNER TYPE: Info (blue) or Promo (gradient red)

EXAMPLE:
🎉 New Feature: Real-time OEE alerts now available for all customers!
[Learn More] [Dismiss]
```

---

## PAGE 3: DEAL REGISTRATION

### URL: `/deals/`
### Template: `page-deals.php`
### Access: Authenticated partners only

### Page Header

```
HEADLINE:
Deal Registration

SUBHEADLINE:
Register opportunities and get 90-day deal protection

ACTION BUTTON:
[+ Register New Deal] (primary, opens modal)
```

### Stats Row (3 columns)

```
STAT 1: Total Deals
Value: [Number]
Subtext: All time

STAT 2: Active Deals
Value: [Number]
Color: Blue
Subtext: In pipeline

STAT 3: Pipeline Value
Value: €[Amount]
Color: Green
Subtext: Total opportunity value
```

### Deals Table

```
TABLE HEADERS:
Company | Contact | Value | Stage | Protection | Actions

STAGE OPTIONS (with badge colors):
• Registered (Gray) - Just submitted
• Qualified (Blue) - Initial contact made
• Demo Scheduled (Amber) - Meeting set
• Proposal Sent (Purple) - Quote delivered
• Negotiation (Orange) - Terms discussion
• Closed Won (Green) - Deal completed ✓
• Closed Lost (Red) - Opportunity lost ✗

PROTECTION COLUMN:
Visual progress bar showing days remaining
• 90-60 days: Green
• 59-30 days: Amber
• <30 days: Red
Text: "[X] days remaining"

ACTIONS:
[Edit] [Update Stage] [View Details]
```

### Empty State (No Deals)

```
ICON: Document illustration
HEADLINE: No deals registered yet
SUBHEADLINE: Register your first opportunity to get 90-day protection
CTA: [+ Register New Deal]
```

### Register Deal Modal

```
MODAL TITLE:
Register New Deal

FORM FIELDS:

Company Name * (text input)
Placeholder: "e.g., Acme Manufacturing"
Validation: Required, min 2 characters

Industry (dropdown)
Options:
• Automotive
• Aerospace & Defense
• Food & Beverage
• Pharmaceuticals
• Electronics
• Machinery & Equipment
• Packaging
• Other Manufacturing

Company Size (dropdown)
Options:
• SME (<250 employees)
• Mid-Market (250-1000)
• Enterprise (1000+)

Number of Plants (number input)
Placeholder: "1"

Contact Name (text input)
Placeholder: "e.g., Jean Dupont"

Contact Title (text input)
Placeholder: "e.g., Plant Manager"

Contact Email (email input)
Placeholder: "jean.dupont@company.com"
Validation: Valid email format

Contact Phone (tel input)
Placeholder: "+33 X XX XX XX XX"

Estimated Deal Value (€) (number input)
Placeholder: "50000"
Helper: Estimated annual contract value

Expected Close Date (date picker)
Default: 3 months from today

Notes (textarea)
Placeholder: "Brief description of the opportunity, pain points, current solutions..."
Max: 500 characters

BUTTONS:
[Cancel] (secondary)
[Register Deal] (primary)

SUCCESS MESSAGE:
✓ Deal registered successfully! Your 90-day protection starts now.
```

### Deal Detail View (Expandable or Modal)

```
HEADER:
[Company Name]
Stage: [Badge]
Registered: [Date] by [Partner Name]

SECTIONS:

Company Information
• Industry: [Value]
• Size: [Value]
• Plants: [Value]

Contact Information
• Name: [Value]
• Title: [Value]
• Email: [Value] (clickable)
• Phone: [Value] (clickable)

Deal Information
• Estimated Value: €[Amount]
• Expected Close: [Date]
• Protection Expires: [Date] ([X] days)

Timeline
• [Date] - Deal registered
• [Date] - Stage updated to Qualified
• [Date] - Note added: "..."

Notes
[Editable textarea]

ACTIONS:
[Update Stage] [Edit Deal] [Add Note]
```

---

## PAGE 4: TRAINING CENTER

### URL: `/training/`
### Template: `page-training.php`
### Access: Authenticated partners only

### Page Header

```
HEADLINE:
Training Center

SUBHEADLINE:
[X] of [Y] modules completed

PROGRESS INDICATOR:
[Visual progress bar] [XX]%
```

### Training Path Overview

```
SECTION TITLE:
Your Certification Path

VISUAL: Horizontal progress path with nodes

PATH LEVELS:

LEVEL 1: Certified Partner (Bronze Requirement)
Modules: 3 | Est. Time: 2 hours
• Introduction to TeepTrak (15 min)
• OEE Fundamentals (30 min)
• Product Overview (45 min)
Assessment: 70% passing score
Badge: Bronze Certified Partner

LEVEL 2: Certified Sales Partner (Silver Requirement)
Modules: 3 | Est. Time: 3 hours
• Sales Methodology (60 min)
• Competitive Positioning (45 min)
• ROI & Business Case (45 min)
Assessment: 80% passing score
Badge: Silver Certified Partner

LEVEL 3: Certified Implementation Partner (Gold Requirement)
Modules: 4 | Est. Time: 4 hours
• Technical Architecture (60 min)
• Installation & Configuration (90 min)
• Customer Success Best Practices (45 min)
• Troubleshooting Guide (45 min)
Assessment: 85% passing score
Badge: Gold Certified Partner
```

### Module Cards Grid

```
MODULE CARD DESIGN:

[Gradient Header Image]
[Completion Badge: ✓ Completed / In Progress % / Locked 🔒]

Module Title
Duration: [X] minutes
Level: [Basic/Intermediate/Advanced]

Progress bar (if in progress)

[Button: Start / Continue / Review]

---

MODULE 1: Introduction to TeepTrak
Duration: 15 minutes
Level: Basic
Description: Learn about TeepTrak's mission, history, and position in the Industrial IoT market.
Topics Covered:
• Company overview and market position
• Target customers and industries
• Partner program structure
Status: [Completed/In Progress/Not Started]

MODULE 2: OEE Fundamentals
Duration: 30 minutes
Level: Basic
Description: Master the principles of Overall Equipment Effectiveness—the key metric our solutions optimize.
Topics Covered:
• What is OEE and why it matters
• Availability, Performance, Quality components
• Common OEE benchmarks by industry
• Calculating and interpreting OEE
Status: [Completed/In Progress/Not Started]

MODULE 3: TeepTrak Product Features
Duration: 45 minutes
Level: Basic
Description: Deep dive into TeepTrak's product capabilities, dashboards, and customer-facing features.
Topics Covered:
• Real-time monitoring dashboard
• Alert and notification system
• Reporting and analytics
• Mobile app capabilities
• Integration options
Status: [Completed/In Progress/Not Started]

MODULE 4: Sales Methodology
Duration: 60 minutes
Level: Intermediate
Description: Learn how to identify, qualify, and close TeepTrak opportunities.
Topics Covered:
• Ideal customer profile (ICP)
• Discovery questions and pain point identification
• Solution mapping and demo best practices
• Objection handling
• Proposal and pricing structure
Status: [Locked - Complete Level 1]

MODULE 5: Competitive Positioning
Duration: 45 minutes
Level: Intermediate
Description: Understand the competitive landscape and how to position TeepTrak against alternatives.
Topics Covered:
• Market landscape: MES vs. OEE software vs. TeepTrak
• Key differentiators
• Competitive battle cards
• Win/loss analysis insights
Status: [Locked - Complete Level 1]

MODULE 6: ROI & Business Case
Duration: 45 minutes
Level: Intermediate
Description: Build compelling business cases that justify investment to CFOs and plant managers.
Topics Covered:
• ROI calculator walkthrough
• Cost-benefit analysis framework
• Case study examples with real numbers
• Presenting to financial stakeholders
Status: [Locked - Complete Level 1]

MODULE 7: Technical Architecture
Duration: 60 minutes
Level: Advanced
Description: Understand TeepTrak's technical infrastructure for implementation discussions.
Topics Covered:
• System architecture overview
• Data flow and connectivity
• Security and compliance
• Integration with MES/ERP systems
Status: [Locked - Complete Level 2]

MODULE 8: Installation & Configuration
Duration: 90 minutes
Level: Advanced
Description: Hands-on guide to deploying TeepTrak at customer sites.
Topics Covered:
• Hardware requirements and setup
• Software installation steps
• Configuration and customization
• Go-live checklist
Status: [Locked - Complete Level 2]
```

### Assessment Section

```
ASSESSMENT CARD:

Level [X] Certification Assessment

Requirements:
• Complete all [X] modules in this level
• Score 70%+ on assessment
• [X] attempts remaining

Questions: 25 | Time Limit: 45 minutes

[Start Assessment] (if eligible)
[Locked - Complete all modules] (if not eligible)

PREVIOUS ATTEMPT (if applicable):
Last attempt: [Date] | Score: [X]% | Result: [Passed/Failed]
```

### Certifications Earned

```
SECTION TITLE:
Your Certifications

CERTIFICATION CARD:
[Badge Image]
[Certification Name]
Earned: [Date]
Valid Until: [Date + 1 year]
[Download Certificate] [Share on LinkedIn]

EMPTY STATE:
Complete training modules and pass assessments to earn certifications.
```

---

## PAGE 5: RESOURCE LIBRARY

### URL: `/resources/`
### Template: `page-resources.php`
### Access: Authenticated partners only (some resources tier-gated)

### Page Header

```
HEADLINE:
Resource Library

SUBHEADLINE:
Sales materials, technical docs, and marketing assets
```

### Category Filter

```
FILTER TABS:
[All] [Sales] [Technical] [Marketing] [Case Studies]

SEARCH BAR:
"Search resources..." (icon: magnifying glass)
```

### Resource Cards Grid

```
RESOURCE CARD DESIGN:

[File Type Icon with Color]
• PDF: Red (#DC2626)
• XLSX: Green (#16A34A)
• ZIP: Blue (#2563EB)
• PPTX: Orange (#F59E0B)
• VIDEO: Purple (#9333EA)

Title
Description
File Size | File Type

[Tier Badge if restricted: 🔒 Silver+ / 🔒 Gold+]

[↓ Download] (or [🔒 Locked] if tier-restricted)

---

RESOURCES LIST:

SALES MATERIALS:

1. TeepTrak Product Brochure (2026)
   Description: Complete product overview with features, benefits, and use cases
   Type: PDF | Size: 2.4 MB
   Tier: Bronze+
   
2. Partner Sales Deck
   Description: Customizable presentation for customer meetings
   Type: PPTX | Size: 8.1 MB
   Tier: Bronze+

3. OEE ROI Calculator
   Description: Interactive spreadsheet to calculate customer ROI
   Type: XLSX | Size: 1.1 MB
   Tier: Bronze+
   
4. Pricing Guide (Partner)
   Description: Current pricing, discounts, and deal structure
   Type: PDF | Size: 450 KB
   Tier: Bronze+

5. Competitive Battle Cards
   Description: Positioning against MES, manual tracking, competitors
   Type: PDF | Size: 1.8 MB
   Tier: Silver+

6. Discovery Call Script
   Description: Qualifying questions and call framework
   Type: PDF | Size: 380 KB
   Tier: Silver+

---

TECHNICAL DOCUMENTATION:

7. Technical Specifications
   Description: Hardware requirements, connectivity, and system architecture
   Type: PDF | Size: 3.8 MB
   Tier: Bronze+

8. Integration Guide
   Description: Connecting TeepTrak with MES, ERP, and SCADA systems
   Type: PDF | Size: 5.2 MB
   Tier: Silver+

9. API Documentation
   Description: REST API reference for custom integrations
   Type: PDF | Size: 2.1 MB
   Tier: Gold+

10. Installation Checklist
    Description: Site readiness and deployment checklist
    Type: PDF | Size: 680 KB
    Tier: Silver+

---

MARKETING MATERIALS:

11. Co-Branded Email Templates
    Description: Ready-to-use outreach emails with customization spots
    Type: ZIP | Size: 850 KB
    Tier: Silver+

12. Social Media Assets
    Description: LinkedIn posts, banners, and graphics
    Type: ZIP | Size: 12.4 MB
    Tier: Silver+

13. Partner Logo Kit
    Description: TeepTrak Partner badges and logo files
    Type: ZIP | Size: 4.2 MB
    Tier: Bronze+

14. Webinar Slides Template
    Description: Presentation template for partner webinars
    Type: PPTX | Size: 6.8 MB
    Tier: Gold+

---

CASE STUDIES:

15. Stellantis Case Study
    Description: How Stellantis achieved 23% OEE improvement across 12 plants
    Type: PDF | Size: 1.8 MB
    Tier: Bronze+

16. Alstom Case Study
    Description: Railway component manufacturing optimization
    Type: PDF | Size: 1.5 MB
    Tier: Bronze+

17. Thales Case Study
    Description: Defense electronics assembly line transformation
    Type: PDF | Size: 1.6 MB
    Tier: Bronze+

18. Mid-Market Success Stories
    Description: Compilation of SME implementations
    Type: PDF | Size: 2.8 MB
    Tier: Silver+

---

VIDEO RESOURCES:

19. Product Demo Video (5 min)
    Description: Overview demo suitable for sharing with prospects
    Type: VIDEO | Duration: 5:23
    Tier: Bronze+

20. Customer Testimonial Reel
    Description: Plant managers sharing their TeepTrak experience
    Type: VIDEO | Duration: 3:45
    Tier: Bronze+

21. Technical Deep Dive Webinar
    Description: Recorded session on architecture and integrations
    Type: VIDEO | Duration: 45:00
    Tier: Silver+
```

### Locked Resource State

```
LOCKED CARD:

[Blurred preview or lock overlay]

🔒 [Tier]+ Required

This resource is available to [Tier] partners and above.

Current Tier: [User's Tier]

[How to Upgrade] → Links to tier info or contacts PSM
```

### Recently Added Section

```
SECTION TITLE:
Recently Added

BADGE: "NEW" (red)

Display 3-4 most recent resources with "NEW" badge
```

---

## PAGE 6: COMMISSIONS

### URL: `/commissions/`
### Template: `page-commissions.php`
### Access: Authenticated partners only

### Page Header

```
HEADLINE:
My Commissions

SUBHEADLINE:
Track your earnings and request withdrawals
```

### Summary Cards (3 columns)

```
CARD 1: Available Balance (Green gradient background)
Value: €[Amount]
Subtext: Ready for withdrawal
Button: [Request Withdrawal]

CARD 2: Pending Commissions
Value: €[Amount]
Color: Amber
Subtext: Awaiting deal closure or approval

CARD 3: Total Paid (All Time)
Value: €[Amount]
Subtext: Since [Join Date]
```

### Commission Rate Info Bar

```
BACKGROUND: Light gray

CONTENT:
[Tier Badge]
Your Commission Rate: [XX]%
[Tier Name] Tier Partner

LINK: How to increase your rate →
```

### Commission Structure Info (Collapsible)

```
SECTION TITLE:
Commission Structure

TABLE:

Tier      | Rate | Requirements
----------|------|----------------------------------
Bronze    | 15%  | Complete basic certification
Silver    | 20%  | 2+ deals, advanced certification
Gold      | 25%  | 5+ deals, €100K+ pipeline
Platinum  | 30%  | 10+ deals, €250K+ pipeline

NOTES:
• Commissions calculated on net contract value
• Paid within 30 days of deal closure confirmation
• Recurring commissions for multi-year contracts (at 50% rate in years 2+)
```

### Transaction History Table

```
TABLE HEADERS:
Date | Type | Description | Amount | Status

TYPE COLUMN:
• ↓ Commission (green text)
• ↑ Withdrawal (red text)

STATUS BADGES:
• Paid (green)
• Pending (amber)
• Processing (blue)

SAMPLE ROWS:
15 Jan 2026 | ↓ Commission | Stellantis Deal #1234 | +€3,200 | Paid
10 Jan 2026 | ↑ Withdrawal | Bank Transfer | -€2,500 | Paid
28 Dec 2025 | ↓ Commission | Renault Deal #1189 | +€1,800 | Paid
20 Dec 2025 | ↓ Commission | Alstom Deal #1156 | +€4,500 | Pending
```

### Commission by Deal (Detailed View)

```
SECTION TITLE:
Commission by Deal

TABLE HEADERS:
Deal | Company | Deal Value | Commission Rate | Commission Amount | Status | Paid Date

EXPANDABLE ROW:
Click to show deal details, timeline, and commission calculation breakdown
```

### Withdrawal Section

```
SECTION TITLE:
Request Withdrawal

CURRENT BALANCE:
€[Amount] available

MINIMUM WITHDRAWAL: €500

FORM FIELDS:
Amount (€) [Input field]
Bank Account: [Dropdown of saved accounts or "Add New"]

SAVED ACCOUNTS:
• ****1234 (Primary)
• ****5678

[Add Bank Account] → Opens modal

PROCESSING TIME:
Withdrawals typically processed within 5 business days.

[Request Withdrawal] (primary button)
```

### Add Bank Account Modal

```
MODAL TITLE:
Add Bank Account

FIELDS:
Account Holder Name *
IBAN *
BIC/SWIFT *
Bank Name

[Cancel] [Save Account]
```

### Empty State (No Transactions)

```
ICON: Coins/Money illustration
HEADLINE: No commissions yet
SUBHEADLINE: Close your first deal to start earning commissions
CTA: [Register a Deal]
```

---

## GLOBAL COMPONENTS

### Navigation (Sidebar - Portal Pages)

```
LOGO: TeepTrak (links to /dashboard/)

MAIN SECTION:
• Dashboard (icon: grid)
• Deal Registration (icon: document-plus)

LEARNING SECTION:
• Training (icon: graduation-cap)
• Resources (icon: folder)

FINANCE SECTION:
• Commissions (icon: dollar-sign)

FOOTER:
[Tier Badge]
[Logout] (icon: log-out)
```

### Header (Portal Pages)

```
LEFT: Mobile menu toggle (hamburger) | Page Title
RIGHT: "Welcome, [First Name]"
```

### Navigation (Landing Page)

```
LOGO: TeepTrak

NAV LINKS:
• Benefits (anchor: #benefits)
• Partner Tiers (anchor: #tiers)
• How It Works (anchor: #process)
• Login (wp_login_url)
• [Become a Partner] (primary button, wp_registration_url)
```

### Tier Badges

```
BRONZE:
Background: Linear gradient (#CD7F32 → #8B4513)
Text: "Bronze Partner"

SILVER:
Background: Linear gradient (#C0C0C0 → #A8A8A8)
Text: "Silver Partner"

GOLD:
Background: Linear gradient (#FFD700 → #FFA500)
Text: "Gold Partner"

PLATINUM:
Background: Linear gradient (#E5E4E2 → #B4B4B4)
Text: "Platinum Partner"
```

### Stage Badges (Deals)

```
registered: Gray (#9CA3AF)
qualified: Blue (#3B82F6)
demo_scheduled: Amber (#F59E0B)
proposal_sent: Purple (#8B5CF6)
negotiation: Orange (#F97316)
closed_won: Green (#22C55E)
closed_lost: Red (#DC2626)
```

### Alerts/Notifications

```
SUCCESS (green border-left):
✓ [Message]

WARNING (amber border-left):
⚠ [Message]

ERROR (red border-left):
✗ [Message]

INFO (blue border-left):
ℹ [Message]
```

### Empty States Pattern

```
STRUCTURE:
[Illustration/Icon - muted colors]
[Headline - what's missing]
[Subheadline - action to take]
[CTA Button]
```

### Loading States

```
SKELETON SCREENS:
Use animated gray placeholders matching card/table dimensions

BUTTON LOADING:
"Processing..." with spinner icon
Disable button during action
```

---

## SEO & META CONTENT

### Front Page
```
Title: Become a TeepTrak Partner | Industrial IoT Partner Program
Description: Join TeepTrak's partner ecosystem. Earn up to 30% commission helping manufacturers achieve 5-30% productivity gains with proven OEE solutions.
Keywords: OEE partner program, industrial IoT reseller, manufacturing software partner, TeepTrak partners
```

### Dashboard
```
Title: Partner Dashboard | TeepTrak Partner Portal
(No index - private page)
```

### Deals
```
Title: Deal Registration | TeepTrak Partner Portal
(No index - private page)
```

### Training
```
Title: Training Center | TeepTrak Partner Portal
(No index - private page)
```

### Resources
```
Title: Resource Library | TeepTrak Partner Portal
(No index - private page)
```

### Commissions
```
Title: My Commissions | TeepTrak Partner Portal
(No index - private page)
```

---

## IMPLEMENTATION NOTES FOR CLAUDE CODE

### Priority Order
1. Front Page (landing) - public facing, SEO important
2. Dashboard - first page partners see after login
3. Deal Registration - core functionality
4. Resources - important for partner enablement
5. Training - can use LearnPress integration
6. Commissions - important but can be simpler initially

### Technical Considerations

1. **Authentication**: All portal pages (dashboard, deals, training, resources, commissions) require login. Redirect to login page if not authenticated.

2. **User Meta**: Store partner data in WordPress user meta:
   - `teeptrak_partner_tier` (bronze/silver/gold/platinum)
   - `teeptrak_partner_score` (0-100)
   - `teeptrak_commission_rate` (15/20/25/30)
   - `teeptrak_onboarding_step` (1-7)
   - `teeptrak_deals` (serialized array)

3. **LearnPress Integration**: Training page should check for LearnPress plugin. If present, use shortcodes. If not, show demo modules.

4. **Responsive Design**: 
   - Mobile: Single column, collapsible sidebar
   - Tablet: 2 columns
   - Desktop: 3-4 columns, sidebar always visible

5. **Form Handling**: Use WordPress nonces for security. Sanitize all inputs.

6. **Localization**: All strings wrapped in `__()` or `_e()` for translation.

### File Structure Reference

```
teeptrak-partner/
├── front-page.php          # Landing page
├── page-dashboard.php      # Partner dashboard
├── page-deals.php          # Deal registration
├── page-training.php       # Training center
├── page-resources.php      # Resource library
├── page-commissions.php    # Commissions page
├── header.php              # Header/sidebar
├── footer.php              # Footer
├── functions.php           # Theme functions
├── style.css               # Main stylesheet
├── assets/
│   ├── css/
│   │   └── portal.css      # Portal-specific styles
│   └── js/
│       └── main.js         # JS functionality
└── inc/
    └── template-tags.php   # Helper functions
```

### CSS Classes Convention

```
.tt-*           # All TeepTrak theme classes
.tt-btn         # Buttons
.tt-btn-primary # Primary button (red)
.tt-btn-secondary # Secondary button (gray)
.tt-card        # Card containers
.tt-card-body   # Card content area
.tt-card-header # Card header
.tt-form-group  # Form field wrapper
.tt-input       # Input fields
.tt-label       # Form labels
.tt-badge       # Status badges
.tt-badge-success/warning/error
.tt-progress    # Progress bar container
.tt-progress-bar # Progress bar fill
.tt-table       # Table styles
.tt-alert       # Alert/notification boxes
.tt-grid        # CSS Grid container
.tt-grid-cols-* # Column count (1-4)
.tt-flex        # Flexbox container
.tt-gap-*       # Gap spacing
.tt-text-*      # Text utilities
.tt-font-*      # Font utilities
.tt-mb-*, .tt-mt-* # Margin utilities
```

---

## SAMPLE DATA FOR DEVELOPMENT

### Demo Partner Profile
```php
$demo_partner = array(
    'tier' => 'gold',
    'partner_score' => 78,
    'commission_rate' => 25,
    'onboarding_step' => 5,
    'deals_count' => 6,
    'active_deals' => 3,
    'pipeline_value' => 145000,
    'available_balance' => 4500,
    'pending_commissions' => 2800,
    'total_paid' => 18500,
);
```

### Demo Deals
```php
$demo_deals = array(
    array(
        'id' => 'deal_001',
        'company_name' => 'Acme Manufacturing',
        'industry' => 'Automotive',
        'contact_name' => 'Pierre Martin',
        'contact_email' => 'p.martin@acme.fr',
        'deal_value' => 45000,
        'stage' => 'proposal_sent',
        'protection_end' => '2026-03-15',
        'created_at' => '2025-12-15',
    ),
    array(
        'id' => 'deal_002',
        'company_name' => 'TechParts GmbH',
        'industry' => 'Electronics',
        'contact_name' => 'Hans Schmidt',
        'contact_email' => 'h.schmidt@techparts.de',
        'deal_value' => 72000,
        'stage' => 'demo_scheduled',
        'protection_end' => '2026-04-01',
        'created_at' => '2026-01-02',
    ),
);
```

### Demo Transactions
```php
$demo_transactions = array(
    array(
        'date' => '2026-01-15',
        'type' => 'commission',
        'description' => 'Stellantis Plant Lyon - Deal #1234',
        'amount' => 3200,
        'status' => 'paid',
    ),
    array(
        'date' => '2026-01-10',
        'type' => 'withdrawal',
        'description' => 'Bank Transfer to ****1234',
        'amount' => 2500,
        'status' => 'paid',
    ),
    array(
        'date' => '2025-12-28',
        'type' => 'commission',
        'description' => 'Renault Flins - Deal #1189',
        'amount' => 1800,
        'status' => 'paid',
    ),
    array(
        'date' => '2025-12-20',
        'type' => 'commission',
        'description' => 'Alstom Belfort - Deal #1156',
        'amount' => 4500,
        'status' => 'pending',
    ),
);
```

---

## END OF CONTENT GUIDE

This document provides complete content and implementation guidance for the TeepTrak Partner Portal. All copy is written in professional B2B industrial tone targeting systems integrators, VARs, and consultants in the manufacturing sector.

For questions or clarifications, reference the design screenshots provided showing the main TeepTrak website design patterns.
