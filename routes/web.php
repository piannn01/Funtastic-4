<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS (CLIENT)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrderTrackController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| CONTROLLERS (ADMIN)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\TestimonialsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderContentController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| REDIRECT /admin → /admin/dashboard
|--------------------------------------------------------------------------
*/
Route::redirect('/admin', '/admin/dashboard');

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('index');
Route::get('/services', [LandingPageController::class, 'services'])->name('services');
Route::get('/testimonials', [LandingPageController::class, 'testimonials'])->name('testimonials');
Route::get('/about', [LandingPageController::class, 'about'])->name('about');

Route::get('/contact', function () {
    return redirect()->away('https://api.whatsapp.com/send/?phone=6289505721124');
})->name('contact');

/*
|--------------------------------------------------------------------------
| INVOICE
|--------------------------------------------------------------------------
*/
Route::get('/invoice/{invoice_code}', [InvoiceController::class, 'show'])
    ->name('invoice.show');

Route::get('/invoice/{invoice_code}/download', [InvoiceController::class, 'download'])
    ->name('invoice.download');

Route::get('/payment/success/update-status/{order}', [LandingPageController::class, 'markAsPaid'])
    ->name('payment.markAsPaid');

/*
|--------------------------------------------------------------------------
| CEK PESANAN (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/cek-pesanan', [OrderTrackController::class, 'form'])
    ->name('cekpesanan');

Route::post('/cek-pesanan', [OrderTrackController::class, 'check'])
    ->name('cekpesanan.check');

Route::get('/cek-pesanan/hasil', [OrderTrackController::class, 'hasil'])
    ->name('cekpesanan.hasil');

/*
|--------------------------------------------------------------------------
| PREVIEW FILE KONTEN (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/preview-content/{id}', [OrderTrackController::class, 'preview'])
    ->name('orders.content.preview');

/*
|--------------------------------------------------------------------------
| DOWNLOAD FILE KONTEN (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/download-content/{id}', [OrderContentController::class, 'download'])
    ->name('orders.content.download');

/*
|--------------------------------------------------------------------------
| CLIENT TESTIMONIAL
| (Tetap pakai LandingPageController biar tidak break)
|--------------------------------------------------------------------------
*/
Route::get('/testimonial/create/{order}', [LandingPageController::class, 'createTestimonial'])
    ->name('testimonial.create');

Route::post('/testimonial/store/{order}', [LandingPageController::class, 'storeTestimonial'])
    ->name('testimonial.store');

/*
|--------------------------------------------------------------------------
| ORDER LAYANAN (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/order/{service}', [LandingPageController::class, 'orderForm'])
    ->name('order.form');

Route::post('/order/{service}', [LandingPageController::class, 'orderSubmit'])
    ->name('order.submit');

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK
|--------------------------------------------------------------------------
*/
Route::get('/payment/{order}', [LandingPageController::class, 'paymentPage'])
    ->name('payment.page');

Route::post('/midtrans/callback', [LandingPageController::class, 'midtransCallback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');

/*
|------------------------------------------------------------------------------------------------------------------------------------------
| ADMIN PANEL (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin.auth'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD Services
        Route::resource('/services', ServicesController::class);

        // CRUD Testimonials
        Route::resource('/testimonials', TestimonialsController::class);

        // CRUD Orders
        Route::resource('/orders', OrderController::class);

        // Update Progress Order (catatan admin)
        Route::post('/orders/{order}/update-progress', [OrderController::class, 'updateProgress'])
            ->name('orders.updateProgress');

        /*
        |----------------------------------------------------------------------
        | UPLOAD / DELETE KONTEN (ADMIN)
        |----------------------------------------------------------------------
        */
        Route::post('/orders/{order}/content', [OrderContentController::class, 'store'])
            ->name('orders.content.store');

        Route::delete('/orders/content/{id}', [OrderContentController::class, 'delete'])
            ->name('orders.content.delete');

        /*
        |----------------------------------------------------------------------
        | SETTINGS WEBSITE
        |----------------------------------------------------------------------
        */
        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::post('/settings/update', [SettingController::class, 'update'])
            ->name('settings.update');

        Route::post('/settings/logo/update', [SettingController::class, 'updateLogo'])
            ->name('settings.logo.update');

        Route::post('/settings/seo/update', [SettingController::class, 'updateSeo'])
            ->name('settings.seo.update');
    });
