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
            width: 380px;
            color: white;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
            z-index: 1;
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo span { font-size: 40px; }
        .logo h1 { font-family: 'Syne', sans-serif; font-size: 28px; background: linear-gradient(135deg, #a855f7, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .logo p { font-size: 13px; color: #aaa; }
        .form-group { margin-bottom: 15px; }
        label { font-size: 13px; color: #bbb; }
        input { width: 100%; padding: 12px; margin-top: 5px; border-radius: 10px; border: none; background: rgba(255,255,255,0.05); color: white; outline: none; }
        input:focus { border: 1px solid #7c3aed; }
        .btn-login { width: 100%; padding: 12px; border: none; border-radius: 10px; background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-login:hover { transform: scale(1.03); box-shadow: 0 0 15px #7c3aed; }
        .error { background: rgba(255,0,0,0.2); padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; }
        .check { display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 10px; color: #bbb; }
        .hint { text-align: center; font-size: 12px; color: #aaa; margin-top: 15px; }
        .remember { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .remember input { width: 16px; height: 16px; accent-color: #7c3aed; }
        .remember span { font-size: 13px; color: #bbb; }
        a { color: #a855f7; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <span>🎤</span>
        <h1>TicketWave</h1>
        <p>Login</p>
    </div>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="admin@email.com">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <div class="check">
            <label class="remember">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
        </div>
        <button type="submit" class="btn-login">🔐 Login</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar</a>
    </p>
    <p class="hint">Sistem Penjualan Tiket Konser</p>
</div>
</body>
</html>