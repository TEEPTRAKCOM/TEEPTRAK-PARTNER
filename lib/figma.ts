/**
 * Figma OAuth 2.0 Integration Utilities
 *
 * Handles OAuth flow for connecting user Figma accounts.
 * Reference: https://developers.figma.com/docs/rest-api/authentication/
 */

// Figma OAuth endpoints
export const FIGMA_AUTH_URL = 'https://www.figma.com/oauth';
export const FIGMA_TOKEN_URL = 'https://api.figma.com/v1/oauth/token';
export const FIGMA_REFRESH_URL = 'https://api.figma.com/v1/oauth/refresh';
export const FIGMA_API_URL = 'https://api.figma.com/v1';

// Available Figma OAuth scopes
export const FIGMA_SCOPES = [
  'files:read',
  'file_variables:read',
  'file_comments:write',
  'file_dev_resources:read',
  'webhooks:write',
] as const;

export type FigmaScope = typeof FIGMA_SCOPES[number];

// Type definitions
export interface FigmaTokenResponse {
  user_id_string: string;
  access_token: string;
  token_type: 'bearer';
  expires_in: number;
  refresh_token: string;
}

export interface FigmaUser {
  id: string;
  email: string;
  handle: string;
  img_url: string;
}

export interface FigmaConnection {
  id: string;
  profile_id: string;
  figma_user_id: string;
  figma_email: string | null;
  figma_handle: string | null;
  token_expires_at: string;
  scopes: string[] | null;
  connected_at: string;
  updated_at: string;
}

/**
 * Generate PKCE code verifier (43-128 characters)
 */
export function generateCodeVerifier(): string {
  const array = new Uint8Array(64);
  crypto.getRandomValues(array);
  return base64URLEncode(array);
}

/**
 * Generate PKCE code challenge from verifier using SHA-256
 */
export async function generateCodeChallenge(verifier: string): Promise<string> {
  const encoder = new TextEncoder();
  const data = encoder.encode(verifier);
  const digest = await crypto.subtle.digest('SHA-256', data);
  return base64URLEncode(new Uint8Array(digest));
}

/**
 * Base64 URL encoding for PKCE
 */
function base64URLEncode(buffer: Uint8Array): string {
  const base64 = btoa(String.fromCharCode(...buffer));
  return base64
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/, '');
}

/**
 * Generate a random state value for OAuth security
 */
export function generateState(): string {
  const array = new Uint8Array(32);
  crypto.getRandomValues(array);
  return base64URLEncode(array);
}

/**
 * Build the Figma OAuth authorization URL
 */
export function buildAuthorizationUrl(params: {
  clientId: string;
  redirectUri: string;
  state: string;
  scopes?: FigmaScope[];
  codeChallenge?: string;
}): string {
  const { clientId, redirectUri, state, scopes = ['files:read'], codeChallenge } = params;

  const url = new URL(FIGMA_AUTH_URL);
  url.searchParams.set('client_id', clientId);
  url.searchParams.set('redirect_uri', redirectUri);
  url.searchParams.set('scope', scopes.join(','));
  url.searchParams.set('state', state);
  url.searchParams.set('response_type', 'code');

  if (codeChallenge) {
    url.searchParams.set('code_challenge', codeChallenge);
    url.searchParams.set('code_challenge_method', 'S256');
  }

  return url.toString();
}

/**
 * Exchange authorization code for access token (server-side only)
 */
export async function exchangeCodeForToken(params: {
  code: string;
  clientId: string;
  clientSecret: string;
  redirectUri: string;
  codeVerifier?: string;
}): Promise<FigmaTokenResponse> {
  const { code, clientId, clientSecret, redirectUri, codeVerifier } = params;

  // Build form data
  const body = new URLSearchParams();
  body.set('redirect_uri', redirectUri);
  body.set('code', code);
  body.set('grant_type', 'authorization_code');

  if (codeVerifier) {
    body.set('code_verifier', codeVerifier);
  }

  // Use Basic Auth with client credentials
  const credentials = Buffer.from(`${clientId}:${clientSecret}`).toString('base64');

  const response = await fetch(FIGMA_TOKEN_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': `Basic ${credentials}`,
    },
    body: body.toString(),
  });

  if (!response.ok) {
    const error = await response.text();
    throw new Error(`Failed to exchange token: ${response.status} - ${error}`);
  }

  return response.json();
}

/**
 * Refresh an expired access token (server-side only)
 */
export async function refreshAccessToken(params: {
  refreshToken: string;
  clientId: string;
  clientSecret: string;
}): Promise<Omit<FigmaTokenResponse, 'refresh_token'>> {
  const { refreshToken, clientId, clientSecret } = params;

  const body = new URLSearchParams();
  body.set('refresh_token', refreshToken);

  const credentials = Buffer.from(`${clientId}:${clientSecret}`).toString('base64');

  const response = await fetch(FIGMA_REFRESH_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': `Basic ${credentials}`,
    },
    body: body.toString(),
  });

  if (!response.ok) {
    const error = await response.text();
    throw new Error(`Failed to refresh token: ${response.status} - ${error}`);
  }

  return response.json();
}

/**
 * Get the current authenticated Figma user
 */
export async function getFigmaUser(accessToken: string): Promise<FigmaUser> {
  const response = await fetch(`${FIGMA_API_URL}/me`, {
    headers: {
      'Authorization': `Bearer ${accessToken}`,
    },
  });

  if (!response.ok) {
    const error = await response.text();
    throw new Error(`Failed to get Figma user: ${response.status} - ${error}`);
  }

  return response.json();
}

/**
 * Simple XOR encryption for token storage (use proper encryption in production)
 * Note: For production, use proper encryption like AES-256-GCM with a secure key
 */
export function encryptToken(token: string, secret: string): string {
  const key = secret.repeat(Math.ceil(token.length / secret.length)).slice(0, token.length);
  let encrypted = '';
  for (let i = 0; i < token.length; i++) {
    encrypted += String.fromCharCode(token.charCodeAt(i) ^ key.charCodeAt(i));
  }
  return Buffer.from(encrypted).toString('base64');
}

export function decryptToken(encrypted: string, secret: string): string {
  const decoded = Buffer.from(encrypted, 'base64').toString();
  const key = secret.repeat(Math.ceil(decoded.length / secret.length)).slice(0, decoded.length);
  let decrypted = '';
  for (let i = 0; i < decoded.length; i++) {
    decrypted += String.fromCharCode(decoded.charCodeAt(i) ^ key.charCodeAt(i));
  }
  return decrypted;
}

/**
 * Calculate token expiration date
 */
export function calculateExpiresAt(expiresIn: number): Date {
  return new Date(Date.now() + expiresIn * 1000);
}

/**
 * Check if token is expired (with 5-minute buffer)
 */
export function isTokenExpired(expiresAt: Date): boolean {
  const buffer = 5 * 60 * 1000; // 5 minutes
  return new Date().getTime() > expiresAt.getTime() - buffer;
}
