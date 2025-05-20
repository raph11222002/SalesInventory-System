<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Sales Report</h2>
        <h3>Cally's Pizza Stop</h3>
        @if($startDate && $endDate)
            <p>Date: {{ $startDate }} to {{ $endDate }}</p>
        @endif
    </div>

    <div>
        <p><strong>Total Orders:</strong> {{ $orders->count() }}</p>
        <p><strong>Total Sales:</strong> ₱{{ number_format($orders->sum('total_amount'), 2) }}</p>
    </div>

    @php
        $groupedOrders = $orders->groupBy(fn($order) => $order->created_at->format('Y-m-d'));
    @endphp

    @foreach ($groupedOrders as $date => $orderGroup)
        <div class="section-title">Date: {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</div>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Staff</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderGroup as $order)
                    @php $itemCount = $order->items->count(); @endphp
                    @foreach ($order->items as $index => $item)
                        <tr>
                            @if ($index === 0)
                                <td rowspan="{{ $itemCount }}">{{ $order->id }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $order->staff->name }}</td>
                            @endif
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->amount, 2) }}</td>
                            @if ($index === 0)
                                <td rowspan="{{ $itemCount }}">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $order->payment_method }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $order->created_at->format('h:i A') }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>