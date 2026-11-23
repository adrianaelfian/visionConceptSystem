<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Terima kasih atas pesanan anda!</h2>
    <p>Hai <strong>{{ $order->customer->name ?? 'Pelanggan' }}</strong>,</p>
    <p>Pesanan anda telah berjaya direkodkan dalam sistem kami.</p>

    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 5px 0;"><strong>No. Pesanan (Order ID):</strong> #{{ $order->id }}</p>
        <p style="margin: 5px 0;"><strong>Jumlah Keseluruhan:</strong> RM {{ number_format($order->total_price, 2) }}</p>
        <p style="margin: 5px 0;"><strong>Jumlah Dibayar (Deposit/Full):</strong> RM {{ number_format($order->amount_paid, 2) }}</p>
        <p style="margin: 5px 0;"><strong>Baki Tertunggak:</strong> RM {{ number_format($order->remaining_balance, 2) }}</p>
        <p style="margin: 5px 0;"><strong>Status Pembayaran:</strong> {{ $order->payment_status }}</p>
    </div>

    <p>Anda boleh menyemak status pesanan anda pada bila-bila masa.</p>
    <p>Sekian, terima kasih!</p>
</body>
</html>
