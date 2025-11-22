<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }

    // 🔹 Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $transaction->update([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }

   public function exportPdf(Request $request)
{
    // Ambil input & pastikan formatnya aman
    $month = $request->filled('month') ? (int) $request->input('month') : null;
    $year = $request->filled('year') ? (int) $request->input('year') : now()->year;

    $query = Transaction::where('user_id', Auth::id())
        ->orderBy('date', 'desc');

    if ($month && $year) {
        $query->whereMonth('date', $month)
              ->whereYear('date', $year);
    } elseif ($year) {
        $query->whereYear('date', $year);
    }

    $transactions = $query->get();

    $totalIncome = $transactions->where('type', 'income')->sum('amount');
    $totalExpense = $transactions->where('type', 'expense')->sum('amount');

    $pdf = Pdf::loadView('transactions.pdf', compact(
        'transactions', 'totalIncome', 'totalExpense', 'month', 'year'
    ));

    $fileName = 'financial_report_' . 
                ($month ? str_pad($month, 2, '0', STR_PAD_LEFT) . '_' : '') . 
                $year . '.pdf';

    return $pdf->download($fileName);
}

}
