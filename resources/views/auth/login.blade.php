<x-guest-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
    <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-semibold text-center">
        {{ session('success') }}
    </div>
@endif
    @if($errors->has('email') || $errors->has('loginError'))
        <div class="alert alert-danger border-0 py-2 text-center text-sm rounded-3 mb-4" style="font-size: 0.85rem; background-color: #fff5f5; color: #9b1c1c;">
            ✨ {{ $errors->first('email') ?: $errors->first('loginError') }}
        </div>
    @endif

    <!-- Guna route('login.submit') untuk menghantar data ke LoginController tersuai -->
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="role" class="form-label text-muted fw-semibold small mb-1" style="color: #4c1d95 !important;">Select Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="" disabled selected>Select your role...</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="salesperson">Salesperson</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1 text-danger" style="font-size:0.75rem;" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-muted fw-semibold small mb-1" style="color: #4c1d95 !important;">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control shadow-sm border-0 py-2 px-3 rounded-3"
                   style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #e9d5ff !important;"
                   value="{{ old('email') }}" required autofocus placeholder="Enter your email">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger" style="font-size:0.75rem;" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-semibold small mb-1" style="color: #4c1d95 !important;">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control shadow-sm border-0 py-2 px-3 rounded-3"
                   style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #e9d5ff !important;"
                   required placeholder="Enter your password">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger" style="font-size: 0.75rem;" />
        </div>

        <div class="d-flex mb-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="fw-semibold text-decoration-none small ms-auto" style="color: #4c1d95; font-size: 0.8rem;">
                    Forgot Password?
                </a>
            @else
                <a href="{{ url('/password/reset') }}" class="fw-semibold text-decoration-none small ms-auto" style="color: #4c1d95; font-size: 0.8rem;">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="btn w-100 py-2 rounded-3 fw-semibold small border-0 shadow-sm text-white"
                    style="background: linear-gradient(135deg, #4c1d95 0%, #2e1065 100%);">
                Sign In <i class="fa-solid fa-right-to-bracket ms-1" style="font-size: 0.8rem;"></i>
            </button>
        </div>
    </form>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let errorMessage = "{{ $errors->first('email') ?: ($errors->first('role') ?: 'Please check your credentials.') }}";

            if (errorMessage.includes('invalid') || errorMessage.includes('match')) {
                errorMessage = "Email, password, or the selected role is incorrect. Please try again.";
            }

            Swal.fire({
                icon: 'error',
                title: 'Login Failed!',
                text: errorMessage,
                confirmButtonColor: '#4c1d95',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-sm'
                }
            });
        });
    </script>
    @endif
</x-guest-layout>
