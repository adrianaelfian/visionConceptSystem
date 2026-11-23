<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Warranty;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Papar borang cipta order dengan carian khusus mengikut Customer ID.
     */
    public function create(Request $request, $customer_id = null)
    {
        // Jika customer_id dihantar melalui URL parameter atau query string
        $customerId = $customer_id ?? $request->input('customer_id');

        $selectedCustomer = null;
        if ($customerId) {
            $selectedCustomer = Customer::findOrFail($customerId);
        }

        $items = Item::all();
        $customers = Customer::all();

        return view('salesperson.orders.createOrder', compact('customers', 'items', 'selectedCustomer'));
    }

    /**
     * Store a new order securely using database transactions and stock validation.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|integer|exists:customers,id',
        'items'       => 'required|array|min:1',
        'items.*.id'  => 'required|exists:items,id',
        'items.*.qty' => 'required|integer|min:1',
        'amount_paid' => 'required|numeric|min:0',
    ]);

    $customer = Customer::findOrFail($validated['customer_id']);

    $calculatedTotal = 0;
    foreach ($validated['items'] as $itemData) {
        $item = Item::findOrFail($itemData['id']);
        $orderedQty = intval($itemData['qty']);

        // Semak jika kuantiti melebihi stok semasa
        if ($orderedQty > $item->stock) {
            return back()->withErrors(['items' => "Stock not enough for item: {$item->name}. Current stock balance is {$item->stock} unit only."])->withInput();
        }

        $calculatedTotal += floatval($item->price ?? 0) * $orderedQty;
    }

    $minimumDeposit = $calculatedTotal * 0.30;

    if ($validated['amount_paid'] < $minimumDeposit) {
        return back()->withErrors(['amount_paid' => 'The minimum deposit required is 30% of the total price (RM ' . number_format($minimumDeposit, 2) . ')'])->withInput();
    }

    if ($validated['amount_paid'] > $calculatedTotal) {
        return back()->withErrors(['amount_paid' => 'The amount paid cannot exceed the total order price.'])->withInput();
    }

    $order = null;

    DB::transaction(function () use ($validated, $calculatedTotal, $customer, &$order) {
        $total_price = $calculatedTotal;
        $amount_paid = floatval($validated['amount_paid']);
        $balance = $total_price - $amount_paid;

        $paymentStatus = ($balance <= 0) ? 'Fully Paid' : 'Depo';

        $order = Order::create([
            'customer_id'       => $validated['customer_id'],
            'total_price'       => $total_price,
            'amount_paid'       => $amount_paid,
            'remaining_balance' => max(0, $balance),
            'payment_status'    => $paymentStatus,
            'order_status'      => 'pending',
            'salesperson_id'    => auth()->id(),
        ]);

        foreach ($validated['items'] as $itemData) {
            $item = Item::findOrFail($itemData['id']);
            $orderedQty = intval($itemData['qty']);

            OrderItem::create([
                'order_id'   => $order->id,
                'item_id'    => $item->id,
                'price'      => floatval($item->price ?? 0),
                'quantity'   => $orderedQty,
            ]);

            $item->stock -= $orderedQty;
            $item->save();
        }
    });

    if ($order && $customer->customerEmail) {
        Mail::to($customer->customerEmail)->send(new OrderPlacedMail($order));
    }

    return redirect()->route('salesperson.orders.index')->with('success', 'Order successfully created and email sent!');
}

    /**
     * Update an existing order payment status and progress status.
     */
    public function update(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|string|in:Depo,Fully Paid',
            'order_status'   => 'required|string',
        ]);

        if ($request->payment_status === 'Fully Paid') {
            // Jika dipilih Fully Paid, pelanggan telah membuat bayaran penuh
            $amountPaid = $order->total_price;
            $remainingBalance = 0;
            $paymentStatus = 'Fully Paid';
        } else {
            // Jika dipilih Depo, kekalkan jumlah bayaran deposit sedia ada
            $amountPaid = $order->amount_paid;
            $remainingBalance = $order->total_price - $amountPaid;
            $paymentStatus = 'Depo';
        }

        $order->update([
            'amount_paid'       => $amountPaid,
            'remaining_balance' => max(0, $remainingBalance),
            'payment_status'    => $paymentStatus,
            'order_status'      => $request->order_status,
        ]);

        return redirect()->route('salesperson.orders.index')->with('success', 'Order status updated successfully!');
    }

    /**
     * Alternative method to update or add partial payment to an existing order.
     */
    public function updateOrder(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'amount_paid'  => 'required|numeric|min:0',
            'order_status' => 'required|string'
        ]);

        DB::transaction(function () use ($order, $request) {
            $newPaid = $order->amount_paid + floatval($request->amount_paid);
            $remaining = $order->total_price - $newPaid;

            $paymentStatus = ($remaining <= 0) ? 'Fully Paid' : 'Depo';

            $order->update([
                'amount_paid'       => $newPaid,
                'remaining_balance' => max(0, $remaining),
                'payment_status'    => $paymentStatus,
                'order_status'      => $request->order_status,
            ]);
        });

        return redirect()->route('salesperson.orders.index')->with('success', 'Order dikemaskini!');
    }

    public function generateInvoice(int $id)
    {
        $order = Order::with(['customer', 'orderItems.item'])->findOrFail($id);
        return view('salesperson.orders.invoice', compact('order'));
    }

    public function index(Request $request)
{
    $search = $request->input('search');

    $orders = Order::with('customer')
        ->when($search, function ($query, $search) {
            // Bersihkan carian jika pengguna taip '#ORD-21' atau 'ORD-21' atau '21'
            $cleanSearch = str_replace(['#', 'ORD-', 'ord-'], '', $search);

            return $query->where('id', 'like', "%{$cleanSearch}%")
                         ->orWhereHas('customer', function ($q) use ($search) {
                             $q->where('customerName', 'like', "%{$search}%");
                         });
        })
        ->latest()
        ->paginate(10); // Anda boleh guna get() jika tiada pagination

    return view('salesperson.orders.index', compact('orders', 'search'));
}

    public function edit(int $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $customers = Customer::all();
        $items = Item::all();

        return view('salesperson.orders.edit', compact('order', 'customers', 'items'));
    }

    public function getCustomerConditions(int $customer_id)
    {
        $conditions = \App\Models\EyeCondition::where('customer_id', $customer_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($conditions);
    }

    public function show(int $id)
    {
        $order = Order::with('customer.eyeConditions')->findOrFail($id);
        return view('salesperson.orders.manage', compact('order'));
    }
}
