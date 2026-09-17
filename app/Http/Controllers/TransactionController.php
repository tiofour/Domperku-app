<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // --- 1. HALAMAN DASHBOARD ---
    public function index()
    {
        // Hitung total
        $totalIncome = Transaction::whereHas('category', function ($q) { $q->where('type', 'income'); })->sum('amount');
        $totalExpense = Transaction::whereHas('category', function ($q) { $q->where('type', 'expense'); })->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Ambil riwayat terbaru (dibatasi 10)
        $recentTransactions = Transaction::with('category')->orderBy('date', 'desc')->take(10)->get();

        // Data Grafik Ringkas 7 Hari
        $chartLabels = collect();
        $incomeData = collect();
        $expenseData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels->push($date);
            $incomeData->push(Transaction::whereDate('date', $date)->whereHas('category', function ($q) { $q->where('type', 'income'); })->sum('amount'));
            $expenseData->push(Transaction::whereDate('date', $date)->whereHas('category', function ($q) { $q->where('type', 'expense'); })->sum('amount'));
        }

        // Return view HANYA dipanggil satu kali di akhir fungsi
        return view('dashboard', compact(
            'balance', 
            'totalIncome', 
            'totalExpense', 
            'recentTransactions', 
            'chartLabels', 
            'incomeData', 
            'expenseData'
        ));
    }

    // --- 2. HALAMAN RIWAYAT LENGKAP ---
    // --- 2. HALAMAN RIWAYAT LENGKAP (DENGAN FILTER) ---
    public function history(Request $request)
    {
        // 1. Buat query dasar mengambil data transaksi beserta kategorinya
        $query = Transaction::with('category');

        // 2. Filter Berdasarkan Waktu (pilihan: day, week, month)
        if ($request->has('range') && $request->range != '') {
            if ($request->range == 'day') {
                $query->whereDate('date', now()->today());
            } elseif ($request->range == 'week') {
                $query->whereBetween('date', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')]);
            } elseif ($request->range == 'month') {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            }
        }

        // 3. Filter Berdasarkan Kategori (jika memilih kategori tertentu)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // 4. Urutkan dari tanggal terbaru dan bagi halaman (15 data per halaman)
        // Note: withQueryString() memastikan filter tidak hilang saat klik halaman berikutnya
        $transactions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        // 5. Ambil semua kategori untuk isi pilihan dropdown filter
        $categories = Category::all();

        return view('history', compact('transactions', 'categories'));
    }

    // --- HALAMAN LAPORAN KEUANGAN (BERBENTUK TABEL REKAP) ---
    public function report(Request $request)
    {
        $type = $request->query('type', 'month');

        // Ambil data transaksi dasar yang sudah diurutkan dari yang terbaru
        $query = Transaction::with('category')->orderBy('date', 'desc');

        if ($type == 'day') {
            // Rekap Per Hari (Ambil 30 hari terakhir agar memori tidak berat)
            $transactions = $query->where('date', '>=', now()->subDays(30))->get();

            // Kelompokkan data menggunakan Laravel Collection
            $reports = $transactions->groupBy(function ($trx) {
                return \Carbon\Carbon::parse($trx->date)->format('Y-m-d');
            })->map(function ($group, $date) {
                return (object) [
                    'period'        => $date,
                    'total_income'  => $group->where('category.type', 'income')->sum('amount'),
                    'total_expense' => $group->where('category.type', 'expense')->sum('amount'),
                ];
            })->values();

        } elseif ($type == 'week') {
            // Rekap Per Minggu
            $transactions = $query->get();

            $reports = $transactions->groupBy(function ($trx) {
                // Kelompokkan berdasarkan Tahun dan Nomor Minggu (Contoh: 2026-38)
                return \Carbon\Carbon::parse($trx->date)->format('o-W');
            })->map(function ($group) {
                // Cari tanggal paling awal dan paling akhir di minggu tersebut
                $firstDate = \Carbon\Carbon::parse($group->min('date'))->startOfWeek();
                $lastDate = \Carbon\Carbon::parse($group->max('date'))->endOfWeek();

                return (object) [
                    'start_date'    => $firstDate->format('Y-m-d'),
                    'end_date'      => $lastDate->format('Y-m-d'),
                    'total_income'  => $group->where('category.type', 'income')->sum('amount'),
                    'total_expense' => $group->where('category.type', 'expense')->sum('amount'),
                ];
            })->values();

        } else {
            // Rekap Per Bulan (Default)
            $transactions = $query->get();

            $reports = $transactions->groupBy(function ($trx) {
                // Kelompokkan berdasarkan Tahun-Bulan (Contoh: 2026-09)
                return \Carbon\Carbon::parse($trx->date)->format('Y-m');
            })->map(function ($group, $monthKey) {
                // Ubah format "2026-09" menjadi "September 2026"
                $periodName = \Carbon\Carbon::createFromFormat('Y-m', $monthKey)->format('F Y');

                return (object) [
                    'period'        => $periodName,
                    'total_income'  => $group->where('category.type', 'income')->sum('amount'),
                    'total_expense' => $group->where('category.type', 'expense')->sum('amount'),
                ];
            })->values();
        }

        return view('report', compact('reports', 'type'));
    }

    // --- 3. HALAMAN FORM TAMBAH TRANSAKSI ---
    public function create()
    {
        $categories = Category::all();
        return view('create', compact('categories'));
    }

    // --- 4. SIMPAN DATA (CREATE) ---
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date'        => 'required|date',
        ]);

        Transaction::create([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // --- 5. HALAMAN EDIT DATA ---
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $categories = Category::all();
        return view('edit', compact('transaction', 'categories'));
    }

    // --- 6. PROSES UPDATE DATA ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'date'        => 'required|date',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // --- 7. PROSES HAPUS DATA ---
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dihapus!');
    }
}