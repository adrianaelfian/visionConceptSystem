@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="mb-2">
            <h1 class="text-2xl font-black text-[#2e1065]">Register New Account</h1>
            <p class="text-xs text-slate-500 mt-0.5">Create, monitor, and manage operational profiles for Salespersons & Managers.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation font-bold"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            <!-- Kolum Kiri: Borang Pendaftaran -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs xl:col-span-1">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2 mb-5">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Create Staff Account</span>
                </div>

                <form action="{{ route('admin.accounts.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmad Fauzi" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="fauzi@visionconcept.com" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="text" name="phoneNO" value="{{ old('phoneNO') }}" placeholder="e.g. 0123456789" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition @error('phoneNO') border-rose-500 bg-rose-50 @enderror">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Role</label>
                        <select name="role" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-purple-500 focus:bg-white transition text-slate-700">
                            <option value="salesperson" {{ old('role') == 'salesperson' ? 'selected' : '' }}>Salesperson</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Security Password</label>
                        <input type="password" name="password" placeholder="Minimum 8 characters" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500 focus:bg-white transition">
                        <span class="text-[9px] text-slate-400 block mt-1">Passwords are securely encrypted standard hashing.</span>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs mt-2">
                        Register New Profile
                    </button>
                </form>
            </div>

            <!-- Kolum Kanan: Senarai Jadual Staf -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs xl:col-span-2">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2 mb-5">
                    <i class="fa-solid fa-address-book"></i>
                    <span>Registered Operator Registry</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                <th class="pb-3 text-center w-12">No</th>
                                <th class="pb-3">Name</th>
                                <th class="pb-3">Email Address</th>
                                <th class="pb-3">Phone Number</th>
                                <th class="pb-3">Role</th>
                                <th class="pb-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-50 font-medium text-slate-700">
                            @forelse($users as $index => $user)
                                <tr>
                                    <td class="py-4 text-center text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-4 font-bold text-purple-950">{{ $user->name }}</td>
                                    <td class="py-4 text-slate-500">{{ $user->email }}</td>
                                    <td class="py-4 text-slate-600">{{ $user->phoneNO }}</td>
                                    <td class="py-4">
                                        @if($user->role === 'manager')
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded-md text-[10px] uppercase tracking-wider">Manager</span>
                                        @elseif($user->role === 'admin')
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-bold rounded-md text-[10px] uppercase tracking-wider">Admin</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-purple-50 text-purple-700 font-bold rounded-md text-[10px] uppercase tracking-wider">Salesperson</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('admin.accounts.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this staff profile?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Delete Account">
                                                    <i class="fa-solid fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                        <i class="fa-solid fa-folder-open block text-2xl mb-2 text-slate-300"></i>
                                        No system account operators registered in the application datastore.
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
