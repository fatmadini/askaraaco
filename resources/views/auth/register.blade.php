<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TicketWave</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: #7c3aed;
            filter: blur(200px);
            top: -100px;
            left: -100px;
            opacity: 0.4;
        }
        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: #f59e0b;
            filter: blur(200px);
            bottom: -100px;
            right: -100px;
            opacity: 0.4;
        }
        .card {
            backdrop-filter: blur(20px);
            background: rgba(20, 20, 35, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            width: 450px;
            color: white;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
            z-index: 1;
        }
        .logo { text-align: center; margin-bottom: 25px; }
        .logo span { font-size: 40px; }
        .logo h1 { font-family: 'Syne', sans-serif; font-size: 28px; background: linear-gradient(135deg, #a855f7, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .logo p { font-size: 13px; color: #aaa; }
        .form-group { margin-bottom: 15px; }
        label { font-size: 13px; color: #bbb; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; outline: none; }
        input:focus { border-color: #7c3aed; }
        .btn-register { width: 100%; padding: 12px; border: none; border-radius: 10px; background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-register:hover { transform: scale(1.03); box-shadow: 0 0 15px #7c3aed; }
        .error { background: rgba(255,0,0,0.2); padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; text-align: center; }
        .error-list { background: rgba(255,0,0,0.2); padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; }
        .error-list ul { margin-left: 20px; }
        a { color: #a855f7; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .hint { text-align: center; font-size: 12px; color: #aaa; margin-top: 15px; }
        .small-text { font-size: 11px; color: #888; margin-top: 4px; display: block; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <span>🎤</span>
        <h1>TicketWave</h1>
        <p>Daftar Akun Baru</p>
    </div>

    @if($errors->any())
        <div class="error-list">
            <strong>❌ Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
        </div>
        <div class="form-group">
            <label>No Telepon</label>
            <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="081234567890">
            <small class="small-text">📱 Contoh: 081234567890 (opsional)</small>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" required>
        </div>
        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
        </div>
        <button type="submit" class="btn-register">📝 Daftar Sekarang</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        Sudah punya akun?
        <a href="{{ route('login') }}">Login di sini</a>
    </p>
    <p class="hint">Sistem Penjualan Tiket Konser</p>
</div>
</body>
</html>