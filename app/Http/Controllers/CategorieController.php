<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    public function index() {
        $categories = Category::where('users_id', Auth::id())->get();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        Category::create([
            'name' => $request->name,
            'tipe' => $request->tipe,
            'users_id' => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Request $request, $id) {
        $request->validate([
            'name' => 'string|max:100',
            'tipe' => 'in:pemasukan, pengeluaran',
        ]);

        $categories = Category::findOrFail($id);
        $categories->update([
            'name' => $request->name,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Request $request, $id) {
        $categories = Category::findOrFail($id);
        $categories->delete();
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
