<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockInList;
use App\Models\Product;
use App\Models\ConsumableList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ConsumableController extends Controller
{
    /**
     * Show the form for creating a new product
     */

    /**
     * Store a newly created product in storage
     */
    public function consumableStore(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'consumable' => 'required|array',
            'consumable.*.name' => 'required|string|max:255',
        ]);

        $existingDuplicates = [];

        foreach ($request->consumable as $item) {
            $name = trim($item['name']);

            // Check if item already exists (case insensitive)
            $exists = ConsumableList::whereRaw('LOWER(consumable_name) = ?', [strtolower($name)])->exists();

            if ($exists) {
                $existingDuplicates[] = $name;
            }
        }

        if (!empty($existingDuplicates)) {
            // Return with error listing the duplicate names
            return redirect()->back()->withInput()->with('error', 'The following consumable(s) already exist: ' . implode(', ', $existingDuplicates));
        }

        // Insert new consumables
        foreach ($request->consumable as $item) {
            ConsumableList::create([
                'consumable_name' => $item['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Consumables listed successfully.');
    }
    public function showConsumables()
    {
        $consumable_list = ConsumableList::withCount('stockInList')
            ->orderByRaw('total_stock_left < 5 desc') // put low-stock first
            ->orderBy('consumable_name', 'asc') // then sort by name
            ->paginate(8);

        return view('consumable_list', compact('consumable_list'));
    }


    public function addStock(Request $request, $consumableId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Add stock to stock_list
        StockInList::create([
            'consumable_id' => $consumableId,
            'quantity_added' => $request->input('quantity'),
            'date_received' => now(),
        ]);

        // Recalculate total stock
        $totalQuantity = StockInList::where('consumable_id', $consumableId)->sum('quantity_added');

        // Update inventories table
        ConsumableList::where('id', $consumableId)->update(['total_stock_left' => $totalQuantity]);

        return redirect()->back()->with('success', 'Stock added and total updated successfully!');
    }
    // In ConsumableController.php
    public function viewConsumableStocks($consumableId)
    {
        $consumable = ConsumableList::findOrFail($consumableId);
        $stocks = $consumable->stockInList()->orderBy('id')->paginate(10);

        return view('view_stocks_consumable', compact('consumable', 'stocks'));
    }
}