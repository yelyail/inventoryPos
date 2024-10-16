<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\inventory;
use App\Models\sold_products;
use App\Models\repair_products;
use App\Models\supplier;
use App\Models\technicians;





class AddingController extends Controller
{
    public function AddBrand(Request $request)
{
    $request->validate([
        'brandname' => 'required|string|max:255',
    ]);

    // Check if the brand already exists
    if (Brand::where('brand_name', $request->input('brandname'))->exists()) {
        return redirect()->route('Storage')->with('error', 'Brand already exists!');
    }else

    $Brander = new Brand();
    $Brander->brand_name = $request->input('brandname');
    $Brander->save();

    return redirect()->route('Storage')->with('success', 'Brand ' . $Brander->brand_name . ' added successfully!');
}

public function AddCategory(Request $request)
{
    $request->validate([
        'catname' => 'required|string|max:255',
    ]);

    // Check if the category already exists
    if (Category::where('category_name', $request->input('catname'))->exists()) {
        return redirect()->route('Storage')->with('error', 'Category already exists!');
    }else

    $Categorieser = new Category();
    $Categorieser->category_name = $request->input('catname');
    $Categorieser->save();

    return redirect()->route('Storage')->with('success', 'Category ' . $Categorieser->category_name . ' added successfully!');
}


    public function AddProduct(Request $request)
{
    $request->validate([
        'imgname' => 'required|mimes:jpg,png,jpeg,gif|max:5048',
        'catname' => 'required',
        'braname' => 'required',
        'prodname' => 'required'
    ]);

    // Check if the product already exists
    if (Product::where('product_name', $request->input('prodname'))->exists()) {
        return redirect()->route('Product')->with('error', 'Product already exists!');
    }else

    $newImgName = time() . '-' . $request->prodname . '.' . $request->imgname->extension();
    $request->imgname->move(public_path('product_images'), $newImgName);

    $Producter = Product::create([
        'Image' => $newImgName,
        'Category_ID' => $request->input('catname'),
        'Brand_ID' => $request->input('braname'),
        'product_name' => $request->input('prodname'),
        'description' => $request->input('descp')
    ]);

    return redirect()->route('Product')->with('success', 'Product ' . $Producter->product_name . ' added successfully!');
}

public function addpending(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'product_id' => 'required|exists:products,Product_ID',
        'serial_number' => 'required|string|max:255',
        'Supplier_ID' => 'required|exists:supplier,Supplier_ID',
        'datearrived' => 'required|date',
        'dateinspected' => 'nullable|date',
        'imgname' => 'nullable|image|max:2048', // Validate as an image and set a max file size
    ]);

   // Check if the product already exists
    if (inventory::where('serial_number', $request->input('serial_number'))->exists()) {
        return redirect()->route('Pending')->with('error', 'Product serial number already exists!');
    }else

    $newImgName = time() . '-' . $request->serial_number . '.' . $request->reportnameimage->extension();
    $request->reportnameimage->move(public_path('product_reportdev_images'), $newImgName);

    // Create a new inventory item
    $Producterpen = inventory::create([
        'Product_ID' => $request->input('product_id'),
        'serial_number' => $request->input('serial_number'),
        // 'status' => 'Pending',
        'Supplier_ID' => $request->input('Supplier_ID'),
        'date_arrived' => $request->input('datearrived'),
        'date_checked' => $request->input('dateinspected'),
        'deliveryR_photo' => $newImgName, // Save the path if a file was uploaded
    ]);

    return redirect()->route('Pending')->with('success', 'Serial ' . $Producterpen->serial_number . ' added to pending successfully!');
}

public function addpendingstaff(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'product_id' => 'required|exists:products,Product_ID',
        'serial_number' => 'required|string|max:255',
        'Supplier_ID' => 'required|exists:supplier,Supplier_ID',
        'datearrived' => 'required|date',
        'dateinspected' => 'nullable|date',
        'imgname' => 'nullable|image|max:2048', // Validate as an image and set a max file size
    ]);

   // Check if the product already exists
    if (inventory::where('serial_number', $request->input('serial_number'))->exists()) {
        return redirect()->route('Pending')->with('error', 'Product serial number already exists!');
    }else

    $newImgName = time() . '-' . $request->serial_number . '.' . $request->reportnameimage->extension();
    $request->reportnameimage->move(public_path('product_reportdev_images'), $newImgName);

    // Create a new inventory item
    $Producterpen = inventory::create([
        'Product_ID' => $request->input('product_id'),
        'serial_number' => $request->input('serial_number'),
        // 'status' => 'Pending',
        'Supplier_ID' => $request->input('Supplier_ID'),
        'date_arrived' => $request->input('datearrived'),
        'date_checked' => $request->input('dateinspected'),
        'deliveryR_photo' => $newImgName, // Save the path if a file was uploaded
    ]);

    return redirect()->route('staffPending')->with('success', 'Serial ' . $Producterpen->serial_number . ' added to pending successfully!');
}

