<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockInList;
use App\Models\ProductStockInList;
use App\Models\ConsumableList;
use App\Models\ProductConsumable;
use App\Models\ProductWithStockList;
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
        $skipConsumables = $request->has('product_consumable_checkbox');
        $productRequiresStock = $request->has('product_required_stock_checkbox');

        // Validate request data
        $validator = Validator::make($request->all(), [
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_group' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            //'required_stock' => $productRequiresStock ? 'required|integer|min:1' : 'nullable',

            // Only validate consumables if the checkbox is NOT checked
            'consumable.*.name' => $skipConsumables ? 'nullable' : 'required|exists:consumable_list,id',
            'consumable.*.quantity_needed' => $skipConsumables ? 'nullable' : 'required|integer|min:1',

            'product_price' => 'required|numeric',
        ], );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
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

                if (!$skipConsumables && $request->filled('consumable')) {
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
                }

                if ($productRequiresStock) {
                    ProductWithStockList::create([
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();
                return redirect()->route('add_product')->with('success', 'Product added successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
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
    public function showProductWithStock()
    {
        $product_with_stock_list = ProductWithStockList::withCount('productStockInList')
            ->orderByRaw('required_stock < 5 desc') // put low-stock first
            ->orderBy('product_name', 'asc')
            ->paginate(8);

        return view('product_stock_list', compact('product_with_stock_list'));
    }
    public function viewProductStocks($productId)
    {
        $product_stock = ProductWithStockList::where('product_id', $productId)->firstOrFail();
        $stocks = $product_stock->productStockInList()->orderBy('id')->paginate(10);

        return view('view_stocks_product', compact('product_stock', 'stocks'));
    }
    public function productAddStock(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Add stock to stock_list
        ProductStockInList::create([
            'product_id' => $productId,
            'quantity_added' => $request->input('quantity'),
            'date_received' => now(),
        ]);

        // Recalculate total stock
        $totalQuantity = ProductStockInList::where('product_id', $productId)->sum('quantity_added');

        // Update
        ProductWithStockList::where('product_id', $productId)->update(['required_stock' => $totalQuantity]);

        return redirect()->back()->with('success', 'Stock added and total updated successfully!');
    }


    public function orderForm($id)
    {
        $product = Product::with('productConsumableNeeded')->findOrFail($id);

        $product_with_stock_list = ProductWithStockList::withCount('productStockInList')
            ->where('product_id', $id)
            ->get();

        $consumableIds = $product->productConsumableNeeded->pluck('consumable_id');

        $consumable_list = ConsumableList::withCount('stockInList')
            ->whereIn('id', $consumableIds)
            ->orderBy('consumable_name', 'asc')
            ->paginate(5);

        return view('product.order', [
            'product' => $product,
            'product_with_stock_list' => $product_with_stock_list,
            'consumable_list' => $consumable_list
        ]);
    }
    public function submitOrder(Request $request, $id, $product_group, $product_name, $product_price)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:Cash,Gcash',
        ]);

        $product = Product::findOrFail($id);
        $orderQty = $request->input('quantity');


        DB::beginTransaction();

        try {
            $consumables = ProductConsumable::where('product_id', $product->id)->get();
            $product_with_stock = ProductWithStockList::where('product_id', $product->id)->get();

            if ($consumables->isNotEmpty()) {
                foreach ($consumables as $consumable) {
                    $totalToDeduct = $consumable->quantity_needed * $orderQty;
                    $remaining = $totalToDeduct;

                    // Deduct from stock_in_list rows FIFO-style
                    $stockRows = StockInList::where('consumable_id', $consumable->consumable_id)
                        ->orderBy('id', 'asc')
                        ->get();

                    foreach ($stockRows as $stock) {
                        if ($remaining <= 0)
                            break;

                        $available = $stock->quantity_added;

                        // Adjust available based on previous usage if needed
                        $deduct = min($available, $remaining);
                        $stock->quantity_added -= $deduct;
                        $stock->save();

                        $remaining -= $deduct;
                    }

                    if ($remaining > 0) {
                        DB::rollBack();
                        return back()->with('error', "Not enough stock for {$consumable->consumable_name}");
                    }

                    // Recalculate total stock
                    $totalQuantity = StockInList::where('consumable_id', $consumable->consumable_id)->sum('quantity_added');

                    // Update inventories table
                    ConsumableList::where('id', $consumable->consumable_id)->update(['total_stock_left' => $totalQuantity]);
                }
            }

            if ($product_with_stock->isNotEmpty()) {
                //$totalToDeduct = $orderQty;
                $remaining = $orderQty;

                $stockRows = ProductStockInList::where('product_id', $product->id)
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($stockRows as $stock) {
                    if ($remaining <= 0)
                        break;

                    $available = $stock->quantity_added;
                    $deduct = min($available, $remaining);

                    $stock->quantity_added -= $deduct;
                    $stock->save();

                    $remaining -= $deduct;
                }

                if ($remaining > 0) {
                    DB::rollBack();
                    return back()->with('error', "Not enough product stock for {$product->product_name}");
                }

                $totalQuantity = ProductStockInList::where('product_id', $product->id)->sum('quantity_added');

                ProductWithStockList::where('product_id', $product->id)
                    ->update(['required_stock' => $totalQuantity]);
            }

            Orders::create([
                'staff_id' => Auth::id(),
                'product_id' => $id,
                'product_group' => $product_group,
                'product_name' => $product_name,
                'quantity_ordered' => $orderQty,
                'product_price' => $product_price,
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Order submitted and stocks deducted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong.');
        }
    }
}