-- TeepTrak Partner Portal - Sample Data Import
-- Run this SQL AFTER activating the TeepTrak Partner Portal plugin
-- The plugin creates the necessary tables on activation

-- ===========================================
-- SAMPLE PARTNERS DATA
-- ===========================================

INSERT INTO wp_teeptrak_partners (user_id, company_name, contact_name, contact_email, phone, address, city, country, tier, status, partner_score, total_deals, total_revenue, onboarding_completed, psm_assigned, notes, created_at, updated_at) VALUES
(2, 'Acme Industries', 'John Smith', 'john@acme-industries.com', '+1-555-0101', '123 Business Ave', 'New York', 'United States', 'gold', 'active', 82, 18, 450000.00, 1, 1, 'Long-term strategic partner', NOW(), NOW()),
(3, 'TechFlow Solutions', 'Sarah Johnson', 'sarah@techflow.io', '+1-555-0102', '456 Innovation Way', 'San Francisco', 'United States', 'platinum', 'active', 95, 42, 1250000.00, 1, 1, 'Top performing partner 2024', NOW(), NOW()),
(4, 'Euro Systems GmbH', 'Hans Mueller', 'hans@eurosystems.de', '+49-30-12345', 'Hauptstrasse 78', 'Berlin', 'Germany', 'silver', 'active', 68, 8, 180000.00, 1, 1, 'Growing European market', NOW(), NOW()),
(5, 'Asia Pacific Tech', 'Li Wei', 'li.wei@aptech.cn', '+86-21-12345678', '88 Nanjing Road', 'Shanghai', 'China', 'silver', 'active', 71, 12, 320000.00, 1, 1, 'APAC regional partner', NOW(), NOW()),
(6, 'Nordic Solutions AB', 'Erik Lindberg', 'erik@nordicsolutions.se', '+46-8-123456', 'Storgatan 45', 'Stockholm', 'Sweden', 'bronze', 'active', 55, 3, 75000.00, 0, 1, 'New partner - onboarding in progress', NOW(), NOW()),
(7, 'Iberia Partners SL', 'Carlos Rodriguez', 'carlos@iberiapartners.es', '+34-91-1234567', 'Calle Mayor 100', 'Madrid', 'Spain', 'bronze', 'pending', 45, 1, 25000.00, 0, NULL, 'Application under review', NOW(), NOW()),
(8, 'Down Under Tech', 'James Wilson', 'james@downundertech.com.au', '+61-2-98765432', '200 George Street', 'Sydney', 'Australia', 'gold', 'active', 78, 15, 380000.00, 1, 1, 'ANZ market leader', NOW(), NOW());

-- ===========================================
-- SAMPLE DEALS DATA
-- ===========================================

INSERT INTO wp_teeptrak_deals (partner_id, customer_name, customer_email, customer_company, deal_value, pipeline_stage, expected_close_date, protection_expires, product_interest, notes, created_at, updated_at) VALUES
(1, 'Michael Brown', 'mbrown@megacorp.com', 'MegaCorp Inc', 75000.00, 'proposal', '2025-03-15', '2025-06-15', 'Enterprise Suite', 'Large manufacturing client', NOW(), NOW()),
(1, 'Jennifer Lee', 'jlee@innovate.co', 'Innovate Co', 45000.00, 'negotiation', '2025-02-28', '2025-05-28', 'Standard Package', 'Tech startup', NOW(), NOW()),
(2, 'Robert Chen', 'rchen@globalfirm.com', 'Global Firm Ltd', 150000.00, 'closed_won', '2025-01-10', '2025-04-10', 'Enterprise Suite + Support', 'Multi-year contract', NOW(), NOW()),
(2, 'Amanda White', 'awhite@techventures.io', 'Tech Ventures', 85000.00, 'proposal', '2025-04-01', '2025-07-01', 'Professional Package', 'Series B funded', NOW(), NOW()),
(2, 'David Park', 'dpark@futuretech.com', 'FutureTech', 200000.00, 'negotiation', '2025-03-20', '2025-06-20', 'Enterprise Suite', 'Fortune 500 subsidiary', NOW(), NOW()),
(3, 'Klaus Schmidt', 'kschmidt@deutschebank.de', 'Deutsche Manufacturing', 65000.00, 'qualified', '2025-05-15', '2025-08-15', 'Standard Package', 'Initial deployment', NOW(), NOW()),
(4, 'Zhang Mei', 'zmei@chinatech.cn', 'China Tech Ltd', 95000.00, 'proposal', '2025-04-30', '2025-07-30', 'Professional Package', 'Government-affiliated', NOW(), NOW()),
(4, 'Wang Jun', 'wjun@shanghaiindustries.cn', 'Shanghai Industries', 120000.00, 'closed_won', '2025-01-05', '2025-04-05', 'Enterprise Suite', 'Expansion deal', NOW(), NOW()),
(5, 'Anna Svensson', 'asvensson@scandinaviatech.se', 'Scandinavia Tech', 35000.00, 'discovery', '2025-06-01', '2025-09-01', 'Starter Package', 'First contact', NOW(), NOW()),
(7, 'Peter Thompson', 'pthompson@aussiemanufacturing.au', 'Aussie Manufacturing', 55000.00, 'proposal', '2025-03-30', '2025-06-30', 'Professional Package', 'Regional expansion', NOW(), NOW()),
(7, 'Susan Clarke', 'sclarke@pacificretail.com.au', 'Pacific Retail', 42000.00, 'qualified', '2025-04-15', '2025-07-15', 'Standard Package', 'Retail sector', NOW(), NOW());

