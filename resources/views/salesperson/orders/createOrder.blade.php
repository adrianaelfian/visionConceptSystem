@extends('layouts.salesperson')

@section('content')
    <div class="max-w-4xl mx-auto py-10" x-data="orderForm()">
        <div class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <h1 class="text-2xl font-black text-[#2e1065] mb-6">Create New Order</h1>

            <form action="{{ route('salesperson.orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Pilih Pelanggan -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Select Customer</label>

                    @if(isset($selectedCustomer))
                        <input type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-slate-100 text-slate-700 font-medium focus:outline-none" value="{{ $selectedCustomer->customerName ?? $selectedCustomer->name }}" disabled>
                        <input type="hidden" name="customer_id" value="{{ $selectedCustomer->id }}">
                    @else
                        <select name="customer_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#3b127e]" required>
                            <option value="">-- Choose Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name ?? $customer->customerName }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Senarai Produk Dinamik -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-[#3b127e] uppercase tracking-wider">Order Items</label>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 bg-purple-50 text-[#6d28d9] rounded-lg text-xs font-bold hover:bg-purple-100 transition">
                            + Add Item
                        </button>
                    </div>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <!-- Pilihan Produk -->
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Product</label>
                                <select :name="`items[${index}][id]`" x-model="item.id" @change="updatePrice(index, $event)" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#3b127e]" required>
                                    <option value="">Select Product</option>
                                    @foreach($items as $itemOption)
                                        <option value="{{ $itemOption->id }}" data-price="{{ $itemOption->price }}">
                                            {{ $itemOption->name }} (RM {{ number_format($itemOption->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kuantiti -->
                            <div class="w-24">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qty</label>
                                <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#3b127e]" required>
                            </div>

                            <!-- Butang Buang Baris -->
                            <div class="pt-5">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Paparan Harga Keseluruhan (Auto Calculate) -->
                <div class="p-4 bg-purple-50 rounded-xl border border-purple-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-[#3b127e] uppercase">Total Calculated Price:</span>
                    <span class="text-lg font-black text-[#3b127e]" x-text="'RM ' + calculateTotal()"></span>
                </div>

                <!-- Bahagian Bayaran -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Amount Paid (RM)</label>
                    <input type="number" step="0.01" name="amount_paid" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" placeholder="0.00" required>
                </div>

                <!-- Butang Simpan -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                        Save Order Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Skrip Alpine.js untuk Auto Calculation -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                items: [{ id: '', qty: 1, price: 0 }],
                addItem() {
                    this.items.push({ id: '', qty: 1, price: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                updatePrice(index, event) {
                    const selectedOption = event.target.options[event.target.selectedIndex];
                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    this.items[index].price = price;
                },
                calculateTotal() {
                    let total = this.items.reduce((sum, item) => {
                        return sum + (item.price * (item.qty || 0));
                    }, 0);
                    return total.toFixed(2);
                }
            }))
        });
    </script>
@endsection
