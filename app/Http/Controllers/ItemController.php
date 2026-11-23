<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Pastikan model Item wujud

class ItemController extends Controller
{
    public function index()
{
    $items = \App\Models\Item::all(); // Pastikan pembolehubah $items dihantar
    return view('manager.items.index', compact('items'));
}

   public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ]);

    // Semak sama ada nama item yang dipilih sudah wujud dalam database
    $existingItem = Item::where('name', $request->name)->first();

    if ($existingItem) {
        // Jika item sudah ada, campurkan stok baharu dengan stok sedia ada
        $existingItem->stock += $request->stock;
        $existingItem->price = $request->price; // Kemaskini harga jika berubah
        $existingItem->save();
    } else {
        // Jika item belum wujud langsung, barulah cipta rekod/baris baru
        Item::create([
            'name'  => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
        ]);
    }

    return redirect()->back()->with('success', 'Item stock successfully updated/added!');
}

   public function edit(int $id)
{
    $itemToEdit = \App\Models\Item::findOrFail($id);
    $items = \App\Models\Item::all();

    // Pastikan anda pass pembolehubah $itemToEdit ke view
    return view('manager.items.index', compact('itemToEdit', 'items'));
}
   public function update(Request $request, $id)
{
    $request->validate([
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric',
    ]);

    $item = \App\Models\Item::findOrFail($id);

    // Tambahkan stok sedia ada dengan stok baru yang dimasukkan oleh manager
    $newStock = $item->stock + intval($request->stock);

    $item->update([
        'stock' => $newStock,
        'price' => $request->price,
    ]);

    return redirect()->route('manager.items.index')->with('success', 'Item and stock information successfully updated!');
}
    public function destroy(int $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'The item has been permanently deleted.');
    }
}