-- ===========================================
-- SAMPLE COMMISSIONS DATA
-- ===========================================

INSERT INTO wp_teeptrak_commissions (partner_id, deal_id, commission_amount, commission_rate, status, paid_date, invoice_number, notes, created_at) VALUES
(2, 3, 22500.00, 0.15, 'paid', '2025-01-20', 'INV-2025-0001', 'Q1 2025 commission payment', NOW()),
(4, 8, 14400.00, 0.12, 'paid', '2025-01-15', 'INV-2025-0002', 'Q1 2025 commission payment', NOW()),
(1, 1, 11250.00, 0.15, 'pending', NULL, NULL, 'Awaiting deal closure', NOW()),
(1, 2, 6750.00, 0.15, 'approved', NULL, 'INV-2025-0003', 'Approved, payment processing', NOW()),
(2, 4, 17000.00, 0.20, 'pending', NULL, NULL, 'Awaiting deal closure', NOW()),
(2, 5, 40000.00, 0.20, 'pending', NULL, NULL, 'Large enterprise deal', NOW());

-- ===========================================
-- SAMPLE RESOURCES DATA
-- ===========================================

INSERT INTO wp_teeptrak_resources (title, description, category, file_url, file_type, file_size, min_tier, download_count, is_featured, created_at, updated_at) VALUES
('TeepTrak Product Overview', 'Complete overview of TeepTrak product line and capabilities', 'sales', '/wp-content/uploads/resources/teeptrak-overview-2025.pdf', 'pdf', '2.5 MB', 'bronze', 245, 1, NOW(), NOW()),
('Partner Sales Playbook', 'Comprehensive sales strategies and techniques for partners', 'sales', '/wp-content/uploads/resources/partner-sales-playbook.pdf', 'pdf', '5.2 MB', 'silver', 128, 1, NOW(), NOW()),
('Technical Integration Guide', 'Step-by-step technical integration documentation', 'technical', '/wp-content/uploads/resources/integration-guide-v3.pdf', 'pdf', '8.1 MB', 'bronze', 312, 0, NOW(), NOW()),
('API Reference Documentation', 'Complete API reference for developers', 'technical', '/wp-content/uploads/resources/api-reference-2025.pdf', 'pdf', '12.4 MB', 'silver', 189, 1, NOW(), NOW()),
('Brand Guidelines', 'Official TeepTrak brand guidelines and assets', 'marketing', '/wp-content/uploads/resources/brand-guidelines.pdf', 'pdf', '15.8 MB', 'bronze', 156, 0, NOW(), NOW()),
('Marketing Campaign Kit', 'Ready-to-use marketing materials and templates', 'marketing', '/wp-content/uploads/resources/campaign-kit-q1-2025.zip', 'zip', '45.2 MB', 'gold', 67, 1, NOW(), NOW()),
('ROI Calculator Spreadsheet', 'Customer ROI calculation tool', 'sales', '/wp-content/uploads/resources/roi-calculator.xlsx', 'xlsx', '1.2 MB', 'bronze', 423, 1, NOW(), NOW()),
('Competitive Analysis Report', 'Detailed competitive landscape analysis', 'sales', '/wp-content/uploads/resources/competitive-analysis-2025.pdf', 'pdf', '3.8 MB', 'platinum', 34, 0, NOW(), NOW()),
('Case Study: MegaCorp', 'Success story from MegaCorp implementation', 'sales', '/wp-content/uploads/resources/case-study-megacorp.pdf', 'pdf', '2.1 MB', 'silver', 198, 1, NOW(), NOW()),
('Partner Onboarding Checklist', 'Step-by-step onboarding guide for new partners', 'training', '/wp-content/uploads/resources/onboarding-checklist.pdf', 'pdf', '0.8 MB', 'bronze', 567, 1, NOW(), NOW());

-- ===========================================
-- SAMPLE BADGES DATA
-- ===========================================

