<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ThemeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('karyawan.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    return redirect()->route('login');
});

// Route /home agar setelah register/login tidak 404
Route::get('/home', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('karyawan.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    return redirect()->route('login');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Theme Toggle
Route::post('/theme/update', [ThemeController::class, 'update'])->name('theme.update')->middleware('auth');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);
    Route::put('/transactions/{transaction}/status', [App\Http\Controllers\Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::get('/transactions/{transaction}/download-invoice', [App\Http\Controllers\Admin\TransactionController::class, 'downloadInvoice'])->name('transactions.download-invoice');
    Route::get('/finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance');
    
    // Price Management
    Route::get('/prices', [App\Http\Controllers\Admin\PriceController::class, 'index'])->name('prices.index');
    Route::get('/prices/create', [App\Http\Controllers\Admin\PriceController::class, 'create'])->name('prices.create');
    Route::post('/prices', [App\Http\Controllers\Admin\PriceController::class, 'store'])->name('prices.store');
    Route::get('/prices/{price}/edit', [App\Http\Controllers\Admin\PriceController::class, 'edit'])->name('prices.edit');
    Route::put('/prices/{price}', [App\Http\Controllers\Admin\PriceController::class, 'update'])->name('prices.update');
    Route::delete('/prices/{price}', [App\Http\Controllers\Admin\PriceController::class, 'destroy'])->name('prices.destroy');
    Route::put('/prices/{price}/toggle-status', [App\Http\Controllers\Admin\PriceController::class, 'toggleStatus'])->name('prices.toggle-status');
    
    Route::resource('targets', App\Http\Controllers\Admin\TargetLaundryController::class);
    
    // Bank Management
    Route::get('/banks', [App\Http\Controllers\Admin\BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/create', [App\Http\Controllers\Admin\BankController::class, 'create'])->name('banks.create');
    Route::post('/banks', [App\Http\Controllers\Admin\BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/{bank}/edit', [App\Http\Controllers\Admin\BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/{bank}', [App\Http\Controllers\Admin\BankController::class, 'update'])->name('banks.update');
    Route::delete('/banks/{bank}', [App\Http\Controllers\Admin\BankController::class, 'destroy'])->name('banks.destroy');
    Route::put('/banks/{bank}/toggle-status', [App\Http\Controllers\Admin\BankController::class, 'toggleStatus'])->name('banks.toggle-status');
    
    Route::get('/notifications/settings', [App\Http\Controllers\Admin\NotificationSettingController::class, 'index'])->name('notifications.settings');
    Route::put('/notifications/settings', [App\Http\Controllers\Admin\NotificationSettingController::class, 'update'])->name('notifications.settings.update');
    Route::post('/notifications/test', [App\Http\Controllers\Admin\NotificationSettingController::class, 'test'])->name('notifications.test');
    Route::get('/documentation', [App\Http\Controllers\Admin\DocumentationController::class, 'index'])->name('documentation');
});

// ==================== KARYAWAN ROUTES ====================
Route::middleware(['auth'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [App\Http\Controllers\Karyawan\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{transaction}', [App\Http\Controllers\Karyawan\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{transaction}/status', [App\Http\Controllers\Karyawan\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{transaction}/download-proof', [App\Http\Controllers\Karyawan\OrderController::class, 'downloadPaymentProof'])->name('orders.download-proof');
    Route::get('/orders/{transaction}/download-invoice', [App\Http\Controllers\Karyawan\OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    Route::resource('customers', App\Http\Controllers\Karyawan\CustomerController::class);
    Route::get('/transactions/create', [App\Http\Controllers\Karyawan\TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [App\Http\Controllers\Karyawan\TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/transactions/{transaction}/payment', [App\Http\Controllers\Karyawan\TransactionController::class, 'processPayment'])->name('transactions.payment');
    Route::get('/reports', [App\Http\Controllers\Karyawan\ReportController::class, 'index'])->name('reports');
    Route::get('/reports/print', [App\Http\Controllers\Karyawan\ReportController::class, 'printReport'])->name('reports.print');
    Route::get('/reports/struk/{transaction}', [App\Http\Controllers\Karyawan\ReportController::class, 'receipt'])->name('reports.struk');
});

// ==================== CUSTOMER ROUTES ====================
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laundry/{type}', [App\Http\Controllers\Customer\LaundryController::class, 'select'])->name('laundry.select');
    Route::post('/order', [App\Http\Controllers\Customer\OrderController::class, 'store'])->name('order.store');
    Route::get('/payments', [App\Http\Controllers\Customer\PaymentController::class, 'index'])->name('payments');
    Route::post('/payments/{transaction}', [App\Http\Controllers\Customer\PaymentController::class, 'process'])->name('payments.process');
    Route::get('/receipt/{transaction}', [App\Http\Controllers\Customer\ReceiptController::class, 'download'])->name('receipt.download');
    Route::get('/invoice/{transaction}/download', [App\Http\Controllers\Customer\InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-read', [App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Customer\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    
    // HAPUS 'customer.' prefix karena sudah ada di group
    Route::get('/transaction/detail', [App\Http\Controllers\Customer\NotificationController::class, 'getTransactionDetail'])->name('transaction.detail');
});