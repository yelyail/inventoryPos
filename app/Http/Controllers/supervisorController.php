<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\supplier;
use App\Models\category;
use App\Models\product;
use App\Models\User;
use App\Models\serial;
use App\Models\inventory;
use App\Models\orders;
use App\Models\customer;
use App\Models\paymentMethod;
use App\Models\repair;
use App\Models\replace;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class supervisorController extends Controller
{
    // adding/viewing of dashboard di na hilabtan fak shit
    public function dashboard() {
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
                ->join('replace', 'serial.serial_id', '=', 'replace.serial_ID') 
                ->where('serial.status', '=', 'inprogress') 
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
                    'product.typeOfUnit'
                )->get();
            
        return $this->dashboardView('Inventory/dashboard', compact('pendingProducts','approvedProducts','defectiveProducts'));
    }
    // adding/viewing of pos di na hilabtan fak shit
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
            )->get();
            foreach ($products as $product) {
                $product->serial_numbers = DB::table('serial')
                    ->select('serial_number', 'created_at')
                    ->where('product_id', $product->product_id)
                    ->where('status', 'available')
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

            // Check if the serial number already exists for this product
            $existingSerial = serial::where('serial_number', $request->serial_number)
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingSerial) {
                return response()->json(['error' => 'This serial number already exists for the selected product.'], 409);
            }

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
                'serial.serial_number',
                'category.brand_name',
                'product.product_name as model_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date as date_added',
                DB::raw('MAX(inventory.warranty_supplier) as warranty_expired'),
                'product.typeOfUnit as unit',
                DB::raw('COUNT(serial.serial_number) as serial_count'),
                'serial.serial_Id',
                DB::raw('(SELECT MAX(replace.replace_date)) as replace_date')
            )
            ->join('inventory', 'product.product_id', '=', 'inventory.product_id')
            ->join('supplier', 'product.supplier_id', '=', 'supplier.supplier_id')
            ->join('category', 'product.category_Id', '=', 'category.category_id')
            ->leftJoin('serial', 'product.product_id', '=', 'serial.product_id')
            ->leftJoin('replace', 'serial.serial_Id', '=', 'replace.serial_id')
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'inventory.inventory_id',
                'product.product_image',
                'serial.serial_number',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.unitPrice',
                'product.added_date',
                'product.typeOfUnit',
                'serial.serial_Id'
            )
            ->orderBy('product.added_date', 'desc')
            ->get();


        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->orderBy('serial_number')
                ->pluck('serial_number')
                ->toArray();
        }
    
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
        $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'category_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'typeOfUnit' => 'required|string|max:255',
            'unitPrice' => 'required|numeric',
            'added_date' => 'required|date',
            'warranty_supplier' => 'required|integer',
            'warrantyUnit' => 'required|string|in:days,weeks,months',
            'supplierName' => 'required|exists:supplier,supplier_ID',
            'product_image' => 'nullable|image|max:2048',
        ]);

        // Retrieve the product and associated category and inventory records
        $product = Product::find($request->product_id);
        $inventory = Inventory::where('product_id', $product->product_id)->first();
        $category = Category::find($product->category_Id);

        // Update category details
        if ($category) {
            $category->update([
                'category_name' => $request->category_name,
                'brand_name' => $request->brand_name,
            ]);
        }

        // Update product details
        $product->update([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'typeOfUnit' => $request->typeOfUnit,
            'unitPrice' => $request->unitPrice,
            'added_date' => $request->added_date,
            'supplier_ID' => $request->supplierName,
        ]);

        // Update or create inventory details
        if ($inventory) {
            $inventory->update([
                'warranty_supplier' => $request->warranty_supplier,
            ]);
        }

        // Handle file upload for product image
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('product_images', 'public');
            $product->update(['product_image' => $path]);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
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
                ->where('serial.status', '=', 'inprogress') 
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
        $request->validate([
            
        ]);
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
                'serial_id' => 'required|integer|exists:serial,serial_id', 
                'reason' => 'required|string|max:255'
            ]);
            
            $replaceReason = $request->input('reason');
            $currentDate = now(); 
            
            $newReplace = replace::create([
                'serial_id' => $request->input('serial_id'), 
                'replace_date' => $currentDate,
                'replace_reason' => $replaceReason,
            ]);
    
            if ($newReplace) {
                DB::table('serial')
                    ->where('serial_id', $request->input('serial_id')) 
                    ->update(['status' => 'inprogress']);
            
                return response()->json(['success' => 'Replace request has been successfully submitted.'], 200);
            }
            return response()->json(['error' => 'Failed to submit replace request.'], 500);
    
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