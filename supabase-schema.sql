-- ============================================
-- TeepTrak Partner Portal - Supabase Schema
-- Version: 2.0 - With Academy & Chatbot Integration
-- ============================================

-- Enable extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================
-- CORE TABLES
-- ============================================

-- Organizations (Partner Companies)
CREATE TABLE organizations (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL,
  domain TEXT,
  industry TEXT,
  size TEXT CHECK (size IN ('startup', 'smb', 'mid-market', 'enterprise')),
  country TEXT,
  region TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- User Profiles (extends Supabase Auth)
CREATE TABLE profiles (
  id UUID PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  organization_id UUID REFERENCES organizations(id),
  email TEXT NOT NULL,
  full_name TEXT,
  avatar_url TEXT,
  phone TEXT,
  job_title TEXT,
  preferred_language TEXT DEFAULT 'en',
  timezone TEXT DEFAULT 'Europe/Paris',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Partners (main partner data)
CREATE TABLE partners (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  profile_id UUID REFERENCES profiles(id) ON DELETE CASCADE,
  organization_id UUID REFERENCES organizations(id),
  region TEXT NOT NULL DEFAULT 'fr',
  tier TEXT DEFAULT 'bronze' CHECK (tier IN ('bronze', 'silver', 'gold', 'platinum')),
  status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'active', 'suspended', 'churned')),
  partner_score INTEGER DEFAULT 0,
  agreement_signed BOOLEAN DEFAULT FALSE,
  agreement_signed_at TIMESTAMPTZ,
  onboarding_completed BOOLEAN DEFAULT FALSE,
  assigned_manager_id UUID,
  
  -- Academy Credentials
  academy_username TEXT,
  academy_password_encrypted TEXT,
  academy_activated_at TIMESTAMPTZ,
  
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- ONBOARDING & TRAINING
-- ============================================

-- Onboarding Steps Definition
CREATE TABLE onboarding_steps (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  step_order INTEGER NOT NULL,
  step_key TEXT NOT NULL UNIQUE, -- 'application', 'welcome', 'training', etc.
  is_required BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Insert default onboarding steps
INSERT INTO onboarding_steps (step_order, step_key, is_required) VALUES
  (1, 'application', true),
  (2, 'welcome', true),
  (3, 'training', true),
  (4, 'quiz', true),
  (5, 'agreement', true),
  (6, 'kickoff', true),
  (7, 'firstDeal', false);

-- Partner Onboarding Progress
CREATE TABLE partner_onboarding (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  step_id UUID REFERENCES onboarding_steps(id),
  completed BOOLEAN DEFAULT FALSE,
  completed_at TIMESTAMPTZ,
  UNIQUE(partner_id, step_id)
);

-- Training Categories
CREATE TABLE training_categories (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  description TEXT,
  sort_order INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO training_categories (name, slug, sort_order) VALUES
  ('Onboarding', 'onboarding', 1),
  ('Product Knowledge', 'product', 2),
  ('Sales Training', 'sales', 3),
  ('Technical Training', 'technical', 4);

-- Training Modules
CREATE TABLE training_modules (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  category_id UUID REFERENCES training_categories(id),
  title TEXT NOT NULL,
  description TEXT,
  duration_minutes INTEGER DEFAULT 0,
  video_url TEXT,
  thumbnail_url TEXT,
  sort_order INTEGER DEFAULT 0,
  is_required BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Partner Training Progress
CREATE TABLE partner_training (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  module_id UUID REFERENCES training_modules(id) ON DELETE CASCADE,
  status TEXT DEFAULT 'not_started' CHECK (status IN ('not_started', 'in_progress', 'completed')),
  progress_percent INTEGER DEFAULT 0 CHECK (progress_percent >= 0 AND progress_percent <= 100),
  started_at TIMESTAMPTZ,
  completed_at TIMESTAMPTZ,
  UNIQUE(partner_id, module_id)
);

-- ============================================
-- CERTIFICATION & QUIZZES
-- ============================================

-- Quizzes
CREATE TABLE quizzes (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  title TEXT NOT NULL,
  description TEXT,
  passing_score INTEGER DEFAULT 80,
  time_limit_minutes INTEGER DEFAULT 30,
  max_attempts INTEGER DEFAULT 3,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Quiz Questions
CREATE TABLE quiz_questions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  quiz_id UUID REFERENCES quizzes(id) ON DELETE CASCADE,
  question_text TEXT NOT NULL,
  question_type TEXT DEFAULT 'multiple_choice' CHECK (question_type IN ('multiple_choice', 'true_false', 'multi_select')),
  options JSONB NOT NULL, -- [{id: 1, text: '...', is_correct: true}]
  points INTEGER DEFAULT 1,
  sort_order INTEGER DEFAULT 0
);

-- Partner Quiz Attempts
CREATE TABLE partner_quiz_attempts (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  quiz_id UUID REFERENCES quizzes(id) ON DELETE CASCADE,
  score INTEGER,
  passed BOOLEAN,
  answers JSONB, -- {question_id: selected_option_id}
  started_at TIMESTAMPTZ DEFAULT NOW(),
  completed_at TIMESTAMPTZ,
  time_spent_seconds INTEGER
);

-- ============================================
-- DEALS & PIPELINE
-- ============================================

-- Deals
CREATE TABLE deals (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  company_name TEXT NOT NULL,
  contact_name TEXT,
  contact_email TEXT,
  contact_phone TEXT,
  deal_value DECIMAL(12, 2) DEFAULT 0,
  currency TEXT DEFAULT 'EUR',
  status TEXT DEFAULT 'registered' CHECK (status IN ('registered', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost')),
  protection_days INTEGER DEFAULT 90,
  protection_expires_at TIMESTAMPTZ,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  closed_at TIMESTAMPTZ
);

-- Deal Activity Log
CREATE TABLE deal_activities (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  deal_id UUID REFERENCES deals(id) ON DELETE CASCADE,
  activity_type TEXT NOT NULL,
  description TEXT,
  created_by UUID REFERENCES profiles(id),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- COMMISSIONS
-- ============================================

-- Commission Rules per Tier
CREATE TABLE commission_rules (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  tier TEXT NOT NULL CHECK (tier IN ('bronze', 'silver', 'gold', 'platinum')),
  rate_percent DECIMAL(5, 2) NOT NULL,
  min_deal_value DECIMAL(12, 2) DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO commission_rules (tier, rate_percent) VALUES
  ('bronze', 15.00),
  ('silver', 20.00),
  ('gold', 25.00),
  ('platinum', 30.00);

-- Commission Payouts
CREATE TABLE commissions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  deal_id UUID REFERENCES deals(id) ON DELETE SET NULL,
  amount DECIMAL(12, 2) NOT NULL,
  currency TEXT DEFAULT 'EUR',
  status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'paid', 'rejected')),
  payout_date DATE,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- RESOURCES
-- ============================================

-- Resource Categories
CREATE TABLE resource_categories (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  icon TEXT,
  sort_order INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO resource_categories (name, slug, icon, sort_order) VALUES
  ('Sales Materials', 'sales', '📊', 1),
  ('Marketing Assets', 'marketing', '🎨', 2),
  ('Technical Docs', 'technical', '⚙️', 3),
  ('Legal', 'legal', '📜', 4),
  ('Templates', 'templates', '📝', 5);

-- Resources
CREATE TABLE resources (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  category_id UUID REFERENCES resource_categories(id),
  title TEXT NOT NULL,
  description TEXT,
  file_url TEXT NOT NULL,
  file_type TEXT,
  file_size TEXT,
  thumbnail_url TEXT,
  download_count INTEGER DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Resource Downloads (tracking)
CREATE TABLE resource_downloads (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  resource_id UUID REFERENCES resources(id) ON DELETE CASCADE,
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  downloaded_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- MEETINGS & SCHEDULING
-- ============================================

-- Meetings
CREATE TABLE meetings (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  partner_id UUID REFERENCES partners(id) ON DELETE CASCADE,
  manager_id UUID REFERENCES profiles(id),
  meeting_type TEXT DEFAULT 'kickoff' CHECK (meeting_type IN ('kickoff', 'review', 'support', 'training')),
  scheduled_at TIMESTAMPTZ NOT NULL,
  duration_minutes INTEGER DEFAULT 30,
  status TEXT DEFAULT 'scheduled' CHECK (status IN ('scheduled', 'completed', 'cancelled', 'no_show')),
  meeting_url TEXT,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- NOTIFICATIONS
-- ============================================

CREATE TABLE notifications (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  profile_id UUID REFERENCES profiles(id) ON DELETE CASCADE,
  title TEXT NOT NULL,
  message TEXT,
  type TEXT DEFAULT 'info' CHECK (type IN ('info', 'success', 'warning', 'error')),
  link TEXT,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- ACTIVITY LOGS (AUDIT)
-- ============================================

CREATE TABLE activity_logs (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  profile_id UUID REFERENCES profiles(id),
  entity_type TEXT NOT NULL,
  entity_id UUID,
  action TEXT NOT NULL,
  details JSONB,
  ip_address INET,
  user_agent TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================
-- MULTILINGUAL SUPPORT
-- ============================================

-- Translation Namespaces
CREATE TABLE translation_namespaces (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL UNIQUE,
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO translation_namespaces (name, description) VALUES
  ('common', 'Common UI elements'),
  ('nav', 'Navigation'),
  ('dashboard', 'Dashboard page'),
  ('deals', 'Deal registration'),
  ('training', 'Training module'),
  ('academy', 'Academy access'),
  ('resources', 'Resource center'),
  ('commissions', 'Commissions'),
  ('quiz', 'Certification quiz'),
  ('agreement', 'Partner agreement'),
  ('kickoff', 'Meeting scheduling'),
  ('chat', 'Teepy chatbot');

-- Translation Keys
CREATE TABLE translation_keys (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  namespace_id UUID REFERENCES translation_namespaces(id) ON DELETE CASCADE,
  key TEXT NOT NULL,
  description TEXT,
  UNIQUE(namespace_id, key)
);

-- Translations
CREATE TABLE translations (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  key_id UUID REFERENCES translation_keys(id) ON DELETE CASCADE,
  language TEXT NOT NULL,
  value TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(key_id, language)
);

-- ============================================
-- ROW LEVEL SECURITY (RLS)
-- ============================================

-- Enable RLS
ALTER TABLE profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE partners ENABLE ROW LEVEL SECURITY;
ALTER TABLE partner_onboarding ENABLE ROW LEVEL SECURITY;
ALTER TABLE partner_training ENABLE ROW LEVEL SECURITY;
ALTER TABLE partner_quiz_attempts ENABLE ROW LEVEL SECURITY;
ALTER TABLE deals ENABLE ROW LEVEL SECURITY;
ALTER TABLE commissions ENABLE ROW LEVEL SECURITY;
ALTER TABLE notifications ENABLE ROW LEVEL SECURITY;
ALTER TABLE resource_downloads ENABLE ROW LEVEL SECURITY;
ALTER TABLE meetings ENABLE ROW LEVEL SECURITY;

-- Profiles: Users can only see their own profile
CREATE POLICY "Users can view own profile" ON profiles
  FOR SELECT USING (auth.uid() = id);

CREATE POLICY "Users can update own profile" ON profiles
  FOR UPDATE USING (auth.uid() = id);

-- Partners: Users can only see their own partner data
CREATE POLICY "Partners can view own data" ON partners
  FOR SELECT USING (profile_id = auth.uid());

CREATE POLICY "Partners can update own data" ON partners
  FOR UPDATE USING (profile_id = auth.uid());

-- Deals: Partners can only see their own deals
CREATE POLICY "Partners can view own deals" ON deals
  FOR SELECT USING (
    partner_id IN (SELECT id FROM partners WHERE profile_id = auth.uid())
  );

CREATE POLICY "Partners can insert own deals" ON deals
  FOR INSERT WITH CHECK (
    partner_id IN (SELECT id FROM partners WHERE profile_id = auth.uid())
  );

CREATE POLICY "Partners can update own deals" ON deals
  FOR UPDATE USING (
    partner_id IN (SELECT id FROM partners WHERE profile_id = auth.uid())
  );

-- Notifications: Users can only see their own notifications
CREATE POLICY "Users can view own notifications" ON notifications
  FOR SELECT USING (profile_id = auth.uid());

CREATE POLICY "Users can update own notifications" ON notifications
  FOR UPDATE USING (profile_id = auth.uid());

-- Public access for resources (read-only)
CREATE POLICY "Anyone can view resources" ON resources
  FOR SELECT USING (is_active = true);

CREATE POLICY "Anyone can view resource categories" ON resource_categories
  FOR SELECT USING (true);

CREATE POLICY "Anyone can view training modules" ON training_modules
  FOR SELECT USING (true);

CREATE POLICY "Anyone can view training categories" ON training_categories
  FOR SELECT USING (true);

CREATE POLICY "Anyone can view quizzes" ON quizzes
  FOR SELECT USING (is_active = true);

CREATE POLICY "Anyone can view translations" ON translations
  FOR SELECT USING (true);

-- ============================================
-- FUNCTIONS & TRIGGERS
-- ============================================

-- Update timestamp function
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Apply to tables
CREATE TRIGGER update_profiles_updated_at BEFORE UPDATE ON profiles
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

CREATE TRIGGER update_partners_updated_at BEFORE UPDATE ON partners
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

CREATE TRIGGER update_deals_updated_at BEFORE UPDATE ON deals
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

CREATE TRIGGER update_commissions_updated_at BEFORE UPDATE ON commissions
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

-- Function to calculate partner score
CREATE OR REPLACE FUNCTION calculate_partner_score(p_partner_id UUID)
RETURNS INTEGER AS $$
DECLARE
  score INTEGER := 0;
  training_progress INTEGER;
  deal_count INTEGER;
  closed_won_count INTEGER;
BEGIN
  -- Training completion (max 30 points)
  SELECT COALESCE(AVG(progress_percent), 0) INTO training_progress
  FROM partner_training WHERE partner_id = p_partner_id;
  score := score + (training_progress * 0.3)::INTEGER;
  
  -- Deal count (max 30 points)
  SELECT COUNT(*) INTO deal_count FROM deals WHERE partner_id = p_partner_id;
  score := score + LEAST(deal_count * 5, 30);
  
  -- Closed won deals (max 40 points)
  SELECT COUNT(*) INTO closed_won_count FROM deals 
  WHERE partner_id = p_partner_id AND status = 'closed_won';
  score := score + LEAST(closed_won_count * 10, 40);
  
  RETURN LEAST(score, 100);
END;
$$ LANGUAGE plpgsql;

-- Function to set deal protection expiry
CREATE OR REPLACE FUNCTION set_deal_protection()
RETURNS TRIGGER AS $$
BEGIN
  NEW.protection_expires_at := NEW.created_at + (NEW.protection_days || ' days')::INTERVAL;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER set_deal_protection_trigger BEFORE INSERT ON deals
  FOR EACH ROW EXECUTE FUNCTION set_deal_protection();

-- Function to create profile on user signup
CREATE OR REPLACE FUNCTION handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO profiles (id, email, full_name)
  VALUES (
    NEW.id,
    NEW.email,
    COALESCE(NEW.raw_user_meta_data->>'full_name', split_part(NEW.email, '@', 1))
  );
  RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE TRIGGER on_auth_user_created
  AFTER INSERT ON auth.users
  FOR EACH ROW EXECUTE FUNCTION handle_new_user();

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

CREATE INDEX idx_partners_profile ON partners(profile_id);
CREATE INDEX idx_partners_organization ON partners(organization_id);
CREATE INDEX idx_partners_region ON partners(region);
CREATE INDEX idx_partners_tier ON partners(tier);
CREATE INDEX idx_partners_status ON partners(status);

CREATE INDEX idx_deals_partner ON deals(partner_id);
CREATE INDEX idx_deals_status ON deals(status);
CREATE INDEX idx_deals_created ON deals(created_at DESC);

CREATE INDEX idx_commissions_partner ON commissions(partner_id);
CREATE INDEX idx_commissions_status ON commissions(status);

CREATE INDEX idx_notifications_profile ON notifications(profile_id);
CREATE INDEX idx_notifications_unread ON notifications(profile_id, is_read) WHERE is_read = false;

CREATE INDEX idx_activity_logs_profile ON activity_logs(profile_id);
CREATE INDEX idx_activity_logs_entity ON activity_logs(entity_type, entity_id);
CREATE INDEX idx_activity_logs_created ON activity_logs(created_at DESC);

CREATE INDEX idx_translations_language ON translations(language);

-- ============================================
-- SAMPLE DATA (Optional - for testing)
-- ============================================

-- Insert sample training modules
INSERT INTO training_modules (category_id, title, description, duration_minutes, sort_order, is_required) VALUES
  ((SELECT id FROM training_categories WHERE slug = 'onboarding'), 'Welcome to TeepTrak', 'Introduction to the partner program', 5, 1, true),
  ((SELECT id FROM training_categories WHERE slug = 'product'), 'Product Deep Dive', 'Comprehensive product overview', 25, 1, true),
  ((SELECT id FROM training_categories WHERE slug = 'product'), 'OEE Fundamentals', 'Understanding Overall Equipment Effectiveness', 20, 2, true),
  ((SELECT id FROM training_categories WHERE slug = 'sales'), 'Sales Methodology', 'TeepTrak sales approach and techniques', 30, 1, true),
  ((SELECT id FROM training_categories WHERE slug = 'sales'), 'ROI Calculator Training', 'How to use the ROI calculator', 15, 2, false),
  ((SELECT id FROM training_categories WHERE slug = 'technical'), 'Technical Implementation', 'Installation and configuration guide', 40, 1, false);

-- Insert sample resources
INSERT INTO resources (category_id, title, description, file_url, file_type, file_size) VALUES
  ((SELECT id FROM resource_categories WHERE slug = 'sales'), 'Sales Presentation 2025', 'Updated sales deck', '/files/sales-deck-2025.pptx', 'pptx', '5.2 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'sales'), 'Competitive Battlecards', 'Competitor analysis', '/files/battlecards.pdf', 'pdf', '1.8 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'sales'), 'ROI Calculator', 'Customer ROI spreadsheet', '/files/roi-calculator.xlsx', 'xlsx', '890 KB'),
  ((SELECT id FROM resource_categories WHERE slug = 'marketing'), 'Brand Guidelines', 'TeepTrak brand standards', '/files/brand-guide.pdf', 'pdf', '12 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'marketing'), 'Product Brochure', 'Customer-facing brochure', '/files/brochure.pdf', 'pdf', '4.5 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'technical'), 'Technical Specifications', 'Product specs and requirements', '/files/tech-specs.pdf', 'pdf', '2.1 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'technical'), 'Integration Guide', 'API and integration documentation', '/files/integration.pdf', 'pdf', '3.2 MB'),
  ((SELECT id FROM resource_categories WHERE slug = 'legal'), 'Partner Agreement Template', 'Standard partnership agreement', '/files/agreement.pdf', 'pdf', '450 KB');

-- Insert sample quiz
INSERT INTO quizzes (title, description, passing_score, time_limit_minutes, max_attempts) VALUES
  ('TeepTrak Certification', 'Core certification for all partners', 80, 30, 3);
