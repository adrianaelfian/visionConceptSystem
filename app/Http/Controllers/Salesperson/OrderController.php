<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Customer;

class OrderController extends Controller
{
   public function create($customer_id = null)
    {
        // Jika customer_id dihantar, cari pelanggan tersebut (untuk aliran terus dari Eye Condition)
        $selectedCustomer = $customer_id ? Customer::findOrFail($customer_id) : null;

        // Ambil senarai semua pelanggan (untuk dropdown jika tiada ID dipilih)
        $customers = Customer::all();
        $items = Item::all();

        return view('salesperson.orders.createOrder', compact('customers', 'items', 'selectedCustomer'));
    }
    /**
     * Store a new order securely using database transactions and stock validation.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'items'       => 'required|array|min:1',
        'items.*.id'  => 'required|exists:items,id',
        'items.*.qty' => 'required|integer|min:1',
        'amount_paid' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($validated, $request) {
            $total_price = 0;

            // Kira jumlah harga keseluruhan dengan selamat
            foreach ($validated['items'] as $itemData) {
                $item = \App\Models\Item::findOrFail($itemData['id']);

                // Ambil harga item (pastikan lajur di DB betul, contoh: price)
                $itemPrice = floatval($item->price ?? 0);
                $quantity  = intval($itemData['qty'] ?? 1);

                $total_price += ($itemPrice * $quantity);
            }

            $balance = $total_price - floatval($validated['amount_paid']);

            // Cipta Pesanan Utama
            $order = \App\Models\Order::create([
                'customer_id'       => $validated['customer_id'],
                'total_price'       => $total_price,
                'amount_paid'       => $validated['amount_paid'],
                'remaining_balance' => $balance,
                'payment_status'    => $balance <= 0 ? 'Fully Paid' : 'Partial / Pending',
                'order_status'      => 'pending',
                'salesperson_id'    => auth()->id(),
            ]);

            // Cipta Item Pesanan (Order Items)
            foreach ($validated['items'] as $itemData) {
                $item = \App\Models\Item::findOrFail($itemData['id']);

                \App\Models\OrderItem::create([
                    'order_id'   => $order->id,
                    'item_id'    => $item->id,
                    'price'      => floatval($item->price ?? 0),
                    'quantity'   => $itemData['qty'],
                ]);
            }
        });

    return redirect()->route('salesperson.orders.index')->with('success', 'Order successfully created with multiple items!');
}

    /**
     * Update an existing order status and payment details.
     */
    public function updateOrder(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'amount_paid'  => 'required|numeric',
            'order_status' => 'required'
        ]);

        DB::transaction(function () use ($order, $request) {
            $newPaid = $order->amount_paid + $request->amount_paid;
            $remaining = $order->total_price - $newPaid;

            $order->update([
                'amount_paid'       => $newPaid,
                'remaining_balance' => max(0, $remaining),
                'payment_status'    => ($remaining <= 0) ? 'Fully Paid' : 'Partially Paid',
                'order_status'      => $request->order_status,
            ]);
        });

        return redirect()->route('salesperson.orders.index')->with('success', 'Order dikemaskini!');
    }

    /**
     * Generate and display the invoice view for a specific order.
     */
    public function generateInvoice(int $id)
    {
        $order = Order::with(['customer', 'orderItems.item'])->findOrFail($id);
        return view('salesperson.orders.invoice', compact('order'));
    }
    public function index()
{
    // Ambil senarai pesanan (berserta data pelanggan jika perlu)
    $orders = \App\Models\Order::with('customer')->get();

    return view('salesperson.orders.index', compact('orders'));
}

public function update(Request $request,int $id)
{
    $order = Order::findOrFail($id);

    // Validasi input
    $request->validate([
        'amount_paid' => 'required|numeric|min:0|max:' . $order->total_price,
        'order_status' => 'required|string',
    ]);

    $amountPaid = $request->amount_paid;
    $remainingBalance = $order->total_price - $amountPaid;

    // Tentukan payment status secara automatik berdasarkan rules
    $paymentStatus = ($amountPaid >= $order->total_price) ? 'Fully Paid' : 'Partially Paid';

    // Kemaskini rekod pesanan
    $order->update([
        'amount_paid' => $amountPaid,
        'remaining_balance' => $remainingBalance,
        'payment_status' => $paymentStatus,
        'order_status' => $request->order_status,
    ]);

    return redirect()->route('salesperson.orders.index')->with('success', 'Order updated successfully!');
}

/**
     * Papar borang edit/kemaskini pesanan.
     */
    public function edit(int $id)
    {
        $order = \App\Models\Order::with('items')->findOrFail($id);
        $customers = \App\Models\Customer::all();
        $items = \App\Models\Item::all();

        return view('salesperson.orders.edit', compact('order', 'customers', 'items'));
    }

}
