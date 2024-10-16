<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\LoginController;
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
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::post('/registerSave', [LoginController::class, 'registerSave'])->name('registerSave');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Routes for header navigations------------------------------------------------------------------------------------------------------
Route::controller(supervisorController::class)->group(function() {
    Route::get('/admin/dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/adding', 'inventory')->name('inventory');
    Route::get('/admin/pending', 'pending')->name('pending');
    Route::get('/admin/report', 'report')->name('report');
    Route::get('/admin/pos', 'pos')->name('pos');
    
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
// //-------------------------------------------------------------------------------------------------------