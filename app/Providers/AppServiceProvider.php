<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Route; // <<< WAJIB ditambahkan
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\EnsureUserHasRole; // <<< WAJIB di-import

class AppServiceProvider extends ServiceProvider
{
    // ... method register()

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ** BAGIAN KRITIS: Mendaftarkan Middleware Role **
        Route::aliasMiddleware('role', EnsureUserHasRole::class);

        // Atau, jika Anda ingin mendaftarkannya sebagai Route Middleware (lebih tradisional):
        /*
        Route::aliasMiddleware('role', EnsureUserHasRole::class);
        */
    }
}
