<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::selectRaw('MIN(id) as id, inventory_name')
            ->groupBy('inventory_name')
            ->orderBy('id', 'asc')
            ->paginate(8);

        return view('inventory_log', compact('inventories'));
    }

    public function addStock(Request $request, $inventoryId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Add stock to stock_list
        Stocks::create([
            'inventory_id' => $inventoryId,
            'quantity' => $request->input('quantity'),
        ]);
    
        // Recalculate total stock
        $totalQuantity = Stocks::where('inventory_id', $inventoryId)->sum('quantity');
    
        // Update inventories table
        Inventory::where('id', $inventoryId)->update(['total_quantity' => $totalQuantity]);
    
        return redirect()->back()->with('success', 'Stock added and total updated successfully!');
    }
}