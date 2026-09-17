<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DompetKu</title>
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
</head>
<body class="bg-gray-100 dark:bg-darkBg text-gray-800 dark:text-gray-200 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white dark:bg-darkCard rounded-3xl p-8 shadow-2xl border border-gray-100 dark:border-gray-800">
        <!-- Logo & Judul -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-brandOrange mb-2">DompetKu</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan masuk untuk mencatat keuangan Anda</p>
        </div>

        <!-- Pesan Error (Jika Salah Password) -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-xl text-sm font-semibold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase mb-2">Email</label>
                <input type="email" name="email" required placeholder="Email Anda" class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase mb-2">Kata Sandi</label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi" class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange transition">
            </div>

            <button type="submit" class="w-full py-4 bg-brandOrange text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 transition transform hover:-translate-y-1">
                Masuk Sekarang
            </button>
        </form>
    </div>

</body>
</html>