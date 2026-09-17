<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>DompetKu - Catatan Keuangan</title>
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#FF5722">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#121212',
                        darkCard: '#1E1E1E',
                        brandOrange: '#FF5722',
                        brandYellow: '#FDE047'
                    }
                }
            }
        }
    </script>
    
    <!-- Script untuk mengecek LocalStorage sebelum halaman dimuat -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<!-- Tambahkan pb-24 di mobile agar konten tidak tertutup bottom navbar -->
<body class="bg-gray-100 text-gray-800 dark:bg-darkBg dark:text-gray-200 transition-colors duration-300 font-sans min-h-screen flex flex-col pb-24 md:pb-0">

    <!-- Navbar / Navigasi Atas (Desktop) -->
    <nav class="bg-white dark:bg-darkCard shadow-sm border-b border-gray-200 dark:border-gray-800 hidden md:block">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-brandOrange">DompetKu</a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('dashboard') }}" class="hover:text-brandOrange transition font-medium {{ request()->routeIs('dashboard') ? 'text-brandOrange' : '' }}">Dashboard</a>
                        <a href="{{ route('transactions.history') }}" class="hover:text-brandOrange transition font-medium {{ request()->routeIs('transactions.history') ? 'text-brandOrange' : '' }}">Riwayat</a>
                        <a href="{{ route('transactions.report') }}" class="hover:text-brandOrange transition font-medium {{ request()->routeIs('transactions.report') ? 'text-brandOrange' : '' }}">Laporan</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('transactions.create') }}" class="bg-brandOrange text-white px-4 py-2 rounded-xl font-bold text-sm shadow hover:opacity-90 transition">
                        + Transaksi
                    </a>
                    <!-- Tombol Logout Desktop -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-200 dark:bg-gray-800 px-4 py-2 rounded-xl text-sm font-bold shadow hover:scale-105 transition" title="Keluar">
                            Logout
                        </button>
                    </form>
                    
                    <button onclick="toggleDarkMode()" class="bg-gray-200 dark:bg-gray-800 p-2 rounded-full shadow hover:scale-105 transition" title="Ganti Mode">
                        🌓
                    </button>

                    
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Khusus Mobile (Menampilkan Judul, Logout, & Tombol Dark Mode) -->
    <div class="md:hidden flex justify-between items-center bg-white dark:bg-darkCard p-4 shadow-sm border-b border-gray-200 dark:border-gray-800">
        <h1 class="text-lg font-bold text-brandOrange">DompetKu</h1>
        <div class="flex items-center gap-2">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm font-bold bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-xl shadow" title="Keluar">Keluar</button>
            </form>
            <button onclick="toggleDarkMode()" class="text-xl p-1 rounded-full bg-gray-100 dark:bg-gray-800 shadow" title="Ganti Mode">🌓</button>
        </div>
    </div>

    <!-- Konten Utama (Kanvas) -->
    <main class="flex-grow p-4 md:p-8">
        <div class="max-w-6xl mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-semibold shadow">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION BAR -->
    <div class="md:hidden fixed bottom-0 left-0 z-50 w-full h-16 bg-white dark:bg-darkCard rounded-t-3xl shadow-[0_-4px_10px_rgba(0,0,0,0.05)] border-t border-gray-100 dark:border-gray-800 flex justify-around items-end px-2 pb-2">
        
        <!-- 1. Menu Dashboard -->
        <a href="{{ route('dashboard') }}" class="relative flex flex-col items-center justify-center w-1/4 h-full">
            @if(request()->routeIs('dashboard'))
                <!-- Aktif: Ikon Melayang -->
                <div class="absolute -top-6 bg-brandOrange text-white p-3 rounded-full border-4 border-gray-100 dark:border-darkBg shadow-lg transform transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-brandOrange mt-8">Home</span>
            @else
                <!-- Tidak Aktif -->
                <div class="text-gray-400 dark:text-gray-500 mb-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Home</span>
            @endif
        </a>

        <!-- 2. Menu Riwayat -->
        <a href="{{ route('transactions.history') }}" class="relative flex flex-col items-center justify-center w-1/4 h-full">
            @if(request()->routeIs('transactions.history'))
                <div class="absolute -top-6 bg-brandOrange text-white p-3 rounded-full border-4 border-gray-100 dark:border-darkBg shadow-lg transform transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-brandOrange mt-8">Riwayat</span>
            @else
                <div class="text-gray-400 dark:text-gray-500 mb-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Riwayat</span>
            @endif
        </a>

        <!-- 3. Menu Tambah (Plus) -->
        <a href="{{ route('transactions.create') }}" class="relative flex flex-col items-center justify-center w-1/4 h-full">
            @if(request()->routeIs('transactions.create'))
                <div class="absolute -top-6 bg-brandOrange text-white p-3 rounded-full border-4 border-gray-100 dark:border-darkBg shadow-lg transform transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-brandOrange mt-8">Tambah</span>
            @else
                <div class="text-gray-400 dark:text-gray-500 mb-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Tambah</span>
            @endif
        </a>

        <!-- 4. Menu Laporan -->
        <a href="{{ route('transactions.report') }}" class="relative flex flex-col items-center justify-center w-1/4 h-full">
            @if(request()->routeIs('transactions.report'))
                <div class="absolute -top-6 bg-brandOrange text-white p-3 rounded-full border-4 border-gray-100 dark:border-darkBg shadow-lg transform transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-brandOrange mt-8">Laporan</span>
            @else
                <div class="text-gray-400 dark:text-gray-500 mb-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Laporan</span>
            @endif
        </a>

    </div>

    <!-- Script Utama -->
    <script>
        function toggleDarkMode() {
            const htmlElement = document.documentElement;
            htmlElement.classList.toggle('dark'); 

            if (htmlElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }
    </script>
    
    @stack('scripts')

    <!-- Script Registrasi Service Worker PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker PWA terdaftar sukses dengan scope: ', registration.scope);
                    })
                    .catch(err => {
                        console.log('Registrasi ServiceWorker PWA gagal: ', err);
                    });
            });
        }
    </script>

    <!-- Tombol Instal PWA Kustom (Opsional) -->
    <button id="btn-install-pwa" style="display: none;" class="btn btn-primary">
        Instal Aplikasi DompetKu
    </button>

    <script>
        let deferredPrompt;
        const installBtn = document.getElementById('btn-install-pwa');

        // Menangkap event bawaan browser saat PWA siap diinstal
        window.addEventListener('beforeinstallprompt', (e) => {
            // Mencegah prompt bawaan Chrome langsung muncul
            e.preventDefault();
            deferredPrompt = e;
            
            // Tampilkan tombol instal kustom kita di layar
            if (installBtn) {
                installBtn.style.display = 'block';
            }
        });

        // Menangani aksi klik pada tombol instal kustom
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (deferredPrompt) {
                    // Tampilkan prompt instalasi PWA
                    deferredPrompt.prompt();
                    
                    // Tunggu respons dari pengguna
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`Pilihan pengguna: ${outcome}`);
                    
                    // Reset variabel prompt
                    deferredPrompt = null;
                    installBtn.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>