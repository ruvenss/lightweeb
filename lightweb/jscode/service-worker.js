// Service Worker Installation
const cacheVersion = 'cache-v{{version}}';
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(cacheVersion).then((cache) => {
            return cache.addAll(['cachefiles']);
        })
    );
});
// Service Worker Activation
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.filter((cacheName) => {
                    // Delete any outdated caches
                    return cacheName !== cacheVersion;
                }).map((cacheName) => {
                    return caches.delete(cacheName);
                })
            );
        })
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});

// PWA Installation
self.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    // Save the event for later use
    deferredPrompt = event;
    // Show your custom install prompt UI here
    // e.g., display a button to install the PWA
});