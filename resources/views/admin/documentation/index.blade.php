@extends('layouts.app')

@section('title', 'Dokumentasi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Dokumentasi Aplikasi Laundry</h2>
            <p class="text-muted">Panduan lengkap penggunaan aplikasi laundry</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group sticky-top" style="top: 20px;">
                <a href="#getting-started" class="list-group-item list-group-item-action">Memulai</a>
                <a href="#admin-guide" class="list-group-item list-group-item-action">Panduan Admin</a>
                <a href="#karyawan-guide" class="list-group-item list-group-item-action">Panduan Karyawan</a>
                <a href="#customer-guide" class="list-group-item list-group-item-action">Panduan Customer</a>
                <a href="#faq" class="list-group-item list-group-item-action">FAQ</a>
                <a href="#troubleshooting" class="list-group-item list-group-item-action">Troubleshooting</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Getting Started -->
            <div id="getting-started" class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-rocket me-2"></i>Memulai</h4>
                </div>
                <div class="card-body">
                    <h5>Cara Login</h5>
                    <ol>
                        <li>Buka halaman login aplikasi</li>
                        <li>Masukkan email dan password yang telah didaftarkan</li>
                        <li>Klik tombol "Login"</li>
                        <li>Anda akan diarahkan ke dashboard sesuai role (Admin/Karyawan/Customer)</li>
                    </ol>
                    
                    <h5 class="mt-4">Cara Registrasi</h5>
                    <ol>
                        <li>Klik "Daftar disini" pada halaman login</li>
                        <li>Isi formulir registrasi dengan lengkap:
                            <ul>
                                <li>Nama Lengkap</li>
                                <li>Email</li>
                                <li>Password</li>
                                <li>No. Telepon</li>
                                <li>Alamat</li>
                                <li>Pilih Role (Admin/Karyawan/Customer)</li>
                            </ul>
                        </li>
                        <li>Klik tombol "Register"</li>
                        <li>Setelah berhasil, silakan login menggunakan akun yang telah dibuat</li>
                    </ol>
                </div>
            </div>
            
            <!-- Admin Guide -->
            <div id="admin-guide" class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-user-shield me-2"></i>Panduan Admin</h4>
                </div>
                <div class="card-body">
                    <h5>1. Dashboard Administrator</h5>
                    <p>Menampilkan ringkasan data:</p>
                    <ul>
                        <li>Statistik transaksi harian dan bulanan</li>
                        <li>Grafik pendapatan 7 hari terakhir</li>
                        <li>Status transaksi</li>
                        <li>Transaksi terbaru</li>
                    </ul>
                    
                    <h5>2. Tambah User Karyawan</h5>
                    <ul>
                        <li>Buka menu "Kelola User"</li>
                        <li>Klik "Tambah User"</li>
                        <li>Isi data user dan pilih role "Karyawan"</li>
                        <li>Klik "Simpan"</li>
                    </ul>
                    
                    <h5>3. Lihat Data Transaksi</h5>
                    <ul>
                        <li>Buka menu "Data Transaksi"</li>
                        <li>Gunakan filter untuk mencari transaksi spesifik</li>
                        <li>Klik "Detail" untuk melihat informasi lengkap transaksi</li>
                        <li>Admin dapat mengubah status transaksi</li>
                    </ul>
                    
                    <h5>4. Data Finance</h5>
                    <ul>
                        <li>Buka menu "Data Finance"</li>
                        <li>Lihat ringkasan pendapatan</li>
                        <li>Filter berdasarkan tahun dan bulan</li>
                        <li>Lihat grafik pendapatan harian dan metode pembayaran</li>
                    </ul>
                    
                    <h5>5. Data Harga</h5>
                    <ul>
                        <li>Buka menu "Data Harga"</li>
                        <li>Tambah/edit harga untuk setiap kategori dan tipe layanan</li>
                        <li>Aktifkan/nonaktifkan harga dengan toggle switch</li>
                    </ul>
                    
                    <h5>6. Atur Target Laundry</h5>
                    <ul>
                        <li>Buka menu "Atur Target"</li>
                        <li>Tambah target pendapatan per bulan</li>
                        <li>Lihat progress pencapaian target</li>
                    </ul>
                    
                    <h5>7. Ubah Tema</h5>
                    <ul>
                        <li>Klik menu "Ubah Tema" di sidebar</li>
                        <li>Pilih tema Dark atau White</li>
                    </ul>
                    
                    <h5>8. Data Bank</h5>
                    <ul>
                        <li>Buka menu "Data Bank"</li>
                        <li>Tambah rekening bank untuk pembayaran customer</li>
                        <li>Upload QR Code untuk memudahkan pembayaran</li>
                    </ul>
                    
                    <h5>9. Setting Notifikasi</h5>
                    <ul>
                        <li>Buka menu "Setting Notifikasi"</li>
                        <li>Konfigurasi Email, Telegram, dan WhatsApp</li>
                        <li>Pilih event notifikasi yang akan dikirim</li>
                        <li>Test notifikasi untuk memastikan konfigurasi berhasil</li>
                    </ul>
                </div>
            </div>
            
            <!-- Karyawan Guide -->
            <div id="karyawan-guide" class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-user-tie me-2"></i>Panduan Karyawan</h4>
                </div>
                <div class="card-body">
                    <h5>1. Dashboard Karyawan</h5>
                    <ul>
                        <li>Menampilkan statistik order hari ini</li>
                        <li>Order pending dan processing</li>
                        <li>Daftar order terbaru</li>
                    </ul>
                    
                    <h5>2. Data Order Masuk</h5>
                    <ul>
                        <li>Lihat semua order laundry</li>
                        <li>Filter berdasarkan status</li>
                        <li>Update status order (pending → processing → washing → drying → ironing → completed)</li>
                    </ul>
                    
                    <h5>3. Data Customer</h5>
                    <ul>
                        <li>Lihat daftar semua customer</li>
                        <li>Cari customer berdasarkan nama atau email</li>
                        <li>Lihat riwayat transaksi customer</li>
                        <li>Edit data customer</li>
                    </ul>
                    
                    <h5>4. Tambah Customer</h5>
                    <ul>
                        <li>Buka menu "Data Customer"</li>
                        <li>Klik "Tambah Customer"</li>
                        <li>Isi data customer (nama, email, telepon, alamat)</li>
                        <li>Klik "Simpan"</li>
                    </ul>
                    
                    <h5>5. Tambah Transaksi Laundry</h5>
                    <ul>
                        <li>Buka menu "Tambah Transaksi"</li>
                        <li>Pilih customer (atau tambah customer baru)</li>
                        <li>Pilih item laundry dan jumlah/berat</li>
                        <li>Sistem akan menghitung total otomatis</li>
                        <li>Pilih metode pembayaran</li>
                        <li>Klik "Simpan Transaksi"</li>
                    </ul>
                    
                    <h5>6. Laporan</h5>
                    <ul>
                        <li>Buka menu "Laporan"</li>
                        <li>Pilih rentang tanggal</li>
                        <li>Lihat ringkasan transaksi</li>
                        <li>Cetak laporan dalam format PDF</li>
                        <li>Cetak struk untuk customer</li>
                    </ul>
                </div>
            </div>
            
            <!-- Customer Guide -->
            <div id="customer-guide" class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Panduan Customer</h4>
                </div>
                <div class="card-body">
                    <h5>1. Dashboard Customer</h5>
                    <ul>
                        <li>Lihat ringkasan aktivitas laundry</li>
                        <li>Card pilihan jenis laundry (pakaian, sepatu, tas, dll)</li>
                        <li>Riwayat transaksi terbaru</li>
                    </ul>
                    
                    <h5>2. Memilih Laundry</h5>
                    <ul>
                        <li>Klik card jenis laundry yang diinginkan</li>
                        <li>Pilih item laundry dan jumlah/berat</li>
                        <li>Sistem akan menghitung estimasi biaya</li>
                        <li>Klik "Buat Pesanan"</li>
                    </ul>
                    
                    <h5>3. Pembayaran</h5>
                    <ul>
                        <li>Buka menu "Pembayaran"</li>
                        <li>Lihat daftar transaksi yang belum lunas</li>
                        <li>Pilih transaksi yang akan dibayar</li>
                        <li>Pilih metode pembayaran (Cash/Transfer/QRIS)</li>
                        <li>Upload bukti pembayaran jika transfer</li>
                        <li>Klik "Proses Pembayaran"</li>
                    </ul>
                    
                    <h5>4. Cetak Struk</h5>
                    <ul>
                        <li>Buka detail transaksi yang sudah lunas</li>
                        <li>Klik tombol "Cetak Struk"</li>
                        <li>Struk akan diunduh dalam format PDF</li>
                    </ul>
                    
                    <h5>5. Notifikasi</h5>
                    <ul>
                        <li>Buka menu "Notifikasi"</li>
                        <li>Lihat semua notifikasi status laundry</li>
                        <li>Notifikasi akan muncul saat status order berubah</li>
                    </ul>
                </div>
            </div>
            
            <!-- FAQ -->
            <div id="faq" class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions (FAQ)</h4>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Bagaimana cara reset password?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Saat ini fitur reset password dapat dilakukan dengan menghubungi admin. Admin dapat mengubah password user melalui menu "Kelola User".
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Apakah bisa membatalkan order?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Order dapat dibatalkan jika status masih "pending". Customer dapat menghubungi karyawan untuk membatalkan order. Admin juga dapat membatalkan order melalui menu "Data Transaksi".
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Berapa lama waktu pengerjaan laundry?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Waktu pengerjaan tergantung tipe layanan yang dipilih:
                                    <ul>
                                        <li>Regular: 2-3 hari</li>
                                        <li>Express: 1 hari</li>
                                        <li>VIP: 4-6 jam</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Apakah ada layanan antar jemput?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Fitur antar jemput sedang dalam pengembangan. Untuk saat ini, customer diharapkan datang langsung ke outlet laundry.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Bagaimana jika ada kerusakan pada pakaian?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Jika terjadi kerusakan, customer dapat melaporkan ke karyawan. Akan ada proses komplain dan kompensasi sesuai kebijakan outlet.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Troubleshooting -->
            <div id="troubleshooting" class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="fas fa-wrench me-2"></i>Troubleshooting</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Masalah Umum dan Solusi</h5>
                        <ul>
                            <li><strong>Tidak bisa login:</strong> Pastikan email dan password benar. Cek koneksi internet.</li>
                            <li><strong>Halaman tidak loading:</strong> Refresh halaman atau clear cache browser.</li>
                            <li><strong>Notifikasi tidak terkirim:</strong> Periksa konfigurasi notifikasi di menu Setting Notifikasi.</li>
                            <li><strong>QR Code tidak muncul:</strong> Pastikan file QR Code sudah diupload dan formatnya benar.</li>
                            <li><strong>PDF tidak bisa diunduh:</strong> Pastikan ekstensi DOM PDF terinstall dengan benar.</li>
                        </ul>
                    </div>
                    
                    <h5>Kontak Dukungan Teknis</h5>
                    <p>Jika mengalami masalah yang tidak dapat diatasi, silakan hubungi:</p>
                    <ul>
                        <li><i class="fas fa-envelope me-2"></i> Email: support@laundryapp.com</li>
                        <li><i class="fab fa-whatsapp me-2"></i> WhatsApp: 0812-3456-7890</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('.list-group-item').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
@endpush
@endsection