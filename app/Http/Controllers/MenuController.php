<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Ingredient;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu.index-resep');
    }

    public function create()
    {
        return view('menu.buat-resep');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_resep' => 'required|string|max:255',
            'bahan' => 'required|array',
            'bahan.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:0',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'jumlah_porsi' => 'required|array',
            'jumlah_porsi.*' => 'required|integer|min:1',
        ]);

        // Simpan data resep dan bahan-bahan
        $recipe = Recipe::create([
            'nama_resep' => $validated['nama_resep'],
        ]);

        foreach ($validated['bahan'] as $index => $bahan) {
            Ingredient::create([
                'recipe_id' => $recipe->id,
                'bahan' => $bahan,
                'qty' => $validated['qty'][$index],
                'harga' => $validated['harga'][$index],
                'jumlah_porsi' => $validated['jumlah_porsi'][$index],
            ]);
        }
        
        return redirect()->route('menu.index')->with('success', 'Resep berhasil disimpan!');
    }
}
