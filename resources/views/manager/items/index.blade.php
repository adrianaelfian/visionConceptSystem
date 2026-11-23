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
                <input type="text" name="name" placeholder="e.g. Vision Concept Display Unit" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
            @endif
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">
                {{ isset($itemToEdit) ? 'Stock Quantity to Add' : 'Stock Quantity' }}
            </label>
            <!-- Dikosongkan (tiada value sedia ada) supaya manager boleh masukkan kuantiti unit nak tambah -->
            <input type="number" name="stock" placeholder="{{ isset($itemToEdit) ? 'e.g. 10 (jumlah unit ditambah)' : 'e.g. 50' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
            @if(isset($itemToEdit))
                <p class="text-[10px] text-gray-400 mt-1">Baki stok semasa dalam sistem: <span class="font-bold text-purple-600">{{ $itemToEdit->stock }} units</span></p>
            @endif
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Price (RM)</label>
            <input type="number" step="0.01" name="price" value="{{ $itemToEdit->price ?? '' }}" placeholder="e.g. 150.00" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600" required>
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
            <div class="flex items-center space-x-2 mb-6">
                <i class="fa-solid fa-warehouse text-purple-700"></i>
                <h2 class="text-sm font-bold text-purple-950">Inventory Item Registry</h2>
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
                    <tbody class="text-xs">
                        @forelse($items as $index => $item)
                        <tr class="border-b border-gray-50 hover:bg-purple-50/30 transition">
                            <td class="py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>

                            <!-- Paparan Nama Item Mengikut Kolum Database 'name' -->
                            <td class="py-4 font-bold text-purple-950">
                                {{ $item->name }}
                            </td>

                            <!-- Paparan Stok Mengikut Kolum Database 'stock' -->
                            <td class="py-4">
    @if($item->stock <= 0)
        <!-- Stok Kosong (0): Warna Merah -->
        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
            <span>0 units (Out of Stock)</span>
        </span>
    @elseif($item->stock <= 10)
        <!-- Stok Kurang / Sama dengan 10: Warna Kuning / Amber -->
        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
            <span>{{ $item->stock }} units (Low Stock)</span>
        </span>
    @else
        <!-- Stok Mencukupi (> 10): Warna Ungu / Hijau -->
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
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                                Tiada item ditemui di dalam inventori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
