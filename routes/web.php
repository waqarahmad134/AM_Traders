<?php

use App\Http\Controllers\DipController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\MobilOilController;
use App\Http\Controllers\PurchaseRecordController;
use App\Http\Controllers\SaleRecordController;
use App\Http\Controllers\ShiftDataController;
use App\Http\Controllers\StockTestingController;
use App\Http\Controllers\StockWastageController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Clear Cache facade value:
Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('optimize');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/delete-db', function () {
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    $tables = DB::select('SHOW TABLES');
    $dbName = 'Tables_in_' . DB::getDatabaseName();
    foreach ($tables as $table) {
        Schema::drop($table->$dbName);
    }

    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    return 'All tables dropped successfully!';
});

Route::get('/migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database seeded successfully!';
});

Route::middleware(['checktoken'])->group(function () {});

Route::get('list_users', [App\Http\Controllers\UserController::class, 'list_users'])->name('list_users');
Route::get('employees', [App\Http\Controllers\UserController::class, 'employees'])->name('employees');
Route::post('add_employees', [App\Http\Controllers\UserController::class, 'add_employee'])->name('add_employees');
Route::get('delete_admin/{id}', [App\Http\Controllers\UserController::class, 'delete_admin'])->name('delete_admin');
Route::get('add_admin', function () {
    return view('admin/add_admin');
})->name('add_admin');
Route::post('add_admin', [App\Http\Controllers\UserController::class, 'add_admin'])->name('add_admins');

Route::get('register', function () {
    return view('auth/register');
})->name('register');

Route::post('register', [App\Http\Controllers\UserController::class, 'register'])->name('registers');
Route::get('change_password', function () {
    return view('front-end-settings/change_password');
})->name('change_password');
Route::post('change_password', [App\Http\Controllers\UserController::class, 'change_password_post'])->name('change_password_post');
Route::get('login', [App\Http\Controllers\UserController::class, 'loginget'])->name('login');
Route::post('login', [App\Http\Controllers\UserController::class, 'login'])->name('logins');
Route::get('logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('homess');
    Route::get('profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::post('update_profile', [App\Http\Controllers\UserController::class, 'update_profile'])->name('update_profile');
});

// users routes
Route::get('customers', [App\Http\Controllers\UserController::class, 'customers'])->name('customers');
Route::get('customers_balance', [App\Http\Controllers\UserController::class, 'customers_balance'])->name('customers_balance');
Route::get('staffs', [App\Http\Controllers\UserController::class, 'staffs'])->name('staffs');
Route::get('staffs_balance', [App\Http\Controllers\UserController::class, 'staffs_balance'])->name('staffs_balance');
Route::get('suppliers', [App\Http\Controllers\UserController::class, 'suppliers'])->name('suppliers');
Route::get('list_admin', [App\Http\Controllers\UserController::class, 'list_admin'])->name('list_admin');
Route::get('update_status/{id}', [App\Http\Controllers\UserController::class, 'update_status'])->name('update_status');
Route::post('add_user', [App\Http\Controllers\UserController::class, 'add_user'])->name('add_user');

Route::get('create_invoice', [App\Http\Controllers\UserController::class, 'create_invoice'])->name('create_invoice');

// Route::get('invoice_pdf', function () {
//     return view('invoice_pdf'); 
// })->name('invoice_pdf');

Route::post('invoice.store', [App\Http\Controllers\UserController::class, 'store_invoice'])->name('invoice.store');

// Sale record
Route::get('sale_record', [App\Http\Controllers\SaleRecordController::class, 'index'])->name('sale_record.index');
Route::post('sale_record', [App\Http\Controllers\SaleRecordController::class, 'store'])->name('sale_record.store');
Route::get('sale_record/update_status/{id}', [App\Http\Controllers\SaleRecordController::class, 'updateStatus'])->name('sale_record.updateStatus');
Route::put('sale_record/{id}', [App\Http\Controllers\SaleRecordController::class, 'update'])->name('sale_record.update');
Route::delete('sale_record/{id}', [App\Http\Controllers\SaleRecordController::class, 'destroy'])->name('sale_record.delete');


// purchase record

Route::get('purchase_record', [App\Http\Controllers\PurchaseRecordController::class, 'index'])->name('purchase_record.index');
Route::post('purchase_record', [App\Http\Controllers\PurchaseRecordController::class, 'store'])->name('purchase_record.store');
Route::get('purchase_record/update_status/{id}', [App\Http\Controllers\PurchaseRecordController::class, 'updateStatus'])->name('purchase_record.updateStatus');
Route::put('purchase_record/{id}', [App\Http\Controllers\PurchaseRecordController::class, 'update'])->name('purchase_record.update');
Route::delete('purchase_record/{id}', [App\Http\Controllers\PurchaseRecordController::class, 'destroy'])->name('purchase_record.delete');

Route::get('stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
Route::post('stock', [App\Http\Controllers\StockController::class, 'store'])->name('stock.store');
Route::get('stock/update_status/{id}', [App\Http\Controllers\StockController::class, 'updateStatus'])->name('stock.updateStatus');
Route::put('stock/{id}', [App\Http\Controllers\StockController::class, 'update'])->name('stock.update');
Route::delete('stock/{id}', [App\Http\Controllers\StockController::class, 'destroy'])->name('stock.delete');

Route::get('items', [App\Http\Controllers\UserController::class, 'items'])->name('items');
Route::post('items', [App\Http\Controllers\UserController::class, 'store_items'])->name('items.store');
Route::get('items/update_status/{id}', [App\Http\Controllers\UserController::class, 'itemsUpdateStatus'])->name('items.updateStatus');
Route::put('items/{id}', [App\Http\Controllers\UserController::class, 'items_update'])->name('items.update');
Route::delete('items/{id}', [App\Http\Controllers\UserController::class, 'items_destroy'])->name('items.delete');
