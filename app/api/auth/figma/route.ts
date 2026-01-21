import { NextRequest, NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { createRouteHandlerClient } from '@supabase/auth-helpers-nextjs';
import {
  buildAuthorizationUrl,
  generateCodeVerifier,
  generateCodeChallenge,
  generateState,
  FIGMA_SCOPES,
} from '@/lib/figma';

/**
 * GET /api/auth/figma
 * Initiates the Figma OAuth 2.0 flow
 */
export async function GET(request: NextRequest) {
  try {
    // Verify user is authenticated
    const supabase = createRouteHandlerClient({ cookies });
    const { data: { session }, error: sessionError } = await supabase.auth.getSession();

    if (sessionError || !session) {
      return NextResponse.json(
        { error: 'Unauthorized. Please sign in first.' },
        { status: 401 }
      );
    }

    // Validate environment variables
    const clientId = process.env.FIGMA_CLIENT_ID;
    const appUrl = process.env.NEXT_PUBLIC_APP_URL;

    if (!clientId || !appUrl) {
      console.error('Missing Figma OAuth configuration');
      return NextResponse.json(
        { error: 'Figma OAuth not configured' },
        { status: 500 }
      );
    }

    // Generate PKCE and state for security
    const codeVerifier = generateCodeVerifier();
    const codeChallenge = await generateCodeChallenge(codeVerifier);
    const state = generateState();

    // Store PKCE verifier and state in secure cookies
    const cookieStore = await cookies();

    cookieStore.set('figma_code_verifier', codeVerifier, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      maxAge: 600, // 10 minutes
      path: '/',
    });

    cookieStore.set('figma_oauth_state', state, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      maxAge: 600, // 10 minutes
      path: '/',
    });

    // Build authorization URL
    const redirectUri = `${appUrl}/api/auth/figma/callback`;
    const authUrl = buildAuthorizationUrl({
      clientId,
      redirectUri,
      state,
      scopes: ['files:read', 'file_variables:read'],
      codeChallenge,
    });

    // Redirect to Figma authorization
    return NextResponse.redirect(authUrl);
  } catch (error) {
    console.error('Figma OAuth initialization error:', error);
    return NextResponse.json(
      { error: 'Failed to initialize Figma authentication' },
      { status: 500 }
    );
  }
}
