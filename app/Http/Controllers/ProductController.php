<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockInList;
use App\Models\ConsumableList;
use App\Models\ProductConsumable;
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

            'consumable.*.name' => 'required|exists:consumable_list,id',
            'consumable.*.quantity_needed' => 'required|integer|min:1',
        ], );

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
                foreach ($request->consumable as $item) {
                    $consumable = ConsumableList::find($item['name']);
                    $consumableName = $consumable ? $consumable->consumable_name : null;

                    ProductConsumable::create([
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'consumable_id' => $item['name'],
                        'consumable_name' => $consumableName,
                        'quantity_needed' => $item['quantity_needed'],
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
        $products = Product::with('productConsumableNeeded')->get();
        $groupedProducts = $products->groupBy('product_group');

        return view('record_sales', compact('groupedProducts'));
    }
    public function showAddProduct()
    {
        $consumable_list = ConsumableList::selectRaw('MIN(id) as id, consumable_name')
            ->groupBy('consumable_name')
            ->get();

        return view('add_product', compact('consumable_list'));
    }
    public function orderForm($id)
    {
        $product = Product::with('productConsumableNeeded')->findOrFail($id);
        return view('product.order', compact('product'));
    }
    public function submitOrder(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        $product = Product::findOrFail($id);
        $orderQty = $request->input('quantity');
    
        DB::beginTransaction();
    
        try {
            $consumables = ProductConsumable::where('product_id', $product->id)->get();
    
            foreach ($consumables as $consumable) {
                $totalToDeduct = $consumable->quantity_needed * $orderQty;
                $remaining = $totalToDeduct;
    
                // Deduct from stock_in_list rows FIFO-style
                $stockRows = StockInList::where('consumable_id', $consumable->consumable_id)
                                        ->orderBy('id', 'asc')
                                        ->get();
    
                foreach ($stockRows as $stock) {
                    if ($remaining <= 0) break;
    
                    $available = $stock->quantity_added;
    
                    // Adjust available based on previous usage if needed
    
                    $deduct = min($available, $remaining);
                    $stock->quantity_added -= $deduct;
                    $stock->save();
    
                    $remaining -= $deduct;
                }
    
                if ($remaining > 0) {
                    throw new \Exception("Not enough stock for {$consumable->consumable_name}");
                }
    
                // Update total_stock_left in ConsumableList
                $consumableList = ConsumableList::find($consumable->consumable_id);
                $consumableList->total_stock_left -= $totalToDeduct;
                $consumableList->save();
            }
    
            DB::commit();
    
            return back()->with('success', 'Order submitted and stocks deducted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}