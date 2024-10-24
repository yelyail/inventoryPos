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
    
        // Fetch products with serial counts for pending status
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
    
        // Fetch products with serial counts for approved status
        $approvedProducts = DB::table('product')
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
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.added_date'
            )->get();
        $products = $pendingProducts->merge($approvedProducts);
    
        return $this->dashboardView('Inventory/dashboard', compact('pageTitle', 'pendingProducts','approvedProducts'));
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
            ->orderBy('product.added_date', 'desc')  // Order by latest added date
            ->get();

        foreach ($products as $product) {
            $product->serial_numbers = DB::table('serial')
                ->where('product_id', $product->product_id)
                ->orderBy('serial_number') 
                ->pluck('serial_number') 
                ->toArray();
        }
    
        // Sort products by model_name
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
    
        // Fetch products with serial counts for approved status
        $approvedProducts = DB::table('product')
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
            ->where('inventory.status', '=', 'approve')
            ->groupBy(
                'product.product_id',
                'product.product_image',
                'category.category_name',
                'category.brand_name',
                'product.product_name',
                'supplier.supplier_name',
                'product.added_date'
            )->get();
        $products = $pendingProducts->merge($approvedProducts);
    
        return $this->dashboardView('OfficeStaff/staffdashboard', compact('pageTitle', 'pendingProducts','approvedProducts'));
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


    //for both users
    public function storeOrder(Request $request) {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'paymentMethod' => 'required|string',
            'gcash.name' => 'nullable|string',
            'gcash.address' => 'nullable|string',
            'gcash.reference' => 'nullable|string',
            'cash.name' => 'nullable|string',
            'cash.address' => 'nullable|string',
            'cash.amount' => 'nullable|numeric',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
    
        $order = new order(); // Assuming you have an Order model
        $order->payment_method = $validatedData['paymentMethod'];
        $order->subtotal = $validatedData['subtotal'];
        $order->discount = $validatedData['discount'];
        $order->total = $validatedData['total'];
    
        if ($validatedData['paymentMethod'] === 'GCash') {
            $order->gcash_name = $validatedData['gcash']['name'];
            $order->gcash_address = $validatedData['gcash']['address'];
            $order->gcash_reference = $validatedData['gcash']['reference'];
        } else {
            $order->cash_name = $validatedData['cash']['name'];
            $order->cash_address = $validatedData['cash']['address'];
            $order->cash_amount = $validatedData['cash']['amount'];
        }
    
        // Save the order
        $order->save();
    
        foreach ($validatedData['items'] as $item) {
            $orderItem = new order(); 
            $orderItem->order_id = $order->id; // Foreign key reference to the order
            $orderItem->name = $item['name'];
            $orderItem->price = $item['price'];
            $orderItem->save();
        }
    
        // Return a response (success or redirect)
        return response()->json(['message' => 'Order stored successfully!', 'order' => $order], 201);
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