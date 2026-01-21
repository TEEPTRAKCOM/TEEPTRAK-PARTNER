import { NextRequest, NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { createRouteHandlerClient } from '@supabase/auth-helpers-nextjs';

/**
 * POST /api/auth/figma/disconnect
 * Disconnects the Figma account from the current user
 */
export async function POST(request: NextRequest) {
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

    // Delete Figma connection from database
    const { error: dbError } = await supabase
      .from('figma_connections')
      .delete()
      .eq('profile_id', session.user.id);

    if (dbError) {
      console.error('Database error deleting Figma connection:', dbError);
      return NextResponse.json(
        { error: 'Failed to disconnect Figma account' },
        { status: 500 }
      );
    }

    return NextResponse.json({
      success: true,
      message: 'Figma account disconnected successfully',
    });
  } catch (error) {
    console.error('Figma disconnect error:', error);
    return NextResponse.json(
      { error: 'Failed to disconnect Figma account' },
      { status: 500 }
    );
  }
}
