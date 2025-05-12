<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\ConsumableList;
use App\Models\ProductWithStockList;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter logic based on 'filter' query param
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
        $totalSales = $orders->sum('amount');

        // Total Quantity Ordered
        $totalOrders = $orders->sum('quantity_ordered');

        // Product Performance: sum of amount per product_name
        $productPerformance = Orders::select('product_name', DB::raw('SUM(amount) as revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_name')
            ->orderBy('revenue', 'desc')
            ->get();

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