<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Receipt</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #111;
            padding: 1.5rem;
            max-width: 480px;
            margin: 0 auto;
            font-size: 14px;
            line-height: 1.4;
        }

        .receipt {
            border: 1px solid #ccc;
            padding: 1rem 1.25rem;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            font-weight: 500;
            font-size: 13px;
            margin-bottom: 1rem;
            color: #555;
        }

        .header>div {
            white-space: nowrap;
        }

        h1 {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            text-align: center;
            letter-spacing: 1px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th,
        td {
            padding: 0.25rem 0.5rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th.qty,
        td.qty,
        th.price,
        td.price,
        th.amount,
        td.amount {
            text-align: right;
            min-width: 50px;
        }

        th.qty,
        td.qty {
            width: 40px;
        }

        th.price,
        td.price {
            width: 80px;
        }

        th.amount,
        td.amount {
            width: 90px;
        }

        tfoot td {
            font-weight: 600;
            border-top: 2px solid #000;
            font-size: 16px;
            color: #e63946;
        }

        .footer-right {
            margin-top: 1rem;
            text-align: right;
            font-size: 13px;
            color: #000;
        }

        .footer-right div {
            margin-bottom: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <h1>Order Receipt</h1>

        <div class="header">
            <div>Order ID: {{ $order->id }}</div>
            <div>
                Date Ordered: {{ $order->created_at->format('F j, Y - h:i A') }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="qty">Qty</th>
                    <th class="price">Price</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="price">PHP {{ number_format($item->product_price, 2) }}</td>
                        <td class="amount">PHP {{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="amount">Total Amount</td>
                    <td class="amount">PHP {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer-right">
            <div>Payment Method: {{ $order->payment_method ?? 'N/A' }}</div>
            <div>Staff ID: {{ $order->staff_id ?? 'N/A' }}</div>
        </div>
    </div>
</body>

</html>