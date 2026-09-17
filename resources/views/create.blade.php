@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-darkCard rounded-3xl p-8 shadow-lg border border-gray-100 dark:border-gray-800 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Catat Transaksi Baru</h1>
    
    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-xs font-semibold uppercase mb-1">Kategori</label>
            <select name="category_id" required class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">
                        [{{ strtoupper($cat->type) }}] {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase mb-1">Jumlah Nominal (Rp)</label>
            <input type="number" name="amount" min="1" required placeholder="Contoh: 50000" class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase mb-1">Keterangan / Catatan</label>
            <input type="text" name="description" required placeholder="Makan siang, bensin, dll" class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase mb-1">Tanggal Transaksi</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full p-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
        </div>

        <button type="submit" class="w-full py-3 mt-4 bg-brandOrange text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition">
            Simpan Transaksi
        </button>
    </form>
</div>
@endsection