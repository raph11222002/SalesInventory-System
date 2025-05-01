<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_group' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
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
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $path = $imageFile->store('products', 'public');

                // Create product
                $product = Product::create([
                    'image' => $path,
                    'product_group' => $request->product_group,
                    'product_name' => $request->product_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create associated inventory items
                foreach ($request->inventory_items as $item) {
                    Inventory::create([
                        'product_id' => $product->id,
                        'inventory_name' => $item['name'],
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