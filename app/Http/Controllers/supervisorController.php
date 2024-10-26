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
use App\Models\customer;
use App\Models\paymentMethod;
use App\Models\repair;
use App\Models\replace;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class supervisorController extends Controller
{
    public function dashboard() {
        $pageTitle = 'Dashboard';
    
        // pang pending
        $pendingProducts = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                DB::raw('COUNT(serial.serial_number) as serial_count')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->where('inventory.status', '=', 'pending')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.added_date'
            )
            ->get();
        // pang low stocks
        $approvedProducts = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.added_date as date_added',
                'product.typeOfUnit',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                DB::raw('COUNT(serial.serial_number) as serial_count')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'product.typeOfUnit',
                'supplier.supplier_name',
                'product.added_date'
            )->get();

            // pang defective di pa finish yawa
            
            $defectiveProducts = DB::table('replace')
                ->select(
                    'replace.replace_id',
                    'product.product_image',
                    'serial.serial_number',
                    'category.category_name',
                    'category.brand_name',
                    'product.product_name as model_name',
                    'product.typeOfUnit'
                )
                ->leftJoin('inventory', 'replace.inventory_id', '=', 'inventory.inventory_id')
                ->leftJoin('product', 'inventory.product_id', '=', 'product.product_id')
                ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
                ->leftJoin('category', 'product.category_Id', '=', 'category.category_id')
                ->where('serial.status', '=', 'inprogress') // Only select rows with "inprogress" status
                ->whereNotNull('replace.replace_id') // Ensure replace_id is not null
                ->get();

        $products = $pendingProducts->merge($approvedProducts);
    
        return $this->dashboardView('Inventory/dashboard', compact('pageTitle', 'pendingProducts','approvedProducts','defectiveProducts'));
    }
    public function pos() {
        $categories = category::all(); 
        
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'category.category_id',
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
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
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
                    ->select('serial_number', 'created_at')
                    ->where('product_id', $product->product_id)
                    ->get();
            }
        return view('Inventory/pos', compact('categories', 'products'));
    }
    // adding/viewing of inventory di na hilabtan fak shit
    public function inventory() {
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.product_description',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count'),
                DB::raw('GROUP_CONCAT(serial.serial_number) as serial_numbers'),
                'inventory.status'
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
                'product.product_description',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit',
                'inventory.status'
            )
            ->get();
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->select('serial_number', 'created_at')
                ->where('product_id', $product->product_id)
                ->get()
                ->map(function ($serial) {
                    return [
                        'serial_number' => $serial->serial_number,
                        'created_at' => $serial->created_at,
                    ];
                })
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
            'product_description' => 'required|string|max:1000',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|integer',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
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
                'supplier_ID' => $request->supplierName,
                'category_Id' => $category->category_id,
                'product_name' => $request->product_name,
                'unitPrice' => $request->unitPrice,
                'added_date' => $request->added_date,
                'typeOfUnit' => $request->typeOfUnit,
                'product_description' => $request->product_description,
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
    // adding new serial di na hilabtan fak shit
    public function storeSerial(Request $request)
    {
        try {
            $request->validate([
                'serial_number' => 'required|string|max:255',
                'product_id' => 'required|exists:product,product_id',
            ]);
    
            $serial = new serial();
            $serial->serial_number = $request->serial_number;
            $serial->product_id = $request->product_id;
            $serial->status = 'available';
            $serial->save();
    
            return response()->json(['success' => 'Serial number added successfully.']);
        } catch (\Exception $e) {
            Log::error('Error storing serial: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while saving the serial number.'], 500);
        }
    }
    // adding/viewing of supplier di na hilabtan fak shit
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
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'inventory.inventory_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'replace.replace_date as replace_date',
                'replace.replace_status as replace_status', // Include replace status
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->leftJoin('replace', function($join) {
                $join->on('inventory.inventory_id', '=', 'replace.inventory_id')
                     ->where('replace.replace_status', '=', 'complete'); // Filter by complete status
            })
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'inventory.inventory_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'replace.replace_date',
                'product.added_date',
                'replace.replace_status', // Group by replace status
                'product.typeOfUnit'
            )
            ->orderBy('product.added_date', 'desc')
            ->get();
    
        // Fetch serial numbers for each product
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->orderBy('serial_number')
                ->pluck('serial_number')
                ->toArray();
        }
    
        // Sort products by added_date
        $products = $products->sortBy('added_date');
    
        return view('Inventory/Report', compact('products'));
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

    // for the buttons here di na hilabtan fak shit
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
    public function userArchive($id) {
        try {
            DB::table('users')
                ->where('user_id', $id)
                ->update(['archived' => 1]); 
    
            return response()->json(['success' => true, 'message' => 'User Archived successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to archive user: ' . $e->getMessage()]);
        }
    }
    public function supplierArchive($id) {
        try {
            DB::table('supplier')
                ->where('supplier_ID', $id)
                ->update(['status' => 'archived']); 
    
            return response()->json(['success' => true, 'message' => 'Supplier Archived successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to archive supplier: ' . $e->getMessage()]);
        }
    }
    public function inventoryArchive($id) {
        try {
            DB::table('inventory')
                ->where('inventory_id', $id)
                ->update(['status' => 'archive']); 
    
            return response()->json(['success' => true, 'message' => 'Supplier Archived successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to archive supplier: ' . $e->getMessage()]);
        }
    }
    public function updateProduct(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'category_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'typeOfUnit' => 'required|string|max:255',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|numeric',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $product = Product::findOrFail($request->product_id);
    
        // Update category
        if ($product->category) {
            $product->category->category_name = $request->category_name;
            $product->category->brand_name = $request->brand_name;
            $product->category->save();
        }
    
        // Update inventory
        if ($product->inventory) {
            $product->inventory->warranty_supplier = $request->warranty_supplier;
            $product->inventory->save();
        }
    
        // Update product attributes
        $product->product_name = $request->product_name;
        $product->typeOfUnit = $request->typeOfUnit;
        $product->unitPrice = $request->unitPrice;
        $product->added_date = $request->added_date;
        $product->supplier_ID = $request->supplierName;
    
        // Handle product image upload
        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::delete($product->product_image);
            }
            $imagePath = $request->file('product_image')->store('product_images', 'public');
            $product->product_image = $imagePath;
        }
    
        // Save product
        $product->save();
    
        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function editUser(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|integer|exists:users,user_id',
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'jobtype' => 'required|string|max:50',
            'user_contact' => 'required|string|regex:/^\d{10}$/',
            'password' => 'nullable|string|min:6', 
        ]);

        // Find the user and update
        $user = User::findOrFail($request->id);
        $user->fullname = $request->fullname;
        $user->username = $request->username;
        $user->job_title = $request->jobtype;
        $user->phone_number = $request->user_contact;

        // Update password if provided
        if ($request->password) {
            $user->password = bcrypt($request->password); // Encrypt the new password
        }

        $user->save(); // Save changes

        return redirect()->back()->with('success', 'User updated successfully!');
    }
    public function editSupplier(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:supplier,supplier_ID',
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'required|email',
            'supplier_phone' => 'required|digits:10', 
            'supplier_address' => 'required|string',
        ]);

        $supplier = supplier::findOrFail($request->id); 
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->supplier_phone = $request->supplier_phone;
    
        $supplier->save(); // Save changes
    
        return redirect()->back()->with('success', 'Supplier updated successfully!');
    }


    // for the user
    public function staffPos() {
        $categories = category::all(); 
        
        $products = DB::table('product')
            ->select(
                'product.product_id',
                'category.category_id',
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
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
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
                    ->select('serial_number', 'created_at')
                    ->where('product_id', $product->product_id)
                    ->get();
            }
        return view('OfficeStaff/pos', compact('categories', 'products'));
    }
    public function staffDashboard() {
        $pageTitle = 'Dashboard';
    
        // pang pending
        $pendingProducts = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                DB::raw('COUNT(serial.serial_number) as serial_count')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->where('inventory.status', '=', 'pending')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.added_date'
            )
            ->get();
        // pang low stocks
        $approvedProducts = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.added_date as date_added',
                'product.typeOfUnit',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                DB::raw('COUNT(serial.serial_number) as serial_count')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'product.typeOfUnit',
                'supplier.supplier_name',
                'product.added_date'
            )->get();

            // pan gdefective
            $defectiveProducts = DB::table('product')
                ->select(
                    'product.product_id',
                    'serial.serial_number',
                    'product.product_image',
                    'category.category_name',
                    'category.brand_name',
                    'product.product_name as model_name',
                    'product.added_date as date_added',
                    'product.typeOfUnit',
                    DB::raw('MAX(inventory.warranty_supplier) as warranty_expired')
                )
                ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
                ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
                ->join('category', 'product.category_Id', '=', 'category.category_id')
                ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
                ->join('replace', 'inventory.inventory_id', '=', 'replace.inventory_id') 
                ->where('replace.replace_status', '=', 'inprogress') 
                ->whereNotNull('serial.serial_number') 
                ->whereNotNull('replace.replace_id') 
                ->groupBy(
                    'product.product_id',
                    'serial.serial_number',
                    'product.product_image',
                    'category.category_name',
                    'category.brand_name',
                    'product.product_name',
                    'product.added_date',
                    'product.typeOfUnit',
                    'supplier.supplier_name'
                )->get();
        $products = $pendingProducts->merge($approvedProducts);
    
        return $this->dashboardView('OfficeStaff/staffdashboard', compact('pageTitle', 'pendingProducts','approvedProducts','defectiveProducts'));
    }
    public function staffPending() {
         $products = DB::table('product')
            ->select(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name as model_name',
                'product.product_description',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count'),
                DB::raw('GROUP_CONCAT(serial.serial_number) as serial_numbers'),
                'inventory.status'
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id') // Use LEFT JOIN to count serials
            ->where('inventory.status', '<>', 'pending')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'product.product_description',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit',
                'inventory.status'
            )
            ->get();
        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->select('serial_number', 'created_at')
                ->where('product_id', $product->product_id)
                ->get()
                ->map(function ($serial) {
                    return [
                        'serial_number' => $serial->serial_number,
                        'created_at' => $serial->created_at,
                    ];
                })
                ->toArray();
        }
    
        $suppliers = supplier::all();
        return view('OfficeStaff/staffpending', compact('products', 'suppliers'));
    }
    public function staffStorePending(Request $request){
        $request->validate([
            'category_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'typeOfUnit' => 'required|string|max:50',
            'product_description' => 'required|string|max:1000',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|integer',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
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
                'supplier_ID' => $request->supplierName,
                'category_Id' => $category->category_id,
                'product_name' => $request->product_name,
                'unitPrice' => $request->unitPrice,
                'added_date' => $request->added_date,
                'typeOfUnit' => $request->typeOfUnit,
                'product_description' => $request->product_description,
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

    //for both users
    public function storeOrder(Request $request) {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer.name' => 'required|string',
            'customer.address' => 'required|string',
            'paymentMethod' => 'required|string',
            'gcash.name' => 'nullable|string',
            'gcash.address' => 'nullable|string',
            'gcash.reference' => 'nullable|string',
            'cash.name' => 'nullable|string',
            'cash.address' => 'nullable|string',
            'cash.amount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|integer', // Ensure the product ID is an integer
            'items.*.quantity' => 'required|integer|min:1', // Quantity must be at least 1
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
    
        Log::info($validatedData);
    
        try {
            Log::info('Entering order storage process');
    
            // Step 1: Save the customer details
            $customer = Customer::create([
                'customer_name' => $validatedData['customer']['name'],
                'customer_address' => $validatedData['customer']['address'],
            ]);
            Log::info('Customer saved:', $customer->toArray());
    
            // Step 2: Prepare payment data
            $paymentData = [
                'paymentType' => $validatedData['paymentMethod'],
                'discount' => $validatedData['discount'],
                'amount_paid' => $validatedData['total'], 
            ];
            if ($validatedData['paymentMethod'] === 'gcash') {
                $paymentData['reference_num'] = $validatedData['gcash']['reference'];
            } elseif ($validatedData['paymentMethod'] === 'cash') {
                $paymentData['amount_paid'] = $validatedData['cash']['amount'];
            }
    
            $payment = PaymentMethod::create($paymentData);
    
            // Step 3: Save the order
            $order = Order::create([
                'customer_id' => $customer->customer_id,
                'payment_id' => $payment->payment_id,
                'order_date' => now(),
                'total_amount' => $validatedData['totaarray: l'],
                'qty_order' => array_sum(array_column($validatedData['items'], 'quantity')),
            ]);
    
            // Step 4: Save each order item in the inventory
            foreach ($validatedData['items'] as $item) {
                Inventory::create([
                    'product_id' => $item['name'], 
                    'date_arrived' => now(),
                    'warranty_supplier' => '1 year', 
                    'status' => 'sold', 
                    'order_id' => $order->order_id,
                    'quantity' => $item['quantity'], 
                ]);
            }
            return response()->json(['message' => 'Order stored successfully!', 'order' => $order], 201);
        } catch (\Exception $e) {
            Log::error('Failed to store order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store order: ' . $e->getMessage()], 500);
        }
    }
    
    public function requestRepair(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:repair,order_id', 
                'reason' => 'required|string|max:255'
            ]);
            $replaceReason = $request->input('reason');
            $currentDate = now(); 
            $newReplace = repair::create([
                'order_id' => $request->input('order_id'),
                'return_date' => $currentDate,
                'return_reason' => $replaceReason,
                'return_status' => 'complete',
            ]);

            if ($newReplace) {
                return response()->json(['success' => 'Replace request has been successfully submitted.'], 200);
            } else {
                return response()->json(['error' => 'Failed to submit replace request.'], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'An internal error occurred. Please try again later.'], 500);
        }
    }
    public function requestReplace(Request $request)
    {
        try {
            $request->validate([
                'inventory_id' => 'required|integer|exists:inventory,inventory_id', 
                'reason' => 'required|string|max:255'
            ]);
            
            $replaceReason = $request->input('reason');
            $currentDate = now(); 
            
            $newReplace = replace::create([
                'inventory_id' => $request->input('inventory_id'),
                'replace_date' => $currentDate,
                'replace_reason' => $replaceReason,
                'replace_status' => 'inprogress',
            ]);
    
            if ($newReplace) {
                DB::table('serial')
                    ->join('inventory', 'serial.product_id', '=', 'inventory.product_id')
                    ->where('inventory.inventory_id', $request->input('inventory_id'))
                    ->update(['serial.status' => 'inprogress']);
    
                return response()->json(['success' => 'Replace request has been successfully submitted.'], 200);
            } else {
                return response()->json(['error' => 'Failed to submit replace request.'], 500);
            }
    
        } catch (\Exception $e) {
            Log::error('Error submitting replace request: ' . $e->getMessage());
            return response()->json(['error' => 'An internal error occurred. Please try again later.'], 500);
        }
    }
    

    private function dashboardView($view, $data = []) {
        $products = DB::table('product')
        ->select(
            'product.product_id',
            'product.product_image',
            'category.category_name',
            'category.brand_name',
            'product.product_name as model_name',
            'product.added_date as date_added',
            'inventory.status',
            DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
            DB::raw('COUNT(serial.serial_number) as serial_count')
        )
        ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
        ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
        ->join('category', 'product.category_Id', '=', 'category.category_id')
        ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
        ->whereIn('inventory.status', ['pending', 'approve'])
        ->groupBy(
            'product.product_id',
            'product.product_image',
            'category.category_name',
            'category.brand_name',
            'product.product_name',
            'supplier.supplier_name',
            'product.added_date',
            'inventory.status'
        )->get();
    
        $lowStockProducts = [];
        foreach ($products as $product) {
            $serialCount = $product->serial_count;
    
            if ($serialCount < 5) {
                $lowStockProducts[] = [
                    'product_id' => $product->product_id,
                    'product_image' => $product->product_image,
                    'category_name' => $product->category_name,
                    'brand_name' => $product->brand_name,
                    'model_name' => $product->model_name,
                    'typeOfUnit' => $product->typeOfUnit ?? 'N/A',
                    'serial_count' => $serialCount,
                ];
            }
        }
    
        $lowStockItems = count($lowStockProducts);
    
        return view($view, array_merge(compact(
            'lowStockItems',  
            'lowStockProducts', 
        ), $data));
    }

}