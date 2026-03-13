<div id="orderModal-{{ $order->id }}" class="fixed inset-0 z-50 hidden items-center justify-center px-4" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(1px);">

  <div class="w-full max-w-md overflow-hidden" style="border-radius: 18px; border: 0.5px solid rgba(148,163,184,0.2); background: linear-gradient(180deg,#1e2336 0%,#141927 100%); box-shadow: 0 24px 48px rgba(0,0,0,0.5); max-width: 520px;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 22px 16px; border-bottom: 0.5px solid rgba(148,163,184,0.12);">
      <div>
        <p style="font-size: 17px; text-transform: uppercase; letter-spacing: 0.12em; color: #64748b; margin: 0 0 4px 0;">Order Slip</p>
        <div style="text-align: left;">
            <p style="font-size: 12px; font-weight: 500; color: #f1f5f9;">ORDER: #{{ $order->id }}</p>
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('orders.downloadReceipt', $order->id) }}" style="font-size: 12px; padding: 6px 12px; border-radius: 8px; border: 0.5px solid rgba(148,163,184,0.3); color: #94a3b8; text-decoration: none; transition: all 0.15s;">Download PDF</a>
        <button onclick="document.getElementById('orderModal-{{ $order->id }}').classList.remove('flex'); document.getElementById('orderModal-{{ $order->id }}').classList.add('hidden')" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 0.5px solid rgba(148,163,184,0.3); color: #94a3b8; background: transparent; font-size: 25px; cursor: pointer; line-height: 1;">×</button>
      </div>
    </div>

    {{-- Items --}}
    <div style="padding: 18px 22px 0;">
      <table style="width: 100%; font-size: 12.5px; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 0.5px solid rgba(148,163,184,0.12);">
            <th style="text-align: left; padding-bottom: 10px; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; width: 46%;">Item</th>
            <th style="text-align: center; padding-bottom: 10px; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; width: 12%;">Qty</th>
            <th style="text-align: right; padding-bottom: 10px; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; width: 20%;">Price</th>
            <th style="text-align: right; padding-bottom: 10px; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; width: 22%;">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->items as $item)
          <tr style="border-bottom: 0.5px solid rgba(148,163,184,0.07);">
            <td style="padding: 11px 0; color: #e2e8f0; font-weight: 500; text-align: left;">{{ $item->product->product_name ?? 'N/A' }}</td>
            <td style="padding: 11px 0; text-align: center; color: #94a3b8;">{{ $item->quantity }}</td>
            <td style="padding: 11px 0; text-align: right; color: #94a3b8;">₱{{ number_format($item->product_price,2) }}</td>
            <td style="padding: 11px 0; text-align: right; color: #f1f5f9; font-weight: 600;">₱{{ number_format($item->amount,2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Total --}}
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 22px; border-bottom: 0.5px solid rgba(148,163,184,0.12);">
      <span style="color: #94a3b8; font-size: 14px;">Total</span>
      <span style="font-size: 20px; font-weight: 700; color: #f8fafc;">₱{{ number_format($order->total_amount,2) }}</span>
    </div>

    {{-- Payment Summary --}}
    <div style="margin: 14px 16px; border-radius: 12px; border: 0.5px solid rgba(148,163,184,0.12); overflow: hidden; font-size: 12.5px; background: rgba(15,21,38,0.6);">
      <div style="display: flex; justify-content: space-between; padding: 11px 16px; border-bottom: 0.5px solid rgba(148,163,184,0.08);">
        <span style="font-size: 14px; color: #94a3b8;">Customer payment</span>
        <span style="color: #e2e8f0; font-weight: 600;">₱{{ number_format($order->customer_payment,2) }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; padding: 11px 16px; border-bottom: 0.5px solid rgba(148,163,184,0.08);">
        <span style="font-size: 14px; color: #94a3b8;">Change</span>
        <span style="color: #4ade80; font-weight: 600;">₱{{ number_format($order->change_amount,2) }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 11px 16px;">
        <span style="font-size: 14px; color: #94a3b8;">Payment method</span>
        <span style="font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; background: rgba(59,130,246,0.2); color: #93c5fd; border: 0.5px solid rgba(59,130,246,0.25);">{{ $order->payment_method ?? 'N/A' }}</span>
      </div>
    </div>

    {{-- Footer --}}
    <div style="padding: 10px 22px 16px; text-align: right;">
      <p style="font-size: 12px; color: #94a3b8; margin: 0;">Staff #{{ $order->staff_id ?? 'N/A' }} · {{ $order->created_at->format('M j, Y — g:i A') }}</p>
    </div>

  </div>
</div>