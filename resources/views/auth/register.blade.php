@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    .auth-register-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .auth-register-card {
        width: 100%;
        max-width: 760px;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
        border: none;
    }

    .auth-register-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 34px 28px 30px;
        text-align: center;
        color: #ffffff;
    }

    .auth-register-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 34px;
    }

    .auth-register-header h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        color: #ffffff;
    }

    .auth-register-header p {
        margin: 6px 0 0;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.75);
    }

    .auth-register-body {
        padding: 38px 34px 34px;
    }

    .auth-register-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .auth-register-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 26px;
    }

    .auth-register-body .form-label {
        font-weight: 700;
        font-size: 13px;
        color: #111827;
        margin-bottom: 8px;
    }

    .auth-register-body .form-control {
        min-height: 50px;
        border-radius: 8px;
        border: 1px solid #d9dee7;
        color: #374151;
        font-size: 15px;
        background-color: #ffffff;
        box-shadow: none;
    }

    .auth-register-body textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .auth-register-body .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .auth-register-body .input-group-text {
        width: 48px;
        justify-content: center;
        border-radius: 8px 0 0 8px;
        border: 1px solid #d9dee7;
        border-right: 0;
        background: #f8fafc;
        color: #667eea;
    }

    .auth-register-body .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    .auth-register-body .input-group .password-with-toggle {
        border-radius: 0;
    }

    .btn-password-toggle {
        width: 48px;
        min-height: 50px;
        border: 1px solid #d9dee7;
        border-left: 0;
        border-radius: 0 8px 8px 0;
        background: #ffffff;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: none;
    }

    .btn-password-toggle:hover {
        background: #f8fafc;
        color: #667eea;
    }

    .btn-password-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .btn-register-auth {
        min-height: 50px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: 0.2s ease;
    }

    .btn-register-auth:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 28px 0 20px;
        color: #9ca3af;
        font-size: 13px;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    .auth-login-link {
        text-align: center;
        font-size: 14px;
        color: #6b7280;
    }

    .auth-login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
    }

    .auth-login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .auth-register-page {
            padding: 24px 12px;
            align-items: flex-start;
        }

        .auth-register-card {
            max-width: 100%;
            border-radius: 14px;
        }

        .auth-register-header {
            padding: 28px 22px 26px;
        }

        .auth-register-header h3 {
            font-size: 24px;
        }

        .auth-register-body {
            padding: 30px 22px 28px;
        }
    }
</style>

<div class="auth-register-page">
    <div class="auth-register-card">
        <div class="auth-register-header">
            <div class="auth-register-icon">
                <i class="fas fa-tshirt"></i>
            </div>
            <h3>Laundry App</h3>
            <p>Sistem Manajemen Laundry</p>
        </div>

        <div class="auth-register-body">
            <h4 class="auth-register-title">Buat Akun Baru</h4>
            <p class="auth-register-subtitle">Daftar akun Anda untuk mulai menggunakan layanan laundry</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>

                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Masukkan nama lengkap" 
                                   required>

                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>

                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Masukkan email" 
                                   required>

                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>

                            <input type="password" 
                                   class="form-control password-with-toggle @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required>

                            <button type="button" class="btn btn-password-toggle" id="togglePassword">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>

                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>

                            <input type="password" 
                                   class="form-control password-with-toggle" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi password" 
                                   required>

                            <button type="button" class="btn btn-password-toggle" id="togglePasswordConfirmation">
                                <i class="fas fa-eye" id="togglePasswordConfirmationIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">
                        No. Telepon <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>

                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="Masukkan no. telepon" 
                               required>

                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">
                        Alamat <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <span class="input-group-text align-items-start pt-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>

                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3" 
                                  placeholder="Masukkan alamat lengkap" 
                                  required>{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <button type="submit" class="btn btn-register-auth w-100 mt-2">
                    <i class="fas fa-user-check me-2"></i>Register
                </button>
                
                <div class="auth-divider">
                    <span>atau</span>
                </div>

                <div class="auth-login-link">
                    <span>Sudah punya akun? </span>
                    <a href="{{ route('login') }}">Login disini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('togglePassword');
        const passwordIcon = document.getElementById('togglePasswordIcon');

        const confirmInput = document.getElementById('password_confirmation');
        const confirmToggle = document.getElementById('togglePasswordConfirmation');
        const confirmIcon = document.getElementById('togglePasswordConfirmationIcon');

        if (passwordToggle && passwordInput && passwordIcon) {
            passwordToggle.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';

                passwordIcon.classList.toggle('fa-eye');
                passwordIcon.classList.toggle('fa-eye-slash');
            });
        }

        if (confirmToggle && confirmInput && confirmIcon) {
            confirmToggle.addEventListener('click', function () {
                const isPassword = confirmInput.type === 'password';

                confirmInput.type = isPassword ? 'text' : 'password';

                confirmIcon.classList.toggle('fa-eye');
                confirmIcon.classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
@endsection