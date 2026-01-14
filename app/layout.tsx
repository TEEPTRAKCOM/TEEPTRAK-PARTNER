import './globals.css';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'TeepTrak Partner Portal',
  description: 'Partner management portal for TeepTrak resellers and integrators',
  icons: {
    icon: '/favicon.ico',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
