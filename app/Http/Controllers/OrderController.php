<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\StockInList;
use App\Models\ProductStockInList;
use App\Models\ConsumableList;
use App\Models\ProductConsumable;
use App\Models\ProductWithStockList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new product
     */

    /**
     * Store a newly created product in storage
     */
    public function downloadReceipt($id)
    {
        $order = Orders::with(['items.product'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.receipt', compact('order'));

        return $pdf->download('Order_Receipt_' . $order->id . '.pdf');
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
    public function showOrders()
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

        $orders = Orders::with('products.productConsumableNeeded')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /*$order_list = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d');
        });*/

        return view('sales_report', compact('data', 'orders'));
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

    private function calculateTotalAmount($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['product_price'];
        }
        return $total;
    }

    private function deductTemporaryStock($product, $quantity)
    {
        $consumables = ProductConsumable::where('product_id', $product->id)->get();
        $productWithStock = ProductWithStockList::where('product_id', $product->id)->first();

        $deductions = session('stockDeductions', []);

        // Check consumables
        foreach ($consumables as $c) {
            $needed = $c->quantity_needed * $quantity;
            $available = StockInList::where('consumable_id', $c->consumable_id)->sum('quantity_added');
            if ($needed > $available)
                return "Insufficient stock for consumable: " . $c->consumable->consumable_name;

            // Temporarily deduct
            StockInList::where('consumable_id', $c->consumable_id)->first()->decrement('quantity_added', $needed);
            $deductions[] = ['type' => 'consumable', 'id' => $c->consumable_id, 'qty' => $needed];
        }

        // Check product stock
        if ($productWithStock && $quantity > 0) {
            $available = ProductStockInList::where('product_id', $product->id)->sum('quantity_added');
            if ($quantity > $available)
                return "Insufficient stock for product: {$product->product_name}";

            ProductStockInList::where('product_id', $product->id)->first()->decrement('quantity_added', $quantity);
            $deductions[] = ['type' => 'product', 'id' => $product->id, 'qty' => $quantity];
        }

        session(['stockDeductions' => $deductions]);
        return true;
    }

    private function revertTemporaryStock($product, $quantity)
    {
        $deductions = session('stockDeductions', []);
        $newDeductions = [];

        foreach ($deductions as $deduction) {
            if ($deduction['type'] === 'consumable') {
                StockInList::where('consumable_id', $deduction['id'])->first()->increment('quantity_added', $deduction['qty']);
            } elseif ($deduction['type'] === 'product') {
                ProductStockInList::where('product_id', $deduction['id'])->first()->increment('quantity_added', $deduction['qty']);
            } else {
                $newDeductions[] = $deduction;
            }
        }

        session(['stockDeductions' => $newDeductions]);
    }


    public function addToOrder(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity');

        // Check and deduct temporary stock
        $deductionResult = $this->deductTemporaryStock($product, $quantity);
        if ($deductionResult !== true) {
            return back()->with('error', $deductionResult); // return error message
        }

        // Add item to order session
        $orderItems = session('orderItems', []);
        $orderItems[] = [
            'product_id' => $id,
            'product_name' => $request->input('product_name'),
            'product_price' => $request->input('product_price'),
            'quantity' => $quantity,
            'amount' => $request->input('amount'),
        ];
        session(['orderItems' => $orderItems]);

        // Update total
        session(['totalAmount' => $this->calculateTotalAmount($orderItems)]);

        return back()->with('success', 'Item added to order');
    }
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'index' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
        ]);

        $orderItems = session('orderItems', []);
        $index = $request->input('index');
        $newQty = $request->input('quantity');

        if (!isset($orderItems[$index]))
            return back();

        $product = Product::findOrFail($orderItems[$index]['product_id']);
        $oldQty = $orderItems[$index]['quantity'];

        // Revert old deduction
        $this->revertTemporaryStock($product, $oldQty);

        // Try new deduction
        $deductionResult = $this->deductTemporaryStock($product, $newQty);
        if ($deductionResult !== true) {
            // Restore old quantity if failed
            $this->deductTemporaryStock($product, $oldQty);
            return back()->with('error', $deductionResult);
        }

        $orderItems[$index]['quantity'] = $newQty;
        $orderItems[$index]['amount'] = $newQty * $orderItems[$index]['product_price'];
        session(['orderItems' => $orderItems]);

        // Update total
        session(['totalAmount' => $this->calculateTotalAmount($orderItems)]);

        return back();
    }

    public function removeItem(Request $request)
    {
        $request->validate(['index' => 'required|numeric|min:0']);
        $index = $request->input('index');
        $orderItems = session('orderItems', []);

        if (isset($orderItems[$index])) {
            $product = Product::findOrFail($orderItems[$index]['product_id']);
            $this->revertTemporaryStock($product, $orderItems[$index]['quantity']);
            array_splice($orderItems, $index, 1);
            session(['orderItems' => $orderItems]);
            session(['totalAmount' => $this->calculateTotalAmount($orderItems)]);
        }

        return back();
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:Cash,Gcash',
        ]);

        // Get order items and total from session
        $orderItems = session('orderItems', []);
        $totalAmount = session('totalAmount', 0);

        if (empty($orderItems)) {
            return back()->with('error', 'No items in order');
        }

        // Create order record
        $order = Orders::create([
            'staff_id' => Auth::guard('staff')->id(),
            'total_amount' => $totalAmount,
            'payment_method' => $request->input('payment_method'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $product = Product::findOrFail($item['product_id']);
            $orderQty = $item['quantity'];

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
                    //DB::rollBack();
                    return back()->with('error', "Not enough product stock for {$product->product_name}");
                }

                $totalQuantity = ProductStockInList::where('product_id', $product->id)->sum('quantity_added');

                ProductWithStockList::where('product_id', $product->id)
                    ->update(['required_stock' => $totalQuantity]);
            }

            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'product_price' => $item['product_price'],
                'amount' => $item['quantity'] * $item['product_price'],
            ]);
        }

        // Clear session
        session()->forget(['orderItems', 'totalAmount']);

        return redirect()->route('record_sales')->with('success', 'Order completed successfully!');
    }
}