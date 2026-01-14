# TeepTrak Partner Portal

A modern, multilingual partner portal for TeepTrak resellers and integrators.

## Features

- 🌍 **Multilingual** - Auto-detects browser language (EN, FR, ZH, DE, ES, PT, JA)
- 📊 **Dashboard** - Real-time partner performance metrics
- 🎯 **Deal Registration** - Track and manage opportunities
- 📚 **Training Academy** - LMS with progress tracking
- 🎓 **Academy Access** - Credentials for academy.teeptrak.net
- 📁 **Resource Center** - Sales materials and documentation
- 🏆 **Certification** - Quiz system with badges
- 💰 **Commissions** - Track earnings and payouts
- 🤖 **Teepy Chatbot** - AI assistant integration

## Tech Stack

- **Frontend**: Next.js 14, React 18, TypeScript
- **Styling**: Tailwind CSS
- **Icons**: Lucide React
- **Database**: Supabase (PostgreSQL)
- **Auth**: Supabase Auth
- **Deployment**: Vercel

## Quick Start

### 1. Setup Supabase

1. Create account at [supabase.com](https://supabase.com)
2. Create a new project
3. Go to SQL Editor
4. Copy contents of `supabase-schema.sql` and run it
5. Go to Settings > API and copy your keys

### 2. Configure Environment

```bash
# Copy environment example
cp .env.example .env.local

# Edit with your Supabase credentials
NEXT_PUBLIC_SUPABASE_URL=https://your-project.supabase.co
NEXT_PUBLIC_SUPABASE_ANON_KEY=your-anon-key
```

### 3. Install & Run Locally

```bash
# Install dependencies
npm install

# Run development server
npm run dev
```

Open [http://localhost:3000](http://localhost:3000)

### 4. Deploy to Vercel

1. Push code to GitHub
2. Import project in [Vercel](https://vercel.com)
3. Add environment variables
4. Deploy!

## Project Structure

```
teeptrak-partner-portal/
├── app/
│   ├── globals.css      # Global styles
│   ├── layout.tsx       # Root layout
│   └── page.tsx         # Main page
├── App.jsx              # Main React application
├── supabase-schema.sql  # Database schema
├── package.json         # Dependencies
├── tailwind.config.js   # Tailwind configuration
├── next.config.js       # Next.js configuration
└── .env.example         # Environment variables template
```

## Customization

### Teepy Chatbot Integration

The chatbot is integrated via iframe. Update the URL in `App.jsx`:

```jsx
<iframe
  src="https://your-teepy-url.com"
  className="w-full h-full border-0"
  title="Teepy Chatbot"
/>
```

### Academy Access

Partners receive credentials for academy.teeptrak.net. Credentials are stored in the `partners` table:
- `academy_username`
- `academy_password_encrypted`

### Adding Languages

1. Add translations in the `translations` object in `App.jsx`
2. Add language to `langs` array in `LanguageSwitcher`

## Supabase Configuration

### Required Setup

After running the schema, configure:

1. **Authentication**:
   - Settings > Authentication
   - Enable Email provider
   - Configure redirect URLs for your domain

2. **Row Level Security**:
   - Already configured in schema
   - Review policies in SQL Editor

3. **Storage** (for resources):
   - Create bucket: `resources`
   - Set public policy for downloads

### API Keys

- **Anon Key**: Safe for frontend (RLS protected)
- **Service Key**: Server-side only (full access)

## Vercel Deployment Checklist

- [ ] Push code to GitHub
- [ ] Create Vercel project
- [ ] Add environment variables:
  - `NEXT_PUBLIC_SUPABASE_URL`
  - `NEXT_PUBLIC_SUPABASE_ANON_KEY`
- [ ] Update Supabase redirect URLs
- [ ] Deploy

## Support

For questions about the partner portal, contact:
- **Email**: partners@teeptrak.com
- **Academy**: academy.teeptrak.net

---

Built with ❤️ for TeepTrak Partners
