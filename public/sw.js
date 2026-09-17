const CACHE_NAME = 'dompetku-cache-v1';
const urlsToCache = [
    '/',
    '/login',
    '/manifest.json'
];

// 1. Proses Install: Menyimpan aset dasar ke dalam cache HP
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Cache berhasil dibuka');
                return cache.addAll(urlsToCache);
            })
    );
});

// 2. Proses Fetch: Mengambil data dari cache jika ada, jika tidak ambil dari internet
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response; // Gunakan versi cache agar cepat
                }
                return fetch(event.request); // Ambil dari server
            })
    );
});