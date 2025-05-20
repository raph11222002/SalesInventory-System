<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
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
                    'admin_id' => Auth::guard('web')->id(),
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
        $data = [];

        if (Auth::guard('web')->check()) {
            $data['user'] = Auth::guard('web')->user();
            // handle web user
        } elseif (Auth::guard('staff')->check()) {
            $data['staff'] = Auth::guard('staff')->user();
            // handle staff user
        } else {
            // optional: redirect to login or abort
            return redirect()->route('welcome'); // or custom logic
        }

        $products = Product::with('productConsumableNeeded')
            ->where('is_active', 'available')
            ->get();
        $groupedProducts = $products->groupBy('product_group');

        return view('record_sales', compact('data', 'groupedProducts'));
    }
    public function showAddProduct()
    {
        $consumable_list = ConsumableList::selectRaw('MIN(id) as id, consumable_name')
            ->groupBy('consumable_name')
            ->get();

        return view('add_product', compact('consumable_list'));
    }
    public function toggleStatus($id)
    {
        DB::table('product_list')->where('id', $id)->update([
            'is_active' => DB::raw("IF(is_active = 'available', 'unavailable', 'available')")
        ]);

        return redirect()->back()->with('success', 'Product availability updated successfully.');
    }

    public function showProductWithStock()
    {
        $perPage = 8;
        $currentPage = request()->get('page', 1);

        // Step 1: Get all products
        $allProducts = Product::select('id', 'product_name', 'is_active')
            ->orderBy('product_name', 'asc')
            ->get();

        // Step 2: Get product IDs that require stock
        $stockProductIds = ProductWithStockList::pluck('product_id')->toArray();

        // Step 3: Products WITH stock requirement
        $productsWithStock = ProductWithStockList::withCount('productStockInList')
            ->whereIn('product_id', $stockProductIds)
            ->get()
            ->map(function ($item) use ($allProducts) {
                $product = $allProducts->firstWhere('id', $item->product_id);
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $product->product_name ?? 'Unknown',
                    'is_active' => $product->is_active ?? 'unavailable',
                    'product_stock_in_list_count' => $item->product_stock_in_list_count,
                    'required_stock' => $item->required_stock,
                    'requires_stock' => true,
                ];
            });

        // Step 4: Products WITHOUT stock requirement
        $productsWithoutStock = $allProducts
            ->filter(function ($product) use ($stockProductIds) {
                return !in_array($product->id, $stockProductIds);
            })
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'is_active' => $product->is_active ?? 'unavailable',
                    'product_stock_in_list_count' => 0,
                    'required_stock' => "Doesn't require stock",
                    'requires_stock' => false,
                ];
            });

        // Step 5: Merge the collections properly
        $mergedProducts = collect($productsWithStock)
            ->merge(collect($productsWithoutStock))
            ->sortBy('product_name')
            ->map(fn($item) => (object) $item)
            ->values();

        // Step 6: Paginate manually
        $paginated = new LengthAwarePaginator(
            $mergedProducts->forPage($currentPage, $perPage),
            $mergedProducts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('product_list', ['product_with_stock_list' => $paginated]);
    }

    public function viewProductStocks($productId)
    {
        $product_stock = ProductWithStockList::where('product_id', $productId)->firstOrFail();
        $stocks = $product_stock->productStockInList()
            ->where('is_active', 1)
            ->orderBy('id')
            ->paginate(10);

        return view('view_stocks_product', compact('product_stock', 'stocks'));
    }
    public function removeStocks($stockId, $productId)
    {
        // Update all stocks of this product to set is_active = 0
        ProductStockInList::where('id', $stockId)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        $totalQuantity = ProductStockInList::where('product_id', $productId)
            ->where('is_active', 1)
            ->sum('quantity_added');

        // Update
        ProductWithStockList::where('product_id', $productId)->update(['required_stock' => $totalQuantity]);

        // Optionally redirect back with success message
        return redirect()->back()->with('success', 'Stocks marked as inactive.');
    }
    public function productAddStock(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Add stock to stock_list
        ProductStockInList::create([
            'admin_id' => Auth::guard('web')->id(),
            'product_id' => $productId,
            'quantity_added' => $request->input('quantity'),
            'date_received' => now(),
        ]);

        // Recalculate total stock
        $totalQuantity = ProductStockInList::where('product_id', $productId)
            ->where('is_active', 1)
            ->sum('quantity_added');

        // Update
        ProductWithStockList::where('product_id', $productId)->update(['required_stock' => $totalQuantity]);

        return redirect()->back()->with('success', 'Stock added and total updated successfully!');
    }
}