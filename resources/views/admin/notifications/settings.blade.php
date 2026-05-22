@extends('layouts.app')

@section('title', 'Setting Notifikasi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Setting Notifikasi</h2>
            <p class="text-muted">Konfigurasi pengiriman notifikasi via Email, Telegram, dan WhatsApp</p>
        </div>
    </div>
    
    <form method="POST" action="{{ route('admin.notifications.settings') }}">
        @csrf
        @method('PUT')
        
        <!-- Email Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Pengaturan Email</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email_host" class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" id="email_host" name="email_host" 
                               value="{{ $settings['email_host'] ?? '' }}" placeholder="smtp.gmail.com">
                        <small class="text-muted">Contoh: smtp.gmail.com</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_port" class="form-label">SMTP Port</label>
                        <input type="text" class="form-control" id="email_port" name="email_port" 
                               value="{{ $settings['email_port'] ?? '' }}" placeholder="587">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_username" class="form-label">Email Username</label>
                        <input type="email" class="form-control" id="email_username" name="email_username" 
                               value="{{ $settings['email_username'] ?? '' }}" placeholder="your-email@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_password" class="form-label">Email Password / App Password</label>
                        <input type="password" class="form-control" id="email_password" name="email_password" 
                               value="{{ $settings['email_password'] ?? '' }}" placeholder="********">
                        <small class="text-muted">Untuk Gmail, gunakan App Password</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Telegram Settings -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fab fa-telegram me-2"></i>Pengaturan Telegram</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telegram_bot_token" class="form-label">Bot Token</label>
                        <input type="text" class="form-control" id="telegram_bot_token" name="telegram_bot_token" 
                               value="{{ $settings['telegram_bot_token'] ?? '' }}" placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz">
                        <small class="text-muted">Dapatkan dari @BotFather di Telegram</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telegram_chat_id" class="form-label">Chat ID</label>
                        <input type="text" class="form-control" id="telegram_chat_id" name="telegram_chat_id" 
                               value="{{ $settings['telegram_chat_id'] ?? '' }}" placeholder="-123456789">
                        <small class="text-muted">Chat ID grup atau user yang akan menerima notifikasi</small>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fab fa-telegram me-2"></i>
                    <strong>Cara mendapatkan Chat ID:</strong> Kirim pesan ke bot, lalu kunjungi 
                    <code>https://api.telegram.org/bot[YOUR_TOKEN]/getUpdates</code>
                </div>
            </div>
        </div>
        
        <!-- WhatsApp Settings -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fab fa-whatsapp me-2"></i>Pengaturan WhatsApp</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_api_key" class="form-label">API Key</label>
                        <input type="text" class="form-control" id="whatsapp_api_key" name="whatsapp_api_key" 
                               value="{{ $settings['whatsapp_api_key'] ?? '' }}" placeholder="your-api-key">
                        <small class="text-muted">API Key dari layanan WhatsApp Gateway (contoh: Fonnte, WATI)</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_sender_number" class="form-label">Nomor Pengirim</label>
                        <input type="text" class="form-control" id="whatsapp_sender_number" name="whatsapp_sender_number" 
                               value="{{ $settings['whatsapp_sender_number'] ?? '' }}" placeholder="628123456789">
                    </div>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Catatan:</strong> Pastikan Anda sudah memiliki akun di layanan WhatsApp Gateway seperti Fonnte, WATI, atau Wablas.
                </div>
            </div>
        </div>
        
        <!-- Notification Events -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Event Notifikasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notification_on_order" 
                                   name="notification_on_order" value="1" 
                                   {{ isset($settings['notification_on_order']) && $settings['notification_on_order'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="notification_on_order">
                                Notifikasi saat order masuk
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notification_on_status_change" 
                                   name="notification_on_status_change" value="1" 
                                   {{ isset($settings['notification_on_status_change']) && $settings['notification_on_status_change'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="notification_on_status_change">
                                Notifikasi saat status berubah
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notification_on_payment" 
                                   name="notification_on_payment" value="1" 
                                   {{ isset($settings['notification_on_payment']) && $settings['notification_on_payment'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="notification_on_payment">
                                Notifikasi saat pembayaran
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notification_on_completion" 
                                   name="notification_on_completion" value="1" 
                                   {{ isset($settings['notification_on_completion']) && $settings['notification_on_completion'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="notification_on_completion">
                                Notifikasi saat laundry selesai
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Test Notification Button -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Uji Coba Notifikasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="testNotification('email')">
                            <i class="fas fa-envelope me-2"></i>Test Email
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="button" class="btn btn-outline-info w-100" onclick="testNotification('telegram')">
                            <i class="fab fa-telegram me-2"></i>Test Telegram
                        </button>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="button" class="btn btn-outline-success w-100" onclick="testNotification('whatsapp')">
                            <i class="fab fa-whatsapp me-2"></i>Test WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function testNotification(channel) {
        $.ajax({
            url: '{{ route("admin.notifications.test") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                channel: channel
            },
            success: function(response) {
                if(response.success) {
                    alert('Notifikasi test berhasil dikirim via ' + channel);
                } else {
                    alert('Gagal mengirim notifikasi: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan. Silakan cek konfigurasi Anda.');
            }
        });
    }
</script>
@endpush
@endsection