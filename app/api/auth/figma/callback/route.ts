import { NextRequest, NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { createRouteHandlerClient } from '@supabase/auth-helpers-nextjs';
import {
  exchangeCodeForToken,
  getFigmaUser,
  encryptToken,
  calculateExpiresAt,
} from '@/lib/figma';

/**
 * GET /api/auth/figma/callback
 * Handles the OAuth callback from Figma
 */
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams;
    const code = searchParams.get('code');
    const state = searchParams.get('state');
    const error = searchParams.get('error');
    const errorDescription = searchParams.get('error_description');

    const appUrl = process.env.NEXT_PUBLIC_APP_URL || '';

    // Handle OAuth errors
    if (error) {
      console.error('Figma OAuth error:', error, errorDescription);
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent(errorDescription || error)}`
      );
    }

    if (!code || !state) {
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent('Missing authorization code or state')}`
      );
    }

    // Retrieve and validate state
    const cookieStore = await cookies();
    const storedState = cookieStore.get('figma_oauth_state')?.value;
    const codeVerifier = cookieStore.get('figma_code_verifier')?.value;

    if (!storedState || state !== storedState) {
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent('Invalid state parameter')}`
      );
    }

    // Verify user is authenticated
    const supabase = createRouteHandlerClient({ cookies });
    const { data: { session }, error: sessionError } = await supabase.auth.getSession();

    if (sessionError || !session) {
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent('Session expired. Please sign in again.')}`
      );
    }

    // Validate environment variables
    const clientId = process.env.FIGMA_CLIENT_ID;
    const clientSecret = process.env.FIGMA_CLIENT_SECRET;

    if (!clientId || !clientSecret) {
      console.error('Missing Figma OAuth credentials');
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent('Figma OAuth not configured')}`
      );
    }

    // Exchange code for tokens
    const redirectUri = `${appUrl}/api/auth/figma/callback`;
    const tokenResponse = await exchangeCodeForToken({
      code,
      clientId,
      clientSecret,
      redirectUri,
      codeVerifier,
    });

    // Get Figma user info
    const figmaUser = await getFigmaUser(tokenResponse.access_token);

    // Encrypt tokens for storage
    const encryptionKey = clientSecret.slice(0, 32);
    const accessTokenEncrypted = encryptToken(tokenResponse.access_token, encryptionKey);
    const refreshTokenEncrypted = encryptToken(tokenResponse.refresh_token, encryptionKey);

    // Calculate expiration
    const expiresAt = calculateExpiresAt(tokenResponse.expires_in);

    // Store connection in database (upsert)
    const { error: dbError } = await supabase
      .from('figma_connections')
      .upsert({
        profile_id: session.user.id,
        figma_user_id: tokenResponse.user_id_string,
        figma_email: figmaUser.email,
        figma_handle: figmaUser.handle,
        access_token_encrypted: accessTokenEncrypted,
        refresh_token_encrypted: refreshTokenEncrypted,
        token_expires_at: expiresAt.toISOString(),
        scopes: ['files:read', 'file_variables:read'],
        connected_at: new Date().toISOString(),
      }, {
        onConflict: 'profile_id',
      });

    if (dbError) {
      console.error('Database error storing Figma connection:', dbError);
      return NextResponse.redirect(
        `${appUrl}?figma_error=${encodeURIComponent('Failed to save connection')}`
      );
    }

    // Clear OAuth cookies
    cookieStore.delete('figma_oauth_state');
    cookieStore.delete('figma_code_verifier');

    // Redirect back to app with success
    return NextResponse.redirect(`${appUrl}?figma_connected=true`);
  } catch (error) {
    console.error('Figma callback error:', error);
    const appUrl = process.env.NEXT_PUBLIC_APP_URL || '';
    return NextResponse.redirect(
      `${appUrl}?figma_error=${encodeURIComponent('Failed to complete Figma connection')}`
    );
  }
}
