@extends('layouts.salesperson')

@section('content')
    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4">

            {{-- Header Section --}}
            <div class="mb-8 text-center">
                <a href="{{ route('salesperson.customer.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-purple-600 transition mb-4">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Customer Directory
                </a>
                <h1 class="text-3xl font-extrabold text-slate-800">Register Customer</h1>
                <p class="text-slate-500 mt-2">Add a new client to your management system</p>
            </div>

            {{-- Card Container --}}
            <div class="bg-white/80 backdrop-blur-md border border-slate-200 p-8 rounded-3xl shadow-xl shadow-purple-100/50">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-bounce">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('salesperson.customer.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Input Fields --}}
                    <div class="grid grid-cols-1 gap-6">
                        <div class="group">
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Customer Name</label>
                            <input type="text" name="customerName" placeholder="e.g. Ahmad Albab" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition-all outline-none">
                            @error('customerName')
                                <p class="text-red-500 text-xs mt-1">{{ $message}}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-slate-600 mb-2">Email</label>
                                <input type="email" name="customerEmail" placeholder="ahmad@example.com" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition-all outline-none">
                                @error('customerEmail')
                                    <p class="text-red-500 text-xs mt-1">{{ $message}}</p>
                                @enderror
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-slate-600 mb-2">Phone</label>
                                <input type="text" name="customerPhoneNumber" placeholder="012-3456789" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition-all outline-none">
                                @error('customerPhoneNumber')
                                    <p class="text-red-500 text-xs mt-1">{{ $message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                        Create Customer Profile
                    </button>
                </form>
                @if(session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '{{ session('success') }}',
                                confirmButtonColor: '#4f46e5', // Warna ungu yang sepadan dengan tema anda
                                background: '#ffffff',
                                customClass: {
                                    popup: 'rounded-4'
                                }
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>
@endsection
