@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header & Pilihan Rekap -->
    <div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Laporan Rekapitulasi</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Ringkasan pemasukan dan pengeluaran berkala</p>
        </div>

        <!-- Tombol Filter Rekap -->
        <div class="flex items-center gap-2">
            <a href="{{ route('transactions.report', ['type' => 'day']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $type == 'day' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Per Hari
            </a>
            <a href="{{ route('transactions.report', ['type' => 'week']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $type == 'week' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Per Minggu
            </a>
            <a href="{{ route('transactions.report', ['type' => 'month']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $type == 'month' ? 'bg-brandOrange text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                Per Bulan
            </a>
        </div>
    </div>

    <!-- Tabel Rekapitulasi (Format Spreadsheet) -->
    <div class="bg-white dark:bg-darkCard rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase text-gray-400">
                    <th class="py-3 px-4">
                        @if($type == 'day') Tanggal
                        @elseif($type == 'week') Minggu (Rentang Tanggal)
                        @else Bulan
                        @endif
                    </th>
                    <th class="py-3 px-4 text-right">Pengeluaran</th>
                    <th class="py-3 px-4 text-right">Pemasukan</th>
                    <th class="py-3 px-4 text-right">Sisa / Arus Kas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                @php
                    $grandExpense = 0;
                    $grandIncome = 0;
                @endphp

                @forelse($reports as $row)
                    @php
                        $cashFlow = $row->total_income - $row->total_expense;
                        $grandExpense += $row->total_expense;
                        $grandIncome += $row->total_income;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                        <td class="py-3 px-4 font-semibold">
                            @if($type == 'day')
                                {{ date('d M Y', strtotime($row->period)) }}
                            @elseif($type == 'week')
                                {{ date('d M Y', strtotime($row->start_date)) }} - {{ date('d M Y', strtotime($row->end_date)) }}
                            @else
                                {{ $row->period }}
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-brandOrange font-bold">
                            Rp {{ number_format($row->total_expense, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-green-500 font-bold">
                            Rp {{ number_format($row->total_income, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold {{ $cashFlow >= 0 ? 'text-gray-800 dark:text-gray-200' : 'text-red-500' }}">
                            Rp {{ number_format($cashFlow, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-400">Belum ada data transaksi untuk laporan ini.</td>
                    </tr>
                @endforelse
            </tbody>

            <!-- Total Keseluruhan di Paling Bawah -->
            @if(!$reports->isEmpty())
                <tfoot>
                    <tr class="border-t-2 border-gray-300 dark:border-gray-600 font-bold bg-gray-50 dark:bg-gray-800/80">
                        <td class="py-3 px-4 uppercase text-xs">Total Keseluruhan</td>
                        <td class="py-3 px-4 text-right font-mono text-brandOrange">
                            Rp {{ number_format($grandExpense, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-green-500">
                            Rp {{ number_format($grandIncome, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono {{ ($grandIncome - $grandExpense) >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            Rp {{ number_format($grandIncome - $grandExpense, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection