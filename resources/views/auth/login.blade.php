<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIP Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 50%, #051947 100%);
            min-height: 100vh;
        }
        .login-card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.25);
        }
        .login-logo {
            font-size: 3.5rem;
            color: #4e73df;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-4">

    <div class="container" style="max-width: 480px;">
        <div class="card o-hidden border-0 shadow-lg login-card">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-12">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-book-reader login-logo"></i>
                                <h1 class="h4 text-gray-900 mb-1 font-weight-bold">SIP Perpustakaan</h1>
                                <p class="text-muted small">Sistem Informasi Perpustakaan</p>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success small py-2">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger small py-2">
                                    <ul class="mb-0 pl-3">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.store') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label text-gray-800 small">
                                        <i class="fas fa-envelope mr-1"></i> Email
                                    </label>
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-user @error('email') is-invalid @enderror"
                                           placeholder="Masukkan email Anda"
                                           value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label text-gray-800 small">
                                        <i class="fas fa-lock mr-1"></i> Password
                                    </label>
                                    <input type="password" name="password" id="password"
                                           class="form-control form-control-user @error('password') is-invalid @enderror"
                                           placeholder="Masukkan password" required>
                                    @error('password')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small text-gray-700" for="remember">
                                        Remember me
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <span class="text-muted small">Belum punya akun? </span>
                                <a href="{{ route('register') }}" class="font-weight-bold text-primary small">Daftar di sini</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-white small mt-4">
            &copy; {{ date('Y') }} Sistem Informasi Perpustakaan &middot; Kelompok AF
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>