public function addsold(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'InvoiceNum' => 'required|string|max:255',
        'sale_date' => 'required|date',
        'Inventory_ID' => 'required|integer|exists:inventory,Inventory_ID' // Corrected table name
    ]);

    // Find the inventory item
    $inventory = Inventory::with('product')->findOrFail($validated['Inventory_ID']); // Ensure to load the product relationship

    // Check if an invoice with the same number already exists
    $existingSale = sold_products::where('Invoice', $validated['InvoiceNum'])->first();

    if ($existingSale) {
        // Check if the status of the existing sale is 'Returned'
        if ($existingSale->status === 'Returned') {
            // Overwrite the status to 'Repaired'
            $existingSale->status = 'Repaired';
            $existingSale->save();

            // Update the inventory status if needed
            $inventory->status = 'Sold';
            $inventory->save();

            return redirect()->route('Invside')->with('success', "Invoice {$validated['InvoiceNum']} has been updated to Repaired.");
        } else {
            // If the status is not 'Returned', return an error
            return redirect()->route('Invside')->with('error', "Invoice {$validated['InvoiceNum']} already exists It's not eligible for sale.");
        }
    }

    // If the invoice doesn't exist, create a new sale record
    $sale = new sold_products();
    $sale->Inventory_ID = $validated['Inventory_ID'];
    $sale->Invoice = $validated['InvoiceNum'];
    $sale->date_sold = $validated['sale_date'];
    
    // Save the new sale record
    $sale->save();

    // Update the inventory item status to 'Sold'
    $inventory->status = 'Sold';
    $inventory->save();

    // Redirect back with a success message
    return redirect()->route('Invside')->with('success', "Serial {$inventory->serial_number} has been sold successfully!");
}


public function addrepair(Request $request)
{
    // Validate the request data directly
    // $request->validate([
    //     'Inventory_ID' => 'required|exists:inventories,id',
    //     'Technician_ID' => 'required|exists:technicians,id',
    // ]);

    // Find the inventory item
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Create a new repair record
    $repair = new repair_products();
    $repair->Inventory_ID = $request->Inventory_ID;
    $repair->Technician_ID = $request->input('techname');
    // Save the repair record
    $repair->save();

    // Update inventory status
    $inventory->status = 'Ongoing Repair';
    $inventory->save();

    // Redirect back with a success message
    return redirect()->route('Invside')->with('success', "Serial {$inventory->serial_number} has been sent to repair successfully!");
}

public function AddSupplier(Request $request)
{
    $request->validate([
        'suppliername' => 'required|string|max:255',
    ]);

    // Check if the supplier already exists
    if (supplier::where('Company_Name', $request->input('suppliername'))->exists()) {
        return redirect()->route('Suptech')->with('error', 'Supplier already exists!');
    } else {
        $Supplierer = new supplier();
        $Supplierer->Company_Name = $request->input('suppliername');
        $Supplierer->Contact = $request->input('suppliercon');
        $Supplierer->save();

        return redirect()->route('Suptech')->with('success', 'Supplier ' . $Supplierer->Company_Name . ' added successfully!');
    }
}

public function AddTechnician(Request $request)
{
    $request->validate([
        'techname' => 'required|string|max:255', // Use 'techname' as in the form
        'techpos' => 'required|string|max:255',
    ]);

    // Check if the technician already exists
    if (technicians::where('Name', $request->input('techname'))->exists()) {
        return redirect()->route('Suptech')->with('error', 'Technician already exists!');
    } else {
        $Technicianer = new technicians();
        $Technicianer->Name = $request->input('techname');  // Use 'techname' from form input
        $Technicianer->Position_Level = $request->input('techpos');  // Use 'techpos' from form input
        $Technicianer->save();

        return redirect()->route('Suptech')->with('success', 'Technician ' . $Technicianer->Name . ' added successfully!');
    }
}

}
