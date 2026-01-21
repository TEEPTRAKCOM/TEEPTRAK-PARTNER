/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        // TEEPTRAK BRAND COLORS (from Canva Brand Kit)
        tt: {
          // Primary Brand Colors
          red: {
            DEFAULT: '#eb352b',
            light: '#ff674c',
            dark: '#c42d25',
          },
          // Dark/Neutral
          dark: {
            DEFAULT: '#232120',
            alt: '#272222',
          },
          // Gray Scale
          gray: {
            50: '#f4f9fd',
            100: '#f5f6f5',
            200: '#ebebeb',
            300: '#d9dbd6',
            400: '#dad9d6',
            500: '#7a7775',
            600: '#5f5b59',
            700: '#4b4846',
          },
          // Tier Colors
          bronze: '#CD7F32',
          silver: '#A8A8A8',
          gold: '#FFD700',
          platinum: '#E5E4E2',
          // Status Colors
          success: '#22C55E',
          warning: '#F59E0B',
          error: '#EF4444',
          info: '#3B82F6',
        },
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      boxShadow: {
        'tt-sm': '0 2px 8px rgba(0, 0, 0, 0.06)',
        'tt-md': '0 4px 20px rgba(0, 0, 0, 0.08)',
        'tt-lg': '0 12px 40px rgba(0, 0, 0, 0.12)',
      },
      borderRadius: {
        'tt-sm': '8px',
        'tt-md': '12px',
        'tt-lg': '20px',
        'tt-xl': '28px',
      },
    },
  },
  plugins: [],
};
