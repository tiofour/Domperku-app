@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
    
    <!-- Header & Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Riwayat Transaksi</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Filter dan pantau riwayat keuangan Anda</p>
        </div>

        <!-- Tombol Filter Cepat Rentang Waktu -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('transactions.history') }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !request('range') ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('transactions.history', array_merge(request()->query(), ['range' => 'day'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('range') == 'day' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Hari Ini
            </a>
            <a href="{{ route('transactions.history', array_merge(request()->query(), ['range' => 'week'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('range') == 'week' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Minggu Ini
            </a>
            <a href="{{ route('transactions.history', array_merge(request()->query(), ['range' => 'month'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('range') == 'month' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Bulan Ini
            </a>
        </div>
    </div>

    <!-- Form Filter Kategori -->
    <form method="GET" action="{{ route('transactions.history') }}" class="mb-6 flex gap-3">
        <!-- Menyimpan nilai filter range jika ada -->
        @if(request('range'))
            <input type="hidden" name="range" value="{{ request('range') }}">
        @endif

        <select name="category_id" onchange="this.form.submit()" class="p-2.5 rounded-xl text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-brandOrange">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    [{{ strtoupper($cat->type) }}] {{ $cat->name }}
                </option>
            @endforeach
        </select>

        @if(request('category_id') || request('range'))
            <a href="{{ route('transactions.history') }}" class="p-2.5 bg-gray-200 dark:bg-gray-700 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-200 hover:opacity-80 transition flex items-center">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- Daftar Riwayat Transaksi -->
    <div class="space-y-4">
        @forelse($transactions as $trx)
            <div class="flex justify-between items-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60 transition hover:bg-gray-100 dark:hover:bg-gray-700/50">
                <div>
                    <p class="font-bold">{{ $trx->description }}</p>
                    <p class="text-sm text-gray-500">{{ $trx->category->name }} • {{ date('d M Y', strtotime($trx->date)) }}</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="font-mono font-bold text-lg text-right">
                        @if($trx->category->type == 'income')
                            <span class="text-green-500">+ Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-brandOrange">- Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    
                    <!-- Tombol Aksi (Edit & Hapus) -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('transactions.edit', $trx->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg transition" title="Edit">
                            ✏️
                        </a>
                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-100 dark:bg-red-900/30 p-2 rounded-lg transition" title="Hapus">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <p class="text-gray-400 text-lg">Tidak ada transaksi yang cocok dengan filter ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginasi -->
    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
</div>
@endsection