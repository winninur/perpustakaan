<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIP Perpustakaan</title>
    <!-- Library CSS disesuaikan persis dengan Login -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 50%, #051947 100%);
            min-height: 100vh;
        }
        .register-card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.25);
        }
        .register-logo {
            font-size: 3.5rem;
            color: #4e73df;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-4">

    <div class="container" style="max-width: 480px;">
        <!-- Outer Row -->
        <div class="card o-hidden border-0 shadow-lg register-card">
            <div class="card-body p-0">
                <!-- Nested Row with Card Columns -->
                <div class="row">
                    <div class="col-12">
                        <div class="p-5">
                            
                            <!-- Header Logo & Title -->
                            <div class="text-center mb-4">
                                <i class="fas fa-user-plus register-logo"></i>
                                <h1 class="h4 text-gray-900 mb-1 font-weight-bold">Daftar Akun Baru</h1>
                                <p class="text-muted small">SIP Perpustakaan</p>
                            </div>

                            <!-- Alert Global Error -->
                            @if ($errors->any())
                                <div class="alert alert-danger small py-2 mb-3">
                                    <ul class="mb-0 pl-3">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Form Register -->
                            <form method="POST" action="{{ route('register.store') }}">
                                @csrf

                                <!-- Input Nama Lengkap -->
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label text-gray-800 small">
                                        <i class="fas fa-user mr-1"></i> Nama Lengkap
                                    </label>
                                    <input type="text" name="name" id="name"
                                           class="form-control form-control-user @error('name') is-invalid @enderror"
                                           placeholder="Masukkan nama lengkap"
                                           value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Input Email / NIM -->
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label text-gray-800 small">
                                        <i class="fas fa-envelope mr-1"></i> Email / NIM
                                    </label>
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-user @error('email') is-invalid @enderror"
                                           placeholder="contoh@gmail.com"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Input Password -->
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

                                <!-- Input Konfirmasi Password -->
                                <div class="form-group mb-4">
                                    <label for="password_confirmation" class="form-label text-gray-800 small">
                                        <i class="fas fa-lock mr-1"></i> Konfirmasi Password
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control form-control-user"
                                           placeholder="Ulangi password" required>
                                </div>

                                <!-- Tombol Submit -->
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    <i class="fas fa-user-check mr-1"></i> Daftar Sekarang
                                </button>
                            </form>

                            <hr class="my-4">

                            <!-- Footer Link to Login -->
                            <div class="text-center">
                                <span class="text-muted small">Sudah punya akun? </span>
                                <a href="{{ route('login') }}" class="font-weight-bold text-primary small">Login di sini</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Credit -->
        <div class="text-center text-white small mt-4">
            &copy; {{ date('Y') }} Sistem Informasi Perpustakaan &middot; Kelompok AF
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 