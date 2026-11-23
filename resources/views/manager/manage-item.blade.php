@extends('layouts.manager')

@section('content')
    <div class="space-y-6">
        <div class="mb-2">
            <h1 class="text-2xl font-black text-[#2e1065]">
                {{ isset($editItem) ? 'Update Item Stock' : 'Register New Item' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage inventory items, stock levels, and operational pricing.</p>
        </div>

        <!-- Paparan Mesej Kejayaan / Success Alert -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Paparan Mesej Ralat / Error Alert -->
        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation font-bold"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            <!-- Kolum Kiri: Borang Tambah / Kemaskini Item -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs xl:col-span-1">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2 mb-5">
                    <i class="fa-solid {{ isset($editItem) ? 'fa-pen-to-square' : 'fa-boxes-packing' }}"></i>
                    <span>{{ isset($editItem) ? 'Edit Item Details' : 'Add Stock Item' }}</span>
                </div>

                <form action="{{ isset($editItem) ? route('manager.items.update', $editItem->id) : route('manager.items.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @if(isset($editItem))
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Item Name</label>
                        <select name="item_name" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                            <option value="" disabled {{ !isset($editItem) ? 'selected' : '' }}>-- Select Item Name --</option>
                            <option value="Classic Titanium Frame (Black)" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Classic Titanium Frame (Black)' ? 'selected' : '' }}>Classic Titanium Frame (Black)</option>
                            <option value="Modern Round Gold Frame" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Modern Round Gold Frame' ? 'selected' : '' }}>Modern Round Gold Frame</option>
                            <option value="Sporty Flexible Acetate Frame" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Sporty Flexible Acetate Frame' ? 'selected' : '' }}>Sporty Flexible Acetate Frame</option>
                            <option value="Minimalist Wire Rim Frame" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Minimalist Wire Rim Frame' ? 'selected' : '' }}>Minimalist Wire Rim Frame</option>
                            <option value="Anti-Blue Light Lenses (Standard)" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Anti-Blue Light Lenses (Standard)' ? 'selected' : '' }}>Anti-Blue Light Lenses (Standard)</option>
                            <option value="Anti-Reflective Multi-Coated Lens" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Anti-Reflective Multi-Coated Lens' ? 'selected' : '' }}>Anti-Reflective Multi-Coated Lens</option>
                            <option value="Transition / Photochromic Lens" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Transition / Photochromic Lens' ? 'selected' : '' }}>Transition / Photochromic Lens</option>
                            <option value="Progressive Multi-Focal Lens" {{ old('item_name', isset($editItem) ? $editItem->item_name : '') == 'Progressive Multi-Focal Lens' ? 'selected' : '' }}>Progressive Multi-Focal Lens</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Stock Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', isset($editItem) ? $editItem->quantity : '') }}" placeholder="e.g. 50" required min="0"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Price (RM)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', isset($editItem) ? $editItem->price : '') }}" placeholder="e.g. 150.00" required min="0"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    </div>

                    <div class="flex space-x-2 pt-2">
                        @if(isset($editItem))
                            <a href="{{ route('manager.items.index') }}" class="w-1/3 py-3 text-center bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 transition shadow-xs">
                                Cancel
                            </a>
                        @endif
                        <button type="submit" class="{{ isset($editItem) ? 'w-2/3' : 'w-full' }} py-3 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                            {{ isset($editItem) ? 'Update Item' : 'Save New Item' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolum Kanan: Senarai Jadual Item -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs xl:col-span-2">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2 mb-5">
                    <i class="fa-solid fa-warehouse"></i>
                    <span>Inventory Item Registry</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                <th class="pb-3 text-center w-12">No</th>
                                <th class="pb-3">Item Name</th>
                                <th class="pb-3">Stock Quantity</th>
                                <th class="pb-3">Price (RM)</th>
                                <th class="pb-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-50 font-medium text-slate-700">
                            @forelse($items as $index => $item)
                                <tr class="{{ (isset($editItem) && $editItem->id == $item->id) ? 'bg-purple-50/50' : '' }}">
                                    <td class="py-4 text-center text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-4 font-bold text-purple-950">{{ $item->item_name }}</td>

                                    <!-- Paparan Kuantiti Stok Bersyarat Warna -->
                                    <td class="py-4">
                                        @if(($item->quantity ?? $item->stock ?? 0) <= 0)
                                            <!-- Stok 0: Merah -->
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                                <span>0 units (Out of Stock)</span>
                                            </span>
                                        @elseif(($item->quantity ?? $item->stock ?? 0) <= 10)
                                            <!-- Stok 1 hingga 10: Kuning/Amber -->
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 font-extrabold rounded-full text-[11px] inline-flex items-center space-x-1">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                <span>{{ $item->quantity ?? $item->stock }} units (Low Stock)</span>
                                            </span>
                                        @else
                                            <!-- Stok > 10: Ungu -->
                                            <span class="px-2.5 py-1 bg-purple-50 text-purple-700 font-bold rounded-full text-[11px]">
                                                {{ $item->quantity ?? $item->stock }} units
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 text-slate-600 font-semibold">RM {{ number_format($item->price, 2) }}</td>
                                    <td class="py-4 px-4 text-right flex space-x-2 items-center justify-center">
                                        <!-- Butang Edit -->
                                        <a href="{{ route('manager.items.edit', $item->id) }}" class="p-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition" title="Update Item">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <!-- Butang Delete -->
                                        <form action="{{ route('manager.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Delete Item">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 font-medium">
                                        <i class="fa-solid fa-folder-open block text-2xl mb-2 text-slate-300"></i>
                                        No inventory items registered in the database.
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
