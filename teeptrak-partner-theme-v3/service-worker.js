/**
 * TeepTrak Partner Theme V3 - Service Worker
 *
 * @package TeepTrak_Partner_Theme_V3
 */

const CACHE_NAME = 'teeptrak-partner-v3';
const OFFLINE_URL = '/offline/';

// Assets to cache on install
const PRECACHE_ASSETS = [
    '/',
    '/dashboard/',
    '/offline/',
    '/wp-content/themes/teeptrak-partner-theme-v3/style.css',
    '/wp-content/themes/teeptrak-partner-theme-v3/assets/js/main.js',
    '/wp-content/themes/teeptrak-partner-theme-v3/assets/icons/icon-192x192.png',
    '/wp-content/themes/teeptrak-partner-theme-v3/assets/icons/icon-512x512.png'
];

// Install event - cache assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Caching app shell');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip admin and API requests
    const url = new URL(event.request.url);
    if (url.pathname.startsWith('/wp-admin') ||
        url.pathname.startsWith('/wp-json') ||
        url.pathname.includes('admin-ajax.php')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Clone the response
                const responseClone = response.clone();

                // Cache successful responses
                if (response.status === 200) {
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }

                return response;
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(event.request)
                    .then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }

                        // For navigation requests, show offline page
                        if (event.request.mode === 'navigate') {
                            return caches.match(OFFLINE_URL);
                        }

                        // Return a basic offline response for other requests
                        return new Response('Offline', {
                            status: 503,
                            statusText: 'Service Unavailable'
                        });
                    });
            })
    );
});

// Push notification event
self.addEventListener('push', (event) => {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body || '',
        icon: '/wp-content/themes/teeptrak-partner-theme-v3/assets/icons/icon-192x192.png',
        badge: '/wp-content/themes/teeptrak-partner-theme-v3/assets/icons/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/dashboard/'
        },
        actions: data.actions || []
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'TeepTrak Partner', options)
    );
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url = event.notification.data?.url || '/dashboard/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Check if there's already an open window
                for (const client of clientList) {
                    if (client.url === url && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open new window
                if (clients.openWindow) {
                    return clients.openWindow(url);
                }
            })
    );
});

// Background sync for offline deal submissions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-deals') {
        event.waitUntil(syncDeals());
    }
});

async function syncDeals() {
    // Get pending deals from IndexedDB
    const pendingDeals = await getPendingDeals();

    for (const deal of pendingDeals) {
        try {
            const response = await fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'teeptrak_register_deal',
                    ...deal.data
                })
            });

            if (response.ok) {
                await removePendingDeal(deal.id);
            }
        } catch (error) {
            console.error('Failed to sync deal:', error);
        }
    }
}

// IndexedDB helpers (simplified)
function getPendingDeals() {
    return new Promise((resolve) => {
        const request = indexedDB.open('teeptrak-offline', 1);
        request.onsuccess = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains('pending-deals')) {
                resolve([]);
                return;
            }
            const tx = db.transaction('pending-deals', 'readonly');
            const store = tx.objectStore('pending-deals');
            const getAllRequest = store.getAll();
            getAllRequest.onsuccess = () => resolve(getAllRequest.result || []);
            getAllRequest.onerror = () => resolve([]);
        };
        request.onerror = () => resolve([]);
    });
}

function removePendingDeal(id) {
    return new Promise((resolve) => {
        const request = indexedDB.open('teeptrak-offline', 1);
        request.onsuccess = () => {
            const db = request.result;
            const tx = db.transaction('pending-deals', 'readwrite');
            const store = tx.objectStore('pending-deals');
            store.delete(id);
            tx.oncomplete = () => resolve();
        };
    });
}
