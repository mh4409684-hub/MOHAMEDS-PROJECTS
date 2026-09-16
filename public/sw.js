const CACHE_NAME = 'cbe-portal-v2';
const STATIC_ASSETS = [
    '/loading.html',
    '/manifest.json'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    // Only intercept page navigation requests (HTML)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).then((response) => {
                // If server is starting up and returns gateway/service unavailable errors
                if (response.status === 502 || response.status === 503 || response.status === 504) {
                    return caches.match('/loading.html');
                }
                return response;
            }).catch(() => {
                // If network is completely unreachable or spinning up
                return caches.match('/loading.html');
            })
        );
        return;
    }

    // Default network-first strategy for other requests
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});