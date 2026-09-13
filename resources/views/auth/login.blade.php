<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Pelita Wakaf</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
    <div class="text-center mb-4">
        <h3 class="fw-bold">🔐 Login Admin</h3>
        <p class="text-muted">Sistem Pemetaan Aset Wakaf Parepare</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.perform') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Admin</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@bpn.go.id" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Masuk Admin</button>
        <a href="{{ route('home') }}" class="btn btn-link w-100 text-center text-muted mt-2">← Kembali ke Peta</a>
    </form>
</div>

</body>
</html>