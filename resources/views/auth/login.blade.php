<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Meta viewport ini SANGAT PENTING agar tampilan bagus di HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pelita Aset Parepare</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @include('partials.styles')
    </head>
    <body class="login-shell d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <!-- Ukuran kolom disesuaikan: lebar 5 kolom di laptop, penuh di HP -->
        <div class="col-12 col-md-8 col-lg-5">
            
            <div class="card login-card border-0">
                <div class="login-header">
                    <i class="fa-solid fa-map-location-dot login-icon"></i>
                    <h3 class="fw-bold text-dark mb-1">Pelita Aset Parepare</h3>
                    <p class="text-muted small">Aplikasi Pemetaan Aset Pemerintah Parepare</p>
                </div>
                
                <div class="card-body p-4 p-md-5 pt-0">
                    
                    <!-- Alert jika ada error saat login (password salah, dll) -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Ganti "login" dengan nama route login Bapak jika berbeda -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Username / Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="email" class="form-control border-start-0" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                                                    <button type="submit" class="btn btn-primary btn-login">
                                Masuk <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Footer Text -->
            <div class="text-center mt-4 text-white opacity-75">
                <small>&copy; {{ date('Y') }} Kantor Pertanahan. Hak Cipta Dilindungi.</small>
            </div>
            
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>