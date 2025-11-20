<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Verify;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Passwords\Confirm;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Admin\ProductManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->name('logout');
});

Route::get('/test', function () {
    return view('test');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/products', ProductManagementController::class)->only(['index', 'show', 'destroy'])->names('admin.products');

    Route::resource('/users', UserManagementController::class)->only(['index', 'show'])->names('admin.users');

});

Route::middleware(['auth', 'role:vendor,admin'])->prefix('vendor')->group(function () {
    
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendors.dashboard');

    Route::resource('products', VendorProductController::class) 
        ->names('vendors.products');

    // -----------------------------------------------------
    // Rute 3: Manajemen Pesanan Masuk (Orders)
    // -----------------------------------------------------
    Route::resource('orders', VendorOrderController::class)
        ->only(['index', 'show']) // Hanya perlu melihat daftar dan detail
        ->names('vendors.orders');
        
    // Custom Route: Aksi untuk Mengkonfirmasi Pesanan (Contoh: POST)
    Route::post('orders/{order}/confirm', [VendorOrderController::class, 'confirm'])
        ->name('vendors.orders.confirm');

    Route::post('orders/{order}/reject', [VendorOrderController::class, 'reject'])
        ->name('vendors.orders.reject');
    
        // Custom Route: Aksi Sedang Disewa (processing/paid -> rented)
    Route::post('orders/{order}/rented', [VendorOrderController::class, 'rented'])
         ->name('vendors.orders.rented');

    // Custom Route: Aksi Selesai (rented -> completed)
    Route::post('orders/{order}/complete', [VendorOrderController::class, 'complete'])
         ->name('vendors.orders.complete');
});

// ---------------------------------------------------------------------
// 🌍 ROUTE GRUP PUBLIK (Guest, Customer, Vendor, Admin)
// ---------------------------------------------------------------------

Route::controller(PublicController::class)->group(function () {
    
    // Rute 1: Katalog Barang (products.index)
    // Bisa diakses oleh semua, menampilkan tombol "Pesan Sekarang"
    Route::get('/products', 'index')->name('products.index'); 

    // Rute 2: Detail Produk & Daftar Review (products.show)
    // Bisa diakses oleh semua, menampilkan form review.
    // Catatan: Proses Booking/Review POST harus dilindungi middleware 'auth'.
    Route::get('/products/{product}', 'show')->name('products.show');
});

// Rute Tambahan: Proses Pemesanan (Hanya POST, harus Login)
Route::post('/orders/checkout', [CustomerController::class, 'processBooking'])
    ->middleware('auth') // Wajib login untuk aksi ini
    ->name('orders.checkout');

Route::middleware(['auth', 'role:admin,vendor,customer'])->prefix('user')->name('user.')->group(function () {
    
    // Catatan: Prefix diubah dari 'customer' menjadi 'user' agar lebih netral
    // -----------------------------------------------------
    // Rute 2: Riwayat Pesanan & Pembatalan (Manajemen Pesanan)
    // -----------------------------------------------------
    Route::prefix('orders')->name('orders.')->controller(CustomerController::class)->group(function () {
        // Logika Controller di sini harus memfilter pesanan HANYA milik Auth::id()
        Route::get('/', 'orderHistory')->name('index');     
        Route::get('/{order}', 'showOrder')->name('show');   
        Route::post('/{order}/cancel', 'cancelOrder')->name('cancel'); 
    });

    // -----------------------------------------------------
    // Rute 3: Pengaturan Profil (Edit Data User)
    // -----------------------------------------------------
    Route::prefix('profile')->name('profile.')->controller(UserController::class)->group(function () {
        Route::get('/', 'showProfile')->name('show');       
        Route::get('/edit', 'editProfile')->name('edit');   
        Route::put('/', 'updateProfile')->name('update');   
    });
    
    // -----------------------------------------------------
    // Rute 4: Proses Review
    // -----------------------------------------------------
    // Catatan: Review harus selalu dilakukan oleh CUSTOMER, tetapi semua user bisa mengirimkan form ini.
    // Otorisasi final (apakah boleh review) harus ada di Controller.
    Route::post('/reviews', [PublicController::class, 'storeReview'])->name('reviews.store');
});

Route::middleware(['auth'])->group(function () {
    // 1. Tampilkan Halaman Informasi Vendor
    Route::get('/become-vendor', [UserController::class, 'becomeVendorForm'])
         ->name('customer.vendor.form');

    // 2. Aksi: Ubah Role User menjadi Vendor (dipicu oleh tombol "Jadi Vendor")
    Route::post('/register-vendor-action', [UserController::class, 'registerVendor'])
         ->name('customer.vendor.register');
});