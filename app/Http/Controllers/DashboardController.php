<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // Ambil data income & expense per bulan dari transaksi user yang login
    $monthlyData = Transaction::selectRaw('
            MONTH(date) as month,
            SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
        ')
        ->where('user_id', $userId)
        ->whereYear('date', date('Y'))
        ->groupBy(DB::raw('MONTH(date)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month'); // biar gampang dicari nanti

    // Inisialisasi semua bulan 1–12
    $months = [];
    $incomes = [];
    $expenses = [];

    for ($m = 1; $m <= 12; $m++) {
        $months[] = date('M', mktime(0, 0, 0, $m, 1));
        $incomes[] = isset($monthlyData[$m]) ? (int) $monthlyData[$m]->total_income : 0;
        $expenses[] = isset($monthlyData[$m]) ? (int) $monthlyData[$m]->total_expense : 0;
    }

    $totalIncome = array_sum($incomes);
    $totalExpense = array_sum($expenses);
    $balance = $totalIncome - $totalExpense;

    return view('dashboard', compact(
        'months',
        'incomes',
        'expenses',
        'totalIncome',
        'totalExpense',
        'balance'
    ));
}

}
