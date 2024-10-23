<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\supplier;
use App\Models\category;
use App\Models\product;
use App\Models\User;
use App\Models\serial;
use App\Models\inventory;
use App\Models\order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class supervisorController extends Controller
{
    public function dashboard() {
        $pageTitle = 'Dashboard'; 
        return view('Inventory/dashboard', compact('pageTitle'));
    }
    public function pos() {
        return view('Inventory/pos');
    }
    // adding/viewing of inventory
    public function inventory() {
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count') 
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id') // Use LEFT JOIN to count serials
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit'
            )
            ->get();
    
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->pluck('serial_number')
                ->toArray();
        }
    
        $suppliers = supplier::all();
        return view('Inventory/inventory', compact('products', 'suppliers'));
    }
    public function storeInventory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'typeOfUnit' => 'required|string|max:50',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|integer',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        Log::info($request);
        try {
            $imagePath = $request->file('product_image')->store('products', 'public');

            $category = category::where('category_name', $request->category_name)->first();
            if (!$category) {
                $category = category::create([
                    'category_name' => $request->category_name,
                    'brand_name' => $request->brand_name,
                ]);
            }
            $product = product::create([
                'supplier_id' => $request->supplierName,
                'category_Id' => $category->category_id, // Use the ID of the existing or newly created category
                'product_name' => $request->product_name,
                'unitPrice' => $request->unitPrice,
                'added_date' => $request->added_date,
                'typeOfUnit' => $request->typeOfUnit,
                'product_image' => $imagePath,
            ]);
            $expirationDate = match($request->warrantyUnit) {
                'days' => now()->addDays($request->warranty_supplier),
                'weeks' => now()->addWeeks($request->warranty_supplier),
                'months' => now()->addMonths($request->warranty_supplier),
                default => null,
            };

            inventory::create([
                'product_id' => $product->product_id,
                'date_arrived' => $request->added_date,
                'warranty_supplier' => $expirationDate,
                'status' => 'approve',
            ]);

            return redirect()->back()->with('success', 'Product added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add product: ', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }
    // adding new serial
    public function storeSerial(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|max:255',
            'product_id' => 'required|exists:product,product_id',
        ]);

        $serial = new Serial();
        $serial->serial_number = $request->serial_number;
        $serial->product_id = $request->product_id;
        $serial->save();
        return response()->json(['success' => 'Serial number added successfully.']);
    }

    // adding/viewing of supplier
    public function supplier() {
        $suppliers = supplier::all();

        $users = User::where(function($query) {
            $query->where('archived', 0)
                ->orWhereNull('archived');
        })->get();        
        
        return view('Inventory/supplier', compact('suppliers', 'users'));
    }
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'required|email',
            'supplier_contact' => 'required|digits:10', // 10 digits for the phone number
            'supplier_address' => 'required|string',
        ]);

        $supplier = new supplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_phone = $request->supplier_contact;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->status =0;
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier added successfully!');
    }
    public function user() {
        $users = User::all();
        return view('Inventory/usermanagement', compact('users'));
    }
    
    public function report() {
        return view('Inventory/Report');
    }
    public function salesReport() {
        return view('Inventory/salesReport');
    }
    public function pending() {  
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count') 
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id') // Use LEFT JOIN to count serials
            ->where('inventory.status', '=', 'pending')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit'
            )
            ->get();
    
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->pluck('serial_number')
                ->toArray();
        }
    
        $suppliers = supplier::all();
        return view('Inventory/pending', compact('products'));
    }

    // for the buttons here
    public function approve($id) {
        try {
            DB::table('inventory')
                ->where('product_id', $id)
                ->update(['status' => 'approve']); // Assuming 'status' column exists
    
            return response()->json(['success' => true, 'message' => 'Product approved successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve product: ' . $e->getMessage()]);
        }
    }
    
    // for the user
    public function staffPos() {
        return view('OfficeStaff/pos');
    }
    public function staffDashboard() {
        return view('OfficeStaff/staffdashboard');
    }
    public function staffInventory() {
        return view('OfficeStaff/staffInvside');
    }
    public function staffPending() {
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count') 
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id') // Use LEFT JOIN to count serials
            ->where('inventory.status', '=', 'pending')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit'
            )
            ->get();
    
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->pluck('serial_number')
                ->toArray();
        }
    
        $suppliers = supplier::all();
        return view('OfficeStaff/staffpending',compact('products', 'suppliers'));
    }
    public function staffStorePending(Request $request){
        $request->validate([
            'category_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'typeOfUnit' => 'required|string|max:50',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|integer',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        Log::info($request);
        try {
            $imagePath = $request->file('product_image')->store('products', 'public');

            $category = category::where('category_name', $request->category_name)->first();
            if (!$category) {
                $category = category::create([
                    'category_name' => $request->category_name,
                    'brand_name' => $request->brand_name,
                ]);
            }
            $product = product::create([
                'supplier_id' => $request->supplierName,
                'category_Id' => $category->category_id, 
                'product_name' => $request->product_name,
                'unitPrice' => $request->unitPrice,
                'added_date' => $request->added_date,
                'typeOfUnit' => $request->typeOfUnit,
                'product_image' => $imagePath,
            ]);
            $expirationDate = match($request->warrantyUnit) {
                'days' => now()->addDays($request->warranty_supplier),
                'weeks' => now()->addWeeks($request->warranty_supplier),
                'months' => now()->addMonths($request->warranty_supplier),
                default => null,
            };

            inventory::create([
                'product_id' => $product->product_id,
                'date_arrived' => $request->added_date,
                'warranty_supplier' => $expirationDate,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Product added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add product: ', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }
}