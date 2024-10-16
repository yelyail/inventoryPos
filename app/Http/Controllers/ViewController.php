<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\sold_products;
use App\Models\supplier;
use App\Models\inventory;
use App\Models\technicians;
use App\Models\repair_products;


class ViewController extends Controller
{
    public function viewBrand(){
        $allbrand = Brand::all();
        $allCategory = Category::all();
        return view('Inventory/Storage',[
            'brands'=>$allbrand,
            'categories'=>$allCategory]);
    }

    public function viewProduct()
    {
        $allbrand=Brand::all();
        $allCategory = Category::all();
        // $allProduct=Product::all();
        $allProducts = Product::with(['brand', 'category'])
        ->where('status', 'Active')
        ->get();

        $inactiveProducts = Product::with(['brand', 'category'])
        ->where('status', 'Inactive')
        ->get();


        return view('Inventory/Product',['brands'=>$allbrand,
                                        'categories' => $allCategory,
                                        'products'=>$allProducts,
                                        'inactiveProducts' => $inactiveProducts]);
    }

   public function viewDashboard() 
{
    // Fetch low stock products
    $lowStockProducts = Product::leftJoin('inventory', 'products.Product_ID', '=', 'inventory.Product_ID')
        ->leftJoin('categories', 'products.Category_ID', '=', 'categories.Category_ID') // Join with categories
        ->leftJoin('brands', 'products.Brand_ID', '=', 'brands.Brand_ID') // Join with brands
        ->selectRaw('
            products.Image as image, 
            products.Product_ID as Product_ID, 
            categories.Category_Name as category_name,
            brands.Brand_Name as brand_name, 
            products.Product_Name as product_name,  
            products.Description as description,  /* Include the description field */
            COALESCE(SUM(CASE WHEN inventory.status NOT IN ("Defective", "Pending", "Returned", "Sold", "Ongoing Repair", "Repair Failed") THEN 1 ELSE 0 END), 0) as total_count
        ')
        ->where('products.status', 'Active') // Fetch only active products
        ->groupBy('products.Image', 'products.Product_ID', 'categories.Category_Name', 'brands.Brand_Name', 'products.Product_Name', 'products.Description') // Group by relevant fields including description
        ->havingRaw('total_count < 5') // Keep products with less than 5 in inventory
        ->orderBy('total_count', 'asc') // Order by total_count
        ->get();

    // Fetch damaged items separately
    $damagedItems = Inventory::with('product')->where('status', 'Defective')->get();

    // Fetch pending items that have not been checked
    $pendingItems = Inventory::with('product')
        ->selectRaw('
            inventory.*, 
            DATEDIFF(CURRENT_DATE, inventory.date_arrived) as days_since_arrived
        ')
        ->where('status', 'Pending')
        ->whereNull('date_checked') // Ensure the date_checked is null
        ->orderBy('days_since_arrived', 'desc') // Order by total_count
        ->get();

    return view('Inventory/dashboard', [
        'inventoryall' => $lowStockProducts,
        'damagedall' => $damagedItems,
        'pendingItems' => $pendingItems, // Pass pending items to the view
    ]);
}


public function staffDashboard() 
{
    // Fetch low stock products
    $lowStockProducts = Product::leftJoin('inventory', 'products.Product_ID', '=', 'inventory.Product_ID')
        ->leftJoin('categories', 'products.Category_ID', '=', 'categories.Category_ID') // Join with categories
        ->leftJoin('brands', 'products.Brand_ID', '=', 'brands.Brand_ID') // Join with brands
        ->selectRaw('
            products.Image as image, 
            products.Product_ID as Product_ID, 
            categories.Category_Name as category_name,
            brands.Brand_Name as brand_name, 
            products.Product_Name as product_name,  
            products.Description as description,  /* Include the description field */
            COALESCE(SUM(CASE WHEN inventory.status NOT IN ("Defective", "Pending", "Returned", "Sold", "Ongoing Repair", "Repair Failed") THEN 1 ELSE 0 END), 0) as total_count
        ')
        ->where('products.status', 'Active') // Fetch only active products
        ->groupBy('products.Image', 'products.Product_ID', 'categories.Category_Name', 'brands.Brand_Name', 'products.Product_Name', 'products.Description') // Group by relevant fields including description
        ->havingRaw('total_count < 5') // Keep products with less than 5 in inventory
        ->orderBy('total_count', 'asc') // Order by total_count
        ->get();

    // Fetch damaged items separately
    $damagedItems = Inventory::with('product')->where('status', 'Defective')->get();

    // Fetch pending items that have not been checked
    $pendingItems = Inventory::with('product')
        ->selectRaw('
            inventory.*, 
            DATEDIFF(CURRENT_DATE, inventory.date_arrived) as days_since_arrived
        ')
        ->where('status', 'Pending')
        ->whereNull('date_checked') // Ensure the date_checked is null
        ->orderBy('days_since_arrived', 'desc') // Order by total_count
        ->get();

    return view('OfficeStaff/staffdashboard', [
        'inventoryall' => $lowStockProducts,
        'damagedall' => $damagedItems,
        'pendingItems' => $pendingItems, // Pass pending items to the view
    ]);
}


public function productSelect() {
    $activeselect = Product::with(['brand', 'category'])
        ->where('status', 'Active')
        ->get();

    
    $pendingInventories = Inventory::where('status', 'Pending')
        ->with('product.category', 'product.brand')
        ->orderBy('date_arrived', 'asc')
        ->get();
    $allcategoryview = Category::all();
    $allsupplier = Supplier::all();    

    return view('Inventory/Pending', [
        'ProductSL' => $activeselect,
        'CategorySL' => $allcategoryview,
        'supSL' => $allsupplier,
        'pendingInventories' => $pendingInventories,
    ]);
}

public function staffproductSelect() {
    $activeselect = Product::with(['brand', 'category'])
        ->where('status', 'Active')
        ->get();

    
    $pendingInventories = Inventory::where('status', 'Pending')
        ->with('product.category', 'product.brand')
        ->orderBy('date_arrived', 'desc')
        ->get();
    $allcategoryview = Category::all();
    $allsupplier = Supplier::all();    

    return view('OfficeStaff/staffPending', [
        'ProductSL' => $activeselect,
        'CategorySL' => $allcategoryview,
        'supSL' => $allsupplier,
        'pendingInventories' => $pendingInventories,
    ]);
}


public function Inventoryview() { 
    $allcategoryview = Category::all();
    $alltechview = technicians::all();
    $productinv = Inventory::with(['product.category'])->get();
    $soldProducts = sold_products::with(['inventory.product'])->get();
    $returnee = Inventory::whereIn('status', ['Returned', 'Returned w/ charged', 'Returned Customer'])
    ->with(['product.category', 'product.brand'])
    ->get();
    $repairee = repair_products::where('status', 'Ongoing Repair')
    ->with(['inventory.product.category', 'inventory.product.brand', 'technician'])
    ->get();

    return view('Inventory/Invside', [
        'catinv' => $allcategoryview,
        'techv' => $alltechview,
        'productinv' => $productinv,
        'soldItems' => $soldProducts,
        'returneditems' => $returnee,
        'repairitems' => $repairee
    ]);
}

public function staffInventoryview() { 
    $allcategoryview = Category::all();
    $alltechview = technicians::all();
    $productinv = Inventory::with(['product.category'])->get();
    $soldProducts = sold_products::with(['inventory.product'])->get();
    $returnee = Inventory::whereIn('status', ['Returned', 'Returned w/ charged', 'Returned Customer'])
    ->with(['product.category', 'product.brand'])
    ->get();
    $repairee = repair_products::where('status', 'Ongoing Repair')
    ->with(['inventory.product.category', 'inventory.product.brand', 'technician'])
    ->get();

    return view('OfficeStaff/staffInvside', [
        'catinv' => $allcategoryview,
        'techv' => $alltechview,
        'productinv' => $productinv,
        'soldItems' => $soldProducts,
        'returneditems' => $returnee,
        'repairitems' => $repairee
    ]);
}

public function viewReport()
{
    $inventorySummary = [
        'incoming' => Inventory::where('date_approved')->count(),
        'outgoing' => Inventory::where('status', 'Sold')->count(),
        'repair' => Inventory::where('status', 'Repaired')->count(),
    ];

    $inventoryDetails = Inventory::with(['product.category', 'product.brand'])
        ->select(
            'products.Product_Name', 
            'categories.Category_Name', 
            'brands.Brand_Name', 
            'inventory.serial_number', 
            'inventory.status',  // Specify the table for status
            'inventory.date_arrived'
        )
        ->leftJoin('products', 'inventory.Product_ID', '=', 'products.Product_ID')
        ->leftJoin('categories', 'products.Category_ID', '=', 'categories.Category_ID')
        ->leftJoin('brands', 'products.Brand_ID', '=', 'brands.Brand_ID')
        ->get();

    return view('Inventory/Report', [
        'inventorySummary' => $inventorySummary,
        'inventoryDetails' => $inventoryDetails,
    ]);
}


 public function viewSuptech(){
        $allsup = supplier::all();
        $alltech = technicians::all();
        return view('Inventory/Suptech',[
            'suppliers'=>$allsup,
            'technicians'=>$alltech]);
    }


}