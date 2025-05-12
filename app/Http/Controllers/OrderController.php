<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockInList;
use App\Models\ConsumableList;
use App\Models\ProductConsumable;
use App\Models\ProductWithStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new product
     */

    /**
     * Store a newly created product in storage
     */
    public function showOrders()
    {
        $orders = Orders::with('products.productConsumableNeeded')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /*$order_list = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d');
        });*/

        return view('sales_report', compact('orders'));
    }
    public function filterOrders(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay(); // 00:00:00
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();       // 23:59:59

        // Filter orders based on the date range
        $orders = Orders::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('sales_report', compact('orders'));
    }

}