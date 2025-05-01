<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stocks;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new product
     */

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_group' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'inventory_items' => 'required|array|min:1',
            'inventory_items.*.name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Begin transaction to ensure all records are created or none
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image_path')) {
                $imageFile = $request->file('image_path');
                $path = $imageFile->store('products', 'public');

                // Create product
                $product = Product::create([
                    'staff_id' => Auth::id(),
                    'image_path' => $path,
                    'product_group' => $request->product_group,
                    'product_name' => $request->product_name,
                    'product_price' => $request->product_price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create associated inventory items
                foreach ($request->inventory_items as $item) {
                    $inventory = Inventory::create([
                        'product_id' => $product->id,
                        'inventory_name' => $item['name'],
                        'total_quantity' => 0,
                    ]);
                
                    Stocks::create([
                        'inventory_id' => $inventory->id,
                        'quantity' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();

                return redirect()->route('add_product')->with('success', 'Product added successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function showProducts()
    {
        $products = Product::with('inventories')->get();
        return view('record_sales', compact('products'));
    }
}