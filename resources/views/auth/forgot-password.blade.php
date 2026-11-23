<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Vision Concept</title>
    <!-- Guna CDN Bootstrap sementara untuk bagi cantik sikit -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="card-title text-center mb-4" style="color: #4c1d95; font-weight: bold;">Reset Password</h4>

            <!-- Papar Mesej Berjaya Jika Ada -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your registered email">
                </div>

                <button type="submit" class="btn w-100 text-white" style="background-color: #4c1d95;">
                    Send Password Reset Link
                </button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="text-decoration-none" style="font-size: 0.9rem;">Back to Login</a>
            </div>
        </div>
    </div>

</body>
</html>
