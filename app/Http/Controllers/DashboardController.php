<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ambil semua transaksi user yang login
        $transactions = Transaction::where('user_id', Auth::id())->get();

        // hitung total Income & Expense
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        // balance akhir
        $balance = $totalIncome - $totalExpense;

        // Ambil data bulanan (per bulan, selama tahun ini)
        $year = Carbon::now()->year;

        $monthlyData = Transaction::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->where('user_id', Auth::id())
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('month')
            ->get();

        $months = [];
        $incomes = [];
        $expenses = [];

        for ($i = 1; $i <= 12; $i++) {
            $data = $monthlyData->firstWhere('month', $i);
            $months[] = Carbon::create()->month($i)->format('M');
            $incomes[] = $data ? $data->total_income : 0;
            $expenses[] = $data ? $data->total_expense : 0;
        }

        // kirim semua data ke view dashboard
        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'transactions',
            'months',
            'incomes',
            'expenses'
        ));
    }
}
