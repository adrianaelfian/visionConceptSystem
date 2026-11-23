@extends('layouts.salesperson')

@section('content')
    @php
        $customerList = $customers->map(fn ($c) => [
            'id' => $c->id,
            'label' => $c->formatted_id . ' - ' . $c->customerName . ' (' . $c->customerEmail . ')',
        ])->values();
    @endphp

    <div class="max-w-4xl mx-auto py-10" x-data="orderForm()">
        <div class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <h1 class="text-2xl font-black text-[#2e1065] mb-6">Create New Order</h1>

            <form action="{{ route('salesperson.orders.store') }}" method="POST" class="space-y-6"
                  @submit="if (!selectedCustomer) { $event.preventDefault(); alert('Please choose customer by customer ID.'); }">
                @csrf

                <!-- Pilihan Customer (Searchable) -->
                <div class="space-y-1 relative" @click.outside="open = false">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Select Customer ID / Name</label>

                    <input type="hidden" name="customer_id" :value="selectedCustomer">

                    <input type="text" x-model="search" autocomplete="off"
                        @focus="open = true"
                        @input="open = true; selectedCustomer = ''; showCondition = false"
                        placeholder="Search by id.."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#6d28d9] bg-slate-50/50">

                    <div x-show="open" style="display: none;"
                        class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="c in filteredCustomers()" :key="c.id">
                            <button type="button" @click="selectCustomer(c)"
                                class="block w-full text-left px-4 py-2 text-xs hover:bg-purple-50">
                                <span x-text="c.label"></span>
                            </button>
                        </template>
                        <p x-show="filteredCustomers().length === 0" class="px-4 py-3 text-xs text-slate-400">
                            Customer Not Found.
                        </p>
                    </div>
                </div>

                <!-- Bahagian Paparan Automatik Eye Condition -->
                <div x-show="showCondition" class="mb-6 bg-purple-50/50 border border-purple-100 rounded-2xl p-4" style="display: none;">
                    <h4 class="text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-3">
                        <i class="fa-solid fa-glasses mr-1.5"></i> Latest Eye Condition Prescription
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs text-slate-700" x-html="conditionHtml">
                    </div>
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
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Product</label>
                                <select :name="`items[${index}][id]`" x-model="item.id" @change="updatePrice(index, $event)" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#3b127e]" required>
                                    <option value="">Select Product</option>
                                    @foreach($items as $itemOption)
                                        <option value="{{ $itemOption->id }}" data-price="{{ $itemOption->price }}">
                                            {{ $itemOption->name }} (RM {{ number_format($itemOption->price, 2) }} | Stock: {{ $itemOption->stock }} units)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-24">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qty</label>
                                <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#3b127e]" required>
                            </div>

                            <div class="pt-5">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Paparan Harga Keseluruhan & Deposit -->
                <div class="p-4 bg-purple-50 rounded-xl border border-purple-100 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-[#3b127e] uppercase">Total Calculated Price:</span>
                        <span class="text-base font-black text-[#3b127e]" x-text="'RM ' + calculateTotal()"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-purple-200/60">
                        <span class="text-xs font-bold text-[#3b127e] uppercase">Total Deposit (30%):</span>
                        <span class="text-sm font-black text-[#3b127e]" x-text="'RM ' + depoTotal()"></span>
                    </div>
                </div>

                <!-- Bahagian Bayaran -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Amount Paid (RM)</label>
                    <input type="number" name="amount_paid" x-model="amountPaid" step="0.01" min="0" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" placeholder="0.00" required>
                    <p class="text-[10px] text-slate-400">Minimum payment must cover at least the 30% deposit requirement, and cannot exceed the total price.</p>
                </div>

                <!-- Butang Simpan -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <a href="{{ route('salesperson.orders.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        Back
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                        Save Order Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Skrip Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                items: [{ id: '', qty: 1, price: 0 }],
                customers: @json($customerList),
                search: '',
                open: false,
                selectedCustomer: '{{ $selectedCustomer->id ?? '' }}',
                showCondition: false,
                conditionHtml: '',
                amountPaid: '',

                init() {
                    if (this.selectedCustomer) {
                        const c = this.customers.find(c => c.id == this.selectedCustomer);
                        if (c) this.search = c.label;
                        this.fetchCustomerConditions(this.selectedCustomer);
                    }
                },

                filteredCustomers() {
                    const q = this.search.toLowerCase().trim();
                    if (!q) return this.customers.slice(0, 50);
                    return this.customers.filter(c => c.label.toLowerCase().includes(q)).slice(0, 50);
                },

                selectCustomer(c) {
                    this.selectedCustomer = c.id;
                    this.search = c.label;
                    this.open = false;
                    this.fetchCustomerConditions(c.id);
                },

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
                        return sum + ((item.price || 0) * (item.qty || 0));
                    }, 0);
                    return total.toFixed(2);
                },

                depoTotal() {
                    let total = this.items.reduce((sum, item) => {
                        return sum + ((item.price || 0) * (item.qty || 0));
                    }, 0);
                    let deposit = total * 0.30;
                    return deposit.toFixed(2);
                },

                fetchCustomerConditions(customerId) {
                    if (!customerId) {
                        this.showCondition = false;
                        this.conditionHtml = '';
                        return;
                    }

                    fetch(`/salesperson/api/customer/${customerId}/conditions`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                let latest = data[0];
                                this.conditionHtml = `
                                    <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-xs">
                                        <span class="text-slate-400 block font-semibold mb-0.5">Right (SPH/CYL/AX)</span>
                                        <strong class="text-slate-800 text-xs">${latest.rightPower || '-'} / ${latest.rightCylinder || '-'} / ${latest.rightAxis || '-'}°</strong>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-xs">
                                        <span class="text-slate-400 block font-semibold mb-0.5">Left (SPH/CYL/AX)</span>
                                        <strong class="text-slate-800 text-xs">${latest.leftPower || '-'} / ${latest.leftCylinder || '-'} / ${latest.leftAxis || '-'}°</strong>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-xs">
                                        <span class="text-slate-400 block font-semibold mb-0.5">Pupil Distance (PD)</span>
                                        <strong class="text-slate-800 text-xs">${latest.pupilDistance || '-'} mm</strong>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-purple-100 shadow-xs">
                                        <span class="text-slate-400 block font-semibold mb-0.5">Date Recorded</span>
                                        <strong class="text-slate-800 text-[11px]">${new Date(latest.created_at).toLocaleDateString()}</strong>
                                    </div>
                                `;
                                this.showCondition = true;
                            } else {
                                this.conditionHtml = `<p class="text-slate-400 italic col-span-4 py-2">No prescription record found for this customer.</p>`;
                                this.showCondition = true;
                            }
                        })
                        .catch(error => console.error('Error fetching conditions:', error));
                }
            }))
        });
    </script>
@endsection
