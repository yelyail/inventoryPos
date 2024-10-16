<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\LoginController;




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

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Routes for header navigations------------------------------------------------------------------------------------------------------
Route::post('dashboard', function () {return view('Inventory/dashboard'); })->name('dashboard');

Route::post('Storage', function () {return view('Inventory/Storage'); })->name('Storage');

Route::post('Product', function () {return view('Inventory/Product'); })->name('Product');

Route::post('Invside', function () {return view('Inventory/Invside'); })->name('Invside');

Route::post('Suptech', function () {return view('Inventory/Suptech'); })->name('Suptech');

Route::post('Pending', function () {return view('Inventory/Pending'); })->name('Pending');

Route::post('/Report', function () {return view('Inventory/Report'); })->name('Report');


//Dashboard
Route::get('dashboard', [ViewController::class, 'viewDashboard']);

//Brand
Route::post('Storageadd', [AddingController::class, 'AddBrand'])->name('brand.add');
Route::get('Storage', [ViewController::class, 'viewBrand']);
Route::post('Inventory/Storage/', [UpdateController::class, 'updateBrand'])->name('brand.update');


//Category
Route::post('StorageCatadd', [AddingController::class, 'AddCategory'])->name('cat.add');
Route::post('category/update', [UpdateController::class, 'updateCategory'])->name('category.update');

// Product
Route::post('Productadd', [AddingController::class, 'AddProduct'])->name('prod.add');
Route::get('Product', [ViewController::class, 'viewProduct']);
Route::post('Inventory/product/{Product_ID}', [UpdateController::class, 'updateProduct'])->name('prod.update');
Route::delete('Inventory/product/{Product_ID}', [DeleteController::class, 'deleteProduct'])->name('prod.delete');
Route::patch('Inventory/product/{Product_ID}', [DeleteController::class, 'reactivateProduct'])->name('reactivate.product');

//Pending 
Route::get('Pending', [ViewController::class, 'productSelect']);
Route::post('Pendingadd', [AddingController::class, 'addpending'])->name('pending.add');
Route::patch('/product/approve/{id}', [DeleteController::class, 'ApproveProduct'])->name('product.approved');
Route::post('Inventory/Pending/{Inventory_ID}', [UpdateController::class, 'updatepending'])->name('pending.update');


//Invside
Route::get('Invside', [ViewController::class, 'Inventoryview']);
Route::post('Invsidedefect', [UpdateController::class, 'DefectiveProduct'])->name('defect.update');
Route::post('Invsidereturn', [UpdateController::class, 'ReturnedProduct'])->name('return.update');
Route::post('Invsiderepairedre', [UpdateController::class, 'RepairedSupplier'])->name('repaired.supp');
Route::post('Invsideadd', [AddingController::class, 'addsold'])->name('sold.add');
Route::post('Invsiderepair', [AddingController::class, 'addrepair'])->name('repair.add');
Route::post('Invsideresuccess', [UpdateController::class, 'RepairSuccess'])->name('repair.succ');
Route::post('Invsiderefailed', [UpdateController::class, 'RepairFailed'])->name('repair.failed');
Route::post('Invsidereturnc', [UpdateController::class, 'ReturnedProductCharged'])->name('returnc.update');
Route::post('Invsidecancel', [UpdateController::class, 'SoldCancel'])->name('sold.cancel');
Route::post('Invsiddefectsold', [UpdateController::class, 'DefectiveSold'])->name('sold.defect');
Route::post('Invsidreturnsold', [UpdateController::class, 'SoldReturn'])->name('sold.return');
Route::post('Invsidrepairsold', [UpdateController::class, 'SoldRepair'])->name('sold.repair');
Route::post('Invsideaddback', [AddingController::class, 'addsoldback'])->name('sold.addback');


//Received
Route::get('Received', function () {return view('Inventory/Received'); })->name('Received');

//SupTec
Route::get('Suptech', [ViewController::class, 'viewSuptech']);
Route::post('Supadd', [AddingController::class, 'AddSupplier'])->name('sup.add');
Route::post('Techadd', [AddingController::class, 'AddTechnician'])->name('tech.add');
Route::post('/supplier/update', [UpdateController::class, 'UpdateSupplier'])->name('sup.update');
Route::post('/technician/update', [UpdateController::class, 'UpdateTechnician'])->name('tech.update');


//Sold
Route::post('Inventory/Invside/{id}', [DeleteController::class, 'SalesSold'])->name('product.sold');


//Report
Route::get('Report', [ViewController::class, 'viewReport']);

// //------------------------------------------------------------------------------------
//Office Staff and Technician
Route::post('staffdashboard', function () {return view('OfficeStaff/staffdashboard'); })->name('staffdashboard');
Route::get('staffdashboard', [ViewController::class, 'staffDashboard']);

Route::post('staffInvside', function () {return view('OfficeStaff/staffInvside'); })->name('staffInvside');
Route::get('staffInvside', [ViewController::class, 'staffInventoryview']);

Route::post('staffPending', function () {return view('OfficeStaff/staffPending'); })->name('staffPending');
Route::get('staffPending', [ViewController::class, 'staffproductSelect']);
Route::post('staffPendingadd', [AddingController::class, 'addpendingstaff'])->name('staffpending.add');



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