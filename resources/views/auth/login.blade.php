<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TicketWave</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: radial-gradient(ellipse at 30% 50%, #1e0a4a 0%, #0d0d14 60%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            color: #e8e8f0;
        }
        .card {
            background: #15151f; border: 1px solid #2a2a3d;
            border-radius: 20px; padding: 48px 40px; width: 380px;
            box-shadow: 0 0 80px rgba(124,58,237,0.15);
        }
        .logo { text-align: center; margin-bottom: 32px; }
        .logo-icon { font-size: 44px; display: block; margin-bottom: 8px; }
        .logo h1 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; background: linear-gradient(135deg, #a855f7, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .logo p { color: #6b6b8a; font-size: 13px; margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; color: #6b6b8a; margin-bottom: 6px; }
        input { width: 100%; background: #1c1c2b; border: 1px solid #2a2a3d; border-radius: 10px; padding: 11px 14px; color: #e8e8f0; font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none; transition: border-color 0.2s; }
        input:focus { border-color: #7c3aed; }
        .btn-login { width: 100%; background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; border: none; border-radius: 10px; padding: 12px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; cursor: pointer; margin-top: 4px; }
        .btn-login:hover { opacity: 0.9; }
        .error { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; }
        .hint { text-align: center; font-size: 12px; color: #6b6b8a; margin-top: 16px; }
        .check-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b6b8a; margin-bottom: 16px; }
        .check-row input[type=checkbox] { width: auto; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <span class="logo-icon">🎵</span>
        <h1>TicketWave</h1>
        <p>Sistem Penjualan Tiket Konser</p>
    </div>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <div class="check-row">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" style="margin:0;">Ingat saya</label>
        </div>
        <button type="submit" class="btn-login">🔐 Masuk</button>
    </form>
    <p class="hint">Hubungi admin jika belum punya akun.</p>
</div>
</body>
</html>