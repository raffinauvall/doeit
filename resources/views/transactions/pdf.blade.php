<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        h3 {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 7px 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tfoot td {
            font-weight: bold;
            background-color: #fafafa;
        }
        .summary {
            margin-top: 20px;
            width: 50%;
            float: right;
        }
        .summary td {
            padding: 6px 10px;
        }
        .text-end {
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- Title --}}
    <h2>Financial Report</h2>
    <h3>
        @if ($month && $year)
            Month of {{ \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') }} {{ $year }}
        @elseif ($year)
            Year {{ $year }}
        @else
            All Transactions
        @endif
    </h3>

    {{-- Transaction Table --}}
    <table>
        <thead>
            <tr>
                <th style="width: 15%">Date</th>
                <th style="width: 45%">Description</th>
                <th style="width: 15%">Type</th>
                <th style="width: 25%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                    <td>{{ $t->description ?? '-' }}</td>
                    <td>{{ ucfirst($t->type) }}</td>
                    <td class="text-end">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#777;">No transactions found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Summary --}}
    <table class="summary">
        <tr>
            <td>Total Income:</td>
            <td class="text-end">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Expenses:</td>
            <td class="text-end">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Final Balance:</td>
            <td class="text-end">
                Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
            </td>
        </tr>
    </table>

</body>
</html>
