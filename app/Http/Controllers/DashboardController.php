<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\ConsumableList;
use App\Models\ProductWithStockList;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check authentication for web or staff guard
        if (auth('web')->check()) {
            $user = auth('web')->user();
        } elseif (auth('staff')->check()) {
            return redirect()->route('record_sales'); // staff redirected
        } else {
            return redirect()->route('welcome'); // not logged in
        }
        
        $filter = $request->query('filter', 'today');

        $startDate = null;
        $endDate = Carbon::now();

        switch ($filter) {
            case 'last3days':
                $startDate = Carbon::now()->subDays(2)->startOfDay(); // includes today
                break;
            case 'lifetime':
                $startDate = Carbon::createFromTimestamp(0); // beginning of time
                break;
            default: // 'today'
                $startDate = Carbon::now()->startOfDay();
        }

        // Orders filtered by date
        $orders = Orders::whereBetween('created_at', [$startDate, $endDate])->get();

        // Total Sales (sum of 'amount' column)
        $totalSales = $orders->sum('total_amount');

        // Total Quantity Ordered
        $totalOrders = $orders->count();

        // Product Performance: sum of amount per product_name
        $productPerformance = OrderItems::with(['product', 'order'])
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy('product.product_name')
            ->map(function ($items, $productName) {
                return [
                    'product_name' => $productName,
                    'revenue' => $items->sum('amount'),
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        // Properly use the models with pagination (6 items per page)
        $productWithStock = ProductWithStockList::orderBy('required_stock', 'asc')->paginate(6);
        $consumables = ConsumableList::orderBy('total_stock_left', 'asc')->paginate(6);

        return view('dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'productPerformance' => $productPerformance,
            'productWithStock' => $productWithStock,
            'consumables' => $consumables,
            'currentFilter' => $filter
        ]);
    }
}