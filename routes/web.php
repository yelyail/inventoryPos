<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\printController;
use App\Http\Controllers\supervisorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Default - Login
Route::get('/', function () {
    return view('Inventory/Login');
})->name('Login');

Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/registerSave', [LoginController::class, 'registerSave'])->name('registerSave');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Routes for header navigations------------------------------------------------------------------------------------------------------
Route::controller(supervisorController::class)->group(function() {
    Route::get('/admin/dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/inventory', 'inventory')->name('inventory');
    Route::get('/admin/supplier', 'supplier')->name('supplier');
    Route::get('/admin/report', 'report')->name('report');
    Route::get('/admin/pos', 'pos')->name('pos');
    Route::get('/admin/user', 'user')->name('user');
    Route::get('/admin/pending', 'pending')->name('pending');
    Route::get('/admin/salesReport', 'salesReport')->name('salesReport');
    
});
Route::controller(supervisorController::class)->group(function() {
   Route::post('/admin/StoreSupply', 'storeSupplier')->name('storeSupplier');
   Route::post('/admin/storeProduct', 'storeInventory')->name('storeInventory');
   Route::post('/admin/storePending', 'storePending')->name('storePending');
   Route::patch('/admin/approve/{id}', 'approve')->name('approve');
   Route::patch('/admin/userarchive/{id}', 'userArchive')->name('userArchive');
   Route::patch('/admin/supplierArchive/{id}', 'supplierArchive')->name('supplierArchive');
   Route::patch('/admin/inventoryArchive/{id}', 'inventoryArchive')->name('inventoryArchive');
   
   Route::post('/admin/updateProduct', 'updateProduct')->name('updateProduct');
   Route::post('/admin/editUser', 'editUser')->name('editUser');
   Route::post('/admin/editSupplier', 'editSupplier')->name('editSupplier');
   Route::post('/admin/requestRepair', 'requestRepair')->name('requestRepair');
   Route::post('/admin/requestReplace', 'requestReplace')->name('requestReplace');
});

Route::post('/storeSerial', [supervisorController::class, 'storeSerial'])->name('storeSerial');
Route::put('/serials/{serialId}/update', [supervisorController::class, 'updateSerial'])->name('updateSerial');
Route::post('/storeOrder', [supervisorController::class, 'storeOrder'])->name('storeOrder');
// for the user
Route::controller(supervisorController::class)->group(function() {
    Route::get('staff/dashboard', 'staffDashboard')->name('staffDashboard');
    Route::get('staff/inventory', 'staffInventory')->name('staffInventory');
    Route::get('staff/pending', 'staffPending')->name('staffPending');
    Route::get('staff/pos', 'staffPos')->name('staffPos');
});
Route::controller(supervisorController::class)->group(function() {
    Route::post('staff/storeSerial', 'staffStoreSerial')->name('staffStoreSerial');
    Route::post('staff/staffStorePending', 'staffStorePending')->name('staffStorePending');
    Route::patch('staff/approve/{id}', 'staffApprove')->name('staffApprove');
});
Route::get('/orderReceipt/{orderreceipts_id}', [printController::class, 'orderReceiptPrint'])->name('orderReceiptPrint');

Route::get('/inventoryReportPrint', [printController::class, 'inventoryReportPrint'])->name('inventoryReportPrint');
Route::get('/salesReportPrint',[printController::class, 'salesReportPrint'])->name('salesReportPrint');

// require __DIR__.'/auth.php';
// //-------------------------------------------------------------------------------------------------------