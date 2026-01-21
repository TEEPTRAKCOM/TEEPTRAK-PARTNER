import { NextRequest, NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { createRouteHandlerClient } from '@supabase/auth-helpers-nextjs';
import { isTokenExpired } from '@/lib/figma';

/**
 * GET /api/auth/figma/status
 * Returns the current Figma connection status for the authenticated user
 */
export async function GET(request: NextRequest) {
  try {
    // Verify user is authenticated
    const supabase = createRouteHandlerClient({ cookies });
    const { data: { session }, error: sessionError } = await supabase.auth.getSession();

    if (sessionError || !session) {
      return NextResponse.json(
        { error: 'Unauthorized' },
        { status: 401 }
      );
    }

    // Get Figma connection from database
    const { data: connection, error: dbError } = await supabase
      .from('figma_connections')
      .select('id, figma_user_id, figma_email, figma_handle, token_expires_at, scopes, connected_at, updated_at')
      .eq('profile_id', session.user.id)
      .single();

    if (dbError) {
      if (dbError.code === 'PGRST116') {
        // No connection found
        return NextResponse.json({
          connected: false,
          connection: null,
        });
      }
      throw dbError;
    }

    // Check if token is expired
    const tokenExpiresAt = new Date(connection.token_expires_at);
    const expired = isTokenExpired(tokenExpiresAt);

    return NextResponse.json({
      connected: true,
      expired,
      connection: {
        id: connection.id,
        figmaUserId: connection.figma_user_id,
        email: connection.figma_email,
        handle: connection.figma_handle,
        expiresAt: connection.token_expires_at,
        scopes: connection.scopes,
        connectedAt: connection.connected_at,
        updatedAt: connection.updated_at,
      },
    });
  } catch (error) {
    console.error('Figma status error:', error);
    return NextResponse.json(
      { error: 'Failed to get Figma connection status' },
      { status: 500 }
    );
  }
}
