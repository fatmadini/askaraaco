@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', '👤 Profil Saya')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h4>Informasi Akun</h4>
    </div>
    <div class="card-body" style="text-align: center;">
        
        @if(session('success'))
            <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #10b981; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: left;">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="profile-avatar" style="margin: 0 auto 20px;">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        
        <form action="{{ route('user.profile.update') }}" method="POST" class="profile-form" style="margin: 0 auto;">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
            </div>
            
            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', auth()->user()->no_telepon) }}" placeholder="081234567890">
                <small class="text-muted">Contoh: 081234567890</small>
            </div>
            
            <div class="form-group">
                <label>Password Baru (Kosongkan jika tidak ingin mengganti)</label>
                <input type="password" name="password" class="form-control" placeholder="********">
            </div>
            
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="********">
            </div>
            
            <button type="submit" class="btn btn-accent" style="width: 100%;">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection