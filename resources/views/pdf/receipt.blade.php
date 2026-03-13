<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Receipt</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f4f5;
            color: #111;
            padding: 2rem 1rem;
            font-size: 13px;
            line-height: 1.5;
        }

        .receipt {
            background: #fff;
            border: 0.5px solid #d4d4d8;
            border-radius: 12px;
            max-width: 480px;
            margin: 0 auto;
            overflow: hidden;
        }

        .receipt-header {
            padding: 22px 24px 18px;
            border-bottom: 0.5px solid #e4e4e7;
        }

        .receipt-header .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #a1a1aa;
            margin-bottom: 4px;
        }

        .receipt-header .store-name {
            font-size: 19px;
            font-weight: 500;
            color: #111;
            margin-bottom: 12px;
        }

        .receipt-header .meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #71717a;
        }

        .items {
            padding: 16px 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead tr {
            border-bottom: 0.5px solid #e4e4e7;
        }

        th {
            padding-bottom: 8px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #a1a1aa;
            text-align: left;
        }

        th.qty  { text-align: center; width: 12%; }
        th.price  { text-align: right; width: 22%; }
        th.amount { text-align: right; width: 22%; }

        tbody tr { border-bottom: 0.5px solid #f0f0f0; }

        td {
            padding: 9px 0;
            color: #111;
            text-align: left;
        }

        td.qty    { text-align: center; color: #71717a; }
        td.price  { text-align: right; color: #71717a; }
        td.amount { text-align: right; font-weight: 500; }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px 14px;
            border-top: 0.5px solid #e4e4e7;
        }

        .total-row .total-label {
            font-size: 13px;
            color: #71717a;
        }

        .total-row .total-value {
            font-size: 20px;
            font-weight: 500;
            color: #111;
        }

        .payment-summary {
            margin: 0 24px 16px;
            border: 0.5px solid #e4e4e7;
            border-radius: 8px;
            overflow: hidden;
            font-size: 12.5px;
        }

        .payment-summary .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 14px;
            background: #fafafa;
            border-bottom: 0.5px solid #e4e4e7;
        }

        .payment-summary .row:last-child {
            border-bottom: none;
        }

        .payment-summary .row .row-label {
            color: #71717a;
        }

        .payment-summary .row .row-value {
            font-weight: 500;
            color: #111;
        }

        .payment-summary .row .row-value.change {
            color: #16a34a;
        }

        .payment-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 5px;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .receipt-footer {
            display: flex;
            justify-content: space-between;
            padding: 12px 24px 18px;
            border-top: 0.5px solid #e4e4e7;
            font-size: 11px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    <div class="receipt">

        <div class="receipt-header">
            <p class="label">Official Receipt</p>
            <p class="store-name">Cally's Pizza Stop</p>
            <div class="meta">
                <span>Order #{{ $order->id }}</span>
                <span>{{ $order->created_at->format('F j, Y — g:i A') }}</span>
            </div>
        </div>

        <div class="items">
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
            </table>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 0 0 16px;">
            <tr>
                <td style="padding: 10px 24px; color: #71717a; font-size: 13px;">Total Amount</td>
                <td style="padding: 10px 24px; text-align: right; font-size: 20px; font-weight: 500; color: #111;">PHP {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        <div style="margin: 0 24px 16px; border: 0.5px solid #e4e4e7; border-radius: 8px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                <tr style="background: #fafafa; border-bottom: 0.5px solid #e4e4e7;">
                    <td style="padding: 9px 14px; color: #71717a;">Customer payment</td>
                    <td style="padding: 9px 14px; text-align: right; font-weight: 500; color: #111;">PHP {{ number_format($order->customer_payment, 2) }}</td>
                </tr>
                <tr style="background: #fafafa; border-bottom: 0.5px solid #e4e4e7;">
                    <td style="padding: 9px 14px; color: #71717a;">Change</td>
                    <td style="padding: 9px 14px; text-align: right; font-weight: 500; color: #16a34a;">PHP {{ number_format($order->change_amount, 2) }}</td>
                </tr>
                <tr style="background: #fafafa;">
                    <td style="padding: 9px 14px; color: #71717a;">Payment method</td>
                    <td style="padding: 9px 14px; text-align: right;">
                        <span style="font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 5px; background: #eff6ff; color: #1d4ed8;">{{ $order->payment_method ?? 'N/A' }}</span>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>