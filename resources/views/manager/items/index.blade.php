@extends('layouts.manager')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-purple-950">Register New Item</h1>
        <p class="text-xs text-gray-500">Manage inventory items, stock levels, and operational pricing.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

       <!-- Borang Tambah / Kemaskini Item (Sebelah Kiri) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-purple-100">
            <div class="flex items-center space-x-2 mb-4">
                <i class="fa-solid fa-boxes-stacked text-purple-700"></i>
                <h2 class="text-sm font-bold text-purple-950">
                    {{ isset($itemToEdit) ? 'Edit Item Details' : 'Add Stock Item' }}
                </h2>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ isset($itemToEdit) ? route('manager.items.update', $itemToEdit->id) : route('manager.items.store') }}" method="POST">
                @csrf
                @if(isset($itemToEdit))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Item Name</label>
                    @if(isset($itemToEdit))
                        <!-- Kunci nama item supaya ia tidak ralat required -->
                        <input type="text" value="{{ $itemToEdit->name }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs bg-gray-50 text-purple-950 font-bold cursor-not-allowed" disabled>
                        <input type="hidden" name="name" value="{{ $itemToEdit->name }}">
                    @else
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Vision Concept Display Unit" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">
                        {{ isset($itemToEdit) ? 'Stock Quantity to Add' : 'Stock Quantity' }}
                    </label>
                    <input type="number" name="stock" min="10" step="1" value="{{ old('stock') }}" placeholder="{{ isset($itemToEdit) ? 'e.g. 10 (min. 10 units)' : 'e.g. 50 (min. 10 units)' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
                    @if(isset($itemToEdit))
                        <p class="text-[10px] text-gray-400 mt-1">Balance stock in system: <span class="font-bold text-purple-600">{{ $itemToEdit->stock }} units</span></p>
                    @endif
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Price (RM)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $itemToEdit->price ?? '') }}" placeholder="e.g. 150.00" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
                </div>

                <div class="flex space-x-2">
                    @if(isset($itemToEdit))
                        <a href="{{ route('manager.items.index') }}" class="w-1/2 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-xs rounded-xl shadow-md text-center transition">
                            Cancel
                        </a>
                        <button type="submit" class="w-1/2 py-3 bg-[#6d28d9] hover:bg-[#5b21b6] text-white font-bold text-xs rounded-xl shadow-md transition">
                            Update Stock
                        </button>
                    @else
                        <button type="submit" class="w-full py-3 bg-[#6d28d9] hover:bg-[#5b21b6] text-white font-bold text-xs rounded-xl shadow-md transition">
                            Save New Item
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Jadual Senarai Item / Registry (Sebelah Kanan) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-purple-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-warehouse text-purple-700"></i>
                    <h2 class="text-sm font-bold text-purple-950">Inventory Item Registry</h2>
                </div>

                <!-- Kotak Carian Item -->
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input
                        type="text"
                        id="itemSearchInput"
                        placeholder="Search item name..."
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:border-purple-600 focus:bg-white transition"
                    >
                    <button
                        type="button"
                        id="clearItemSearch"
                        onclick="clearSearch()"
                        class="hidden text-gray-400 hover:text-purple-700 text-xs absolute right-3 top-1/2 -translate-y-1/2"
                        title="Clear search"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase border-b border-gray-100">
                            <th class="pb-3">No</th>
                            <th class="pb-3">Item Name</th>
                            <th class="pb-3">Stock Quantity</th>
                            <th class="pb-3">Price (RM)</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody" class="text-xs">
                        @forelse($items as $index => $item)
                        <tr class="item-row border-b border-gray-50 hover:bg-purple-50/30 transition">
                            <td class="py-4 text-gray-500 font-medium row-number">{{ $index + 1 }}</td>

                            <!-- Paparan Nama Item Mengikut Kolum Database 'name' -->
                            <td class="py-4 font-bold text-purple-950 item-name">
                                {{ $item->name }}
                            </td>

                            <!-- Paparan Stok Mengikut Kolum Database 'stock' -->
                            <td class="py-4">
                                @if($item->stock <= 0)
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span>0 units (Out of Stock)</span>
                                    </span>
                                @elseif($item->stock <= 10)
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                        <span>{{ $item->stock }} units (Low Stock)</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-purple-50 text-[#6d28d9] font-bold rounded-full text-[11px]">
                                        {{ $item->stock }} units
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 font-semibold text-gray-700">
                                RM {{ number_format($item->price, 2) }}
                            </td>

                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Butang Edit -->
                                    <a href="{{ route('manager.items.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <!-- Butang Delete -->
                                    <form action="{{ route('manager.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Padam item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyDbRow">
                            <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                                Tiada item ditemui di dalam inventori.
                            </td>
                        </tr>
                        @endforelse

                        <!-- Baris jika tiada hasil carian -->
                        <tr id="noSearchResultRow" class="hidden">
                            <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                                Tiada item yang sepadan dengan carian tersebut.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            const searchInput = document.getElementById('itemSearchInput');
            const clearBtn = document.getElementById('clearItemSearch');
            const rows = document.querySelectorAll('#inventoryTableBody .item-row');
            const noResultRow = document.getElementById('noSearchResultRow');

            function filterItems() {
                const query = searchInput.value.trim().toLowerCase();
                clearBtn.classList.toggle('hidden', query.length === 0);

                let visibleCount = 0;

                rows.forEach((row) => {
                    const nameText = row.querySelector('.item-name')?.textContent.toLowerCase() || '';
                    const isMatch = nameText.includes(query);

                    row.classList.toggle('hidden', !isMatch);

                    if (isMatch) {
                        visibleCount++;
                        const numCell = row.querySelector('.row-number');
                        if (numCell) {
                            numCell.textContent = visibleCount;
                        }
                    }
                });

                if (noResultRow) {
                    noResultRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
                }
            }

            function clearSearch() {
                searchInput.value = '';
                filterItems();
                searchInput.focus();
            }

            searchInput?.addEventListener('input', filterItems);
        </script>
    </div>
</div>
@endsection
