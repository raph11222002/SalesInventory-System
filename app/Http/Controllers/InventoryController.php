<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::latest()->paginate(10);
        
        return view('inventory_log', compact('inventories'));
    }
    public function show(Inventory $inventory)
    {
        return view('inventories.show', compact('inventory'));
    }
    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'inventory_name' => 'required|string|max:255',
        ]);

        $inventory->update([
            'inventory_name' => $request->inventory_name,
        ]);

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory updated successfully.');
    }
}