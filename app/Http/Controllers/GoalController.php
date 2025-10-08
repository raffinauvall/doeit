<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GoalController extends Controller
{
    // 🔹 Tampilkan semua goal milik user
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())->latest()->get();

        // hitung total target & total progress
        $totalTarget = $goals->sum('amount_target');
        $totalProgress = $goals->sum('amount_current');

        return view('goals.index', compact('goals', 'totalTarget', 'totalProgress'));
    }

    // 🔹 Simpan goal baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount_target' => 'required|numeric|min:0',
            'amount_current' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('goals', 'public');
        }

        Goal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'amount_target' => $request->amount_target,
            'amount_current' => $request->amount_current ?? 0,
            'photo' => $photoPath,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Goal baru berhasil ditambahkan!');
    }

    // 🔹 Update goal
    public function update(Request $request, $id)
    {
        $goal = Goal::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'amount_target' => 'required|numeric|min:0',
            'amount_current' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        // hapus foto lama kalau ada upload baru
        if ($request->hasFile('photo')) {
            if ($goal->photo && Storage::disk('public')->exists($goal->photo)) {
                Storage::disk('public')->delete($goal->photo);
            }
            $goal->photo = $request->file('photo')->store('goals', 'public');
        }

        $goal->update([
            'title' => $request->title,
            'amount_target' => $request->amount_target,
            'amount_current' => $request->amount_current ?? $goal->amount_current,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Goal berhasil diperbarui!');
    }

    // 🔹 Hapus goal
    public function destroy($id)
    {
        $goal = Goal::where('user_id', Auth::id())->findOrFail($id);

        if ($goal->photo && Storage::disk('public')->exists($goal->photo)) {
            Storage::disk('public')->delete($goal->photo);
        }

        $goal->delete();

        return redirect()->back()->with('success', 'Goal berhasil dihapus!');
    }
}
