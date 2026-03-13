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
use Illuminate\Support\Facades\Log;

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
        //$productRequiresStock = $request->has('product_required_stock_checkbox');

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
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image_path')) {
                $imageFile = $request->file('image_path');
                $path = $imageFile->store('products', 'public');

                // Create product
                $product = Product::create([
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

                // If product has no consumables for example drinks, add it to ProductWithStockList
                if ($skipConsumables) {
                    ProductWithStockList::create([
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();

                Log::info('Product added', [
                    'product_name' => $product->product_name,
                    'user_id' => Auth::id()
                ]);

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
        } elseif (Auth::guard('staff')->check()) {
            $data['staff'] = Auth::guard('staff')->user();
        } else {
            return redirect()->route('welcome');
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

        // Step 1: Get all product IDs that require stock
        $stockProductIds = ProductWithStockList::pluck('product_id')->toArray();

        // Step 2: Fetch products WITH stock requirement, eager loading relationships
        $productsWithStock = ProductWithStockList::with([
            'product',
            'productStockInList' => function ($query) {
                $query->where('is_active', 1);
            }
        ])
            ->whereIn('product_id', $stockProductIds)
            ->get();

        // Step 3: Map to uniform objects expected by Blade
        $productsWithStockMapped = $productsWithStock->map(function ($item) {
            return (object) [
                'product_id' => $item->product_id,
                'product_name' => $item->product->product_name ?? 'Unknown',
                'is_active' => $item->product->is_active ?? 'unavailable',
                'required_stock' => true,
                'required_stock_count' => $item->required_stock,
                'productStockInList' => $item->productStockInList, // collection used in Blade for sums
                'product' => $item->product,
            ];
        });

        // Step 4: Fetch products WITHOUT stock requirement
        $productsWithoutStock = Product::whereNotIn('id', $stockProductIds)
            ->orderBy('product_name', 'asc')
            ->get()
            ->map(function ($product) {
                return (object) [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'is_active' => $product->is_active ?? 'unavailable',
                    'required_stock' => false,
                    'required_stock_count' => 0,
                    'productStockInList' => collect(), // empty collection to avoid errors in Blade
                    'product' => $product,
                ];
            });

        // Step 5: Merge both collections and sort by product_name
        //$mergedProducts = $productsWithStockMapped->merge($productsWithoutStock)->sortBy('product_name')->values();

        $mergedProducts = collect(
            array_merge(
                $productsWithStockMapped->toArray(),
                $productsWithoutStock->toArray()
            )
        )
        ->map(fn($item) => (object) $item)
        ->sortBy(fn($item) => $item->product_name)
        ->values();

        // Step 6: Manual pagination for merged collection
        $pageItems = $mergedProducts->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pageItems,
            $mergedProducts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Pass the paginated collection to your Blade view
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
            'stock_price' => 'required|numeric|min:0',
        ]);

        $quantity = $request->input('quantity');
        $stockPrice = $request->input('stock_price'); // Total expenses for the added stock
        //$stockExpenses = $quantity * $stockPrice;
        $totalStockPrice = $stockPrice / $quantity; // Price per added stock

        ProductStockInList::create([
            'product_id' => $productId,
            'quantity_added' => $request->input('quantity'),
            'stock_price' => $totalStockPrice,
            'stock_expenses' => $stockPrice,
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