INSERT INTO wp_teeptrak_badges (partner_id, badge_name, badge_type, description, earned_date, expires_date) VALUES
(1, 'Sales Fundamentals', 'certification', 'Completed TeepTrak Sales Fundamentals course', '2024-06-15', '2025-06-15'),
(1, 'Technical Specialist', 'certification', 'Completed Technical Specialist certification', '2024-08-20', '2025-08-20'),
(1, 'Deal Closer', 'achievement', 'Closed 10+ deals in a quarter', '2024-09-30', NULL),
(2, 'Sales Fundamentals', 'certification', 'Completed TeepTrak Sales Fundamentals course', '2024-03-10', '2025-03-10'),
(2, 'Technical Specialist', 'certification', 'Completed Technical Specialist certification', '2024-04-15', '2025-04-15'),
(2, 'Solution Architect', 'certification', 'Completed Solution Architect certification', '2024-07-22', '2025-07-22'),
(2, 'Top Performer', 'achievement', 'Achieved Platinum tier status', '2024-12-01', NULL),
(2, 'Million Dollar Partner', 'achievement', 'Exceeded $1M in total revenue', '2024-11-15', NULL),
(3, 'Sales Fundamentals', 'certification', 'Completed TeepTrak Sales Fundamentals course', '2024-09-01', '2025-09-01'),
(4, 'Sales Fundamentals', 'certification', 'Completed TeepTrak Sales Fundamentals course', '2024-07-20', '2025-07-20'),
(4, 'Technical Specialist', 'certification', 'Completed Technical Specialist certification', '2024-10-05', '2025-10-05'),
(7, 'Sales Fundamentals', 'certification', 'Completed TeepTrak Sales Fundamentals course', '2024-11-10', '2025-11-10');

-- ===========================================
-- SAMPLE ACTIVITY LOG DATA
-- ===========================================

INSERT INTO wp_teeptrak_activity_log (partner_id, user_id, action, description, ip_address, created_at) VALUES
(1, 2, 'login', 'Partner logged into portal', '192.168.1.100', NOW() - INTERVAL 2 HOUR),
(1, 2, 'deal_created', 'Created new deal: MegaCorp Inc', '192.168.1.100', NOW() - INTERVAL 1 HOUR),
(2, 3, 'login', 'Partner logged into portal', '10.0.0.50', NOW() - INTERVAL 3 HOUR),
(2, 3, 'resource_download', 'Downloaded: Partner Sales Playbook', '10.0.0.50', NOW() - INTERVAL 2 HOUR),
(2, 3, 'deal_updated', 'Updated deal stage: Tech Ventures', '10.0.0.50', NOW() - INTERVAL 1 HOUR),
(3, 4, 'login', 'Partner logged into portal', '85.214.123.45', NOW() - INTERVAL 5 HOUR),
(4, 5, 'login', 'Partner logged into portal', '202.96.134.100', NOW() - INTERVAL 4 HOUR),
(4, 5, 'profile_updated', 'Updated company profile', '202.96.134.100', NOW() - INTERVAL 3 HOUR),
(7, 8, 'login', 'Partner logged into portal', '203.45.67.89', NOW() - INTERVAL 6 HOUR),
(7, 8, 'certification_completed', 'Completed: Sales Fundamentals', '203.45.67.89', NOW() - INTERVAL 5 HOUR);

-- ===========================================
-- SAMPLE NOTIFICATIONS DATA
-- ===========================================

INSERT INTO wp_teeptrak_notifications (partner_id, user_id, type, title, message, is_read, action_url, created_at) VALUES
(1, 2, 'deal', 'Deal Protection Expiring', 'Your deal with MegaCorp Inc protection expires in 30 days', 0, '/deals/?deal=1', NOW()),
(1, 2, 'commission', 'Commission Approved', 'Your commission of $6,750 has been approved for payment', 0, '/commissions/', NOW()),
(2, 3, 'system', 'New Resource Available', 'New Marketing Campaign Kit is now available for download', 0, '/resources/', NOW()),
(2, 3, 'achievement', 'Badge Earned!', 'Congratulations! You earned the Million Dollar Partner badge', 1, '/training/', NOW() - INTERVAL 1 DAY),
(3, 4, 'reminder', 'Complete Onboarding', 'Complete your onboarding checklist to unlock all features', 0, '/dashboard/', NOW()),
(4, 5, 'deal', 'Deal Closed Won', 'Your deal with Shanghai Industries has been marked as closed won', 1, '/deals/?deal=8', NOW() - INTERVAL 5 DAY),
(5, 6, 'system', 'Welcome to TeepTrak Partner Portal', 'Your partner application is being reviewed', 0, '/dashboard/', NOW()),
(7, 8, 'training', 'Certification Expiring', 'Your Sales Fundamentals certification expires in 60 days', 0, '/training/', NOW());

-- ===========================================
-- END OF SAMPLE DATA
-- ===========================================
