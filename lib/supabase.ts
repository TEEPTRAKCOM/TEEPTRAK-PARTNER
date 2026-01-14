import { createClientComponentClient } from '@supabase/auth-helpers-nextjs';

export const supabase = createClientComponentClient();

// Types for the database
export type Partner = {
  id: string;
  profile_id: string;
  organization_id: string | null;
  region: string;
  tier: 'bronze' | 'silver' | 'gold' | 'platinum';
  status: 'pending' | 'active' | 'suspended' | 'churned';
  partner_score: number;
  agreement_signed: boolean;
  agreement_signed_at: string | null;
  onboarding_completed: boolean;
  academy_username: string | null;
  academy_password_encrypted: string | null;
  academy_activated_at: string | null;
  created_at: string;
  updated_at: string;
};

export type Deal = {
  id: string;
  partner_id: string;
  company_name: string;
  contact_name: string | null;
  contact_email: string | null;
  contact_phone: string | null;
  deal_value: number;
  currency: string;
  status: 'registered' | 'qualified' | 'proposal' | 'negotiation' | 'closed_won' | 'closed_lost';
  protection_days: number;
  protection_expires_at: string;
  notes: string | null;
  created_at: string;
  updated_at: string;
  closed_at: string | null;
};

export type TrainingModule = {
  id: string;
  category_id: string;
  title: string;
  description: string | null;
  duration_minutes: number;
  video_url: string | null;
  thumbnail_url: string | null;
  sort_order: number;
  is_required: boolean;
  created_at: string;
  updated_at: string;
};

export type Resource = {
  id: string;
  category_id: string;
  title: string;
  description: string | null;
  file_url: string;
  file_type: string | null;
  file_size: string | null;
  thumbnail_url: string | null;
  download_count: number;
  is_active: boolean;
  created_at: string;
  updated_at: string;
};

export type Commission = {
  id: string;
  partner_id: string;
  deal_id: string | null;
  amount: number;
  currency: string;
  status: 'pending' | 'approved' | 'paid' | 'rejected';
  payout_date: string | null;
  notes: string | null;
  created_at: string;
  updated_at: string;
};
