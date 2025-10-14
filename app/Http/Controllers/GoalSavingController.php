<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalSaving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalSavingController extends Controller
{
    public function index($goalId)
    {
        $goal = Goal::where('users_id', Auth::id())->findOrFail($goalId);
        $savings = GoalSaving::where('goal_id', $goalId)
            ->orderBy('saved_at', 'desc')
            ->get();

        return view('goals.savings.index', compact('goal', 'savings'));
    }

    public function store(Request $request, $goalId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'saved_at' => 'nullable|date',
        ]);

        GoalSaving::create([
            'goal_id' => $goalId,
            'amount' => $request->amount,
            'note' => $request->note,
            'saved_at' => $request->date ?? now(),
        ]);

        $this->recalculateGoal($goalId);

        return back()->with('success', 'Tabungan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $saving = GoalSaving::findOrFail($id);
        return view('goals.savings.edit', compact('saving'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'saved_at' => 'nullable|date',
        ]);

        $saving = GoalSaving::findOrFail($id);
        $saving->update($request->only(['amount', 'note', 'date']));

        $this->recalculateGoal($saving->goal_id);

        return redirect()->route('goals.savings.index', $saving->goal_id)
                         ->with('success', 'Data tabungan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $saving = GoalSaving::findOrFail($id);
        $goalId = $saving->goal_id;
        $saving->delete();

        $this->recalculateGoal($goalId);

        return back()->with('success', 'Tabungan berhasil dihapus!');
    }

    private function recalculateGoal($goalId)
    {
        $total = GoalSaving::where('goal_id', $goalId)->sum('amount');
        Goal::where('id', $goalId)->update(['amount_current' => $total]);
    }
}
