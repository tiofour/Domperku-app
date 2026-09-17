<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-darkBg dark:text-gray-200 font-sans p-4 md:p-8 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-lg bg-white dark:bg-darkCard rounded-3xl p-8 shadow-lg border border-gray-100 dark:border-gray-800">
        <h1 class="text-2xl font-bold mb-6">Edit Transaksi</h1>
        
        <!-- Formulir menggunakan metode POST, tapi di-override dengan @method('PUT') -->
        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold uppercase mb-1">Kategori</label>
                <select name="category_id" required class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
                    @foreach($categories as $cat)
                        <!-- Logika ini memastikan kategori yang tersimpan sebelumnya terpilih otomatis -->
                        <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                            [{{ strtoupper($cat->type) }}] {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase mb-1">Jumlah (Rp)</label>
                <!-- Menampilkan value amount yang ada -->
                <input type="number" name="amount" min="1" required value="{{ (int)$transaction->amount }}" class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase mb-1">Keterangan</label>
                <!-- Menampilkan value description yang ada -->
                <input type="text" name="description" required value="{{ $transaction->description }}" class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase mb-1">Tanggal</label>
                <!-- Menampilkan value date yang ada -->
                <input type="date" name="date" required value="{{ $transaction->date }}" class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
            </div>

            <div class="flex gap-4 pt-4">
                <a href="{{ route('dashboard') }}" class="w-1/3 text-center py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold rounded-xl shadow hover:opacity-90 transition">
                    Batal
                </a>
                <button type="submit" class="w-2/3 py-3 bg-brandOrange text-white font-bold rounded-xl shadow hover:opacity-90 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>