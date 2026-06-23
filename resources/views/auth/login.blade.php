<x-guest-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- error message if login failed[E1 / E2] -->
    @if($errors->has('loginError'))
        <div class="alert alert-danger border-0 py-2 text-center text-sm rounded-3 mb-4" style="font-size: 0.85rem; background-color: #fff5f5; color: #9b1c1c;">
            ✨ {{ $errors->first('loginError') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <!-- Input Email -->
        <div class="mb-3">
            <label for="email" class="form-label text-muted fw-semibold small mb-1" style="color: #4c1d95 !important;">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control shadow-sm border-0 py-2 px-3 rounded-3"
                   style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #e9d5ff !important;"
                   value="{{ old('email') }}" required autofocus placeholder="Enter your email">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Input Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-semibold small mb-1" style="color: #4c1d95 !important;">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control shadow-sm border-0 py-2 px-3 rounded-3"
                   style="background-color: #fbfbfe; font-size: 0.9rem; border: 1px solid #e9d5ff !important;"
                   required placeholder="Enter your password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="d-flex mb-4">
            <!-- forgot password -->
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

        <!-- link to sign up if dont have any account-->
        <div class="text-center mt-4 pt-2 border-top" style="border-color: #e9d5ff !important;">
            <p class="text-muted small mb-0">
                Don't have an account?
                <a href="{{ url('/register') }}" class="fw-semibold text-decoration-none ms-1" style="color: #4c1d95;">
                    Sign Up
                </a>
            </p>
        </div>
    </form>
    @if($errors->has('loginError') || $errors->has('email') || $errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // take the error message and send to Laravel
            let errorMessage = "{{ $errors->first('loginError') ?: ($errors->first('email') ?: $errors->first('password')) }}";

            // if the input is not correct, error message will be pop up
            if (errorMessage.includes('credentials')) {
                errorMessage = "The email or password you entered is incorrect.";
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
