@extends('layouts.app')

@section('content')
<!-- Kartu Ringkasan Saldo -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Saldo (Kartu Kuning) -->
    <div class="bg-brandYellow text-black rounded-3xl p-6 shadow-lg flex flex-col justify-between h-44">
        <span class="text-xs font-bold uppercase tracking-wider opacity-75">Total Saldo</span>
        <span class="text-4xl font-extrabold font-mono">Rp {{ number_format($balance, 0, ',', '.') }}</span>
        <span class="text-xs font-medium">Sisa dana tersedia</span>
    </div>

    <!-- Total Pemasukan -->
    <div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 flex flex-col justify-between h-44">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pemasukan</span>
        <span class="text-3xl font-bold text-green-500 font-mono">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        <span class="text-xs text-gray-400">Total akumulasi masuk</span>
    </div>

    <!-- Total Pengeluaran (Kartu Oranye) -->
    <div class="bg-brandOrange text-white rounded-3xl p-6 shadow-lg flex flex-col justify-between h-44">
        <span class="text-xs font-bold uppercase tracking-wider opacity-90">Pengeluaran</span>
        <span class="text-3xl font-bold font-mono">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        <span class="text-xs opacity-80">Total akumulasi keluar</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Grafik Analitik -->
    <div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
        <h2 class="text-sm font-bold uppercase tracking-wider mb-4">Grafik 7 Hari Terakhir</h2>
        <canvas id="expenseChart" height="200"></canvas>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-bold uppercase tracking-wider">10 Riwayat Terbaru</h2>
            <a href="{{ route('transactions.history') }}" class="text-xs text-brandOrange font-bold hover:underline">Lihat Semua &rarr;</a>
        </div>
        
        <div class="space-y-3">
            @forelse($recentTransactions as $trx)
                <div class="flex justify-between items-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60">
                    <div>
                        <p class="font-bold text-sm">{{ $trx->description }}</p>
                        <p class="text-xs text-gray-400">{{ $trx->category->name }} • {{ $trx->date }}</p>
                    </div>
                    <div class="font-mono font-bold text-sm">
                        @if($trx->category->type == 'income')
                            <span class="text-green-500">+ Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                        @else
                            <span class="text-brandOrange">- Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

<!-- Memasukkan Script Grafik ke Master Layout -->
@push('scripts')
<script>
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const labels = @json($chartLabels);
    const incomeData = @json($incomeData);
    const expenseData = @json($expenseData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan (Rp)',
                    data: incomeData,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pengeluaran (Rp)',
                    data: expenseData,
                    borderColor: '#FF5722',
                    backgroundColor: 'rgba(255, 87, 34, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: {
                x: { grid: { display: false } },
                y: { border: { dash: [4, 4] }, grid: { color: 'rgba(150, 150, 150, 0.15)' } }
            }
        }
    });
</script>
@endpush