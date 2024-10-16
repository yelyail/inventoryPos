<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\inventory;
use App\Models\repair_products;
use App\Models\sold_products;
use Illuminate\Support\Facades\Storage;
use App\Models\supplier;
use App\Models\technicians;



class UpdateController extends Controller
{
  public function updateProduct(Request $request, $productId)
{
    // Validate the incoming request data
    $request->validate([
        'imgname' => 'nullable|mimes:jpg,png,jpeg|max:5048', // Image is optional for update
        'catname' => 'required',
        'braname' => 'required',
        'prodname' => 'required',
    ]);

    // Find the product by its ID
    $product = Product::findOrFail($productId);

    // Check if a new image is uploaded
    if ($request->hasFile('imgname')) {
        // Generate a new image name
        $newImgName = time() . '-' . $request->prodname . '.' . $request->imgname->extension();
        
        // Move the new image to the public directory
        $request->imgname->move(public_path('product_images'), $newImgName);

        // Optionally delete the old image if necessary
        if ($product->Image && file_exists(public_path('product_images/' . $product->Image))) {
            unlink(public_path('product_images/' . $product->Image));
        }

        // Update the product image
        $product->Image = $newImgName;
    }

    // Update other product details
    $product->Category_ID = $request->input('catname');
    $product->Brand_ID = $request->input('braname');
    $product->product_name = $request->input('prodname');
    $product->description = $request->input('descp');

    // Save the updated product
    $product->save();

    return redirect()->route('Product')->with('success','Product '. $product->product_name . ' updated successfully!');
}

    public function updateBrand(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'brandname' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,brand_id', // Ensure that brand_id exists in the database
    ]);

    // Find the brand by ID
    $brand = Brand::findOrFail($request->brand_id);

    // Update the brand name
    $brand->brand_name = $request->brandname;
    $brand->save();

    // Redirect back with a success message
    return redirect()->route('Storage')->with('success','Brand '. $brand->brand_name . ' updated successfully!');
}

    public function updateCategory(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'categoryname' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id' // Assuming your table has 'category_id' as primary key
        ]);

        // Find the category by ID
        $category = Category::findOrFail($request->category_id);

        // Update the category name
        $category->category_name = $request->categoryname;
        $category->save();

        // Redirect back with success message
        return redirect()->route('Storage')->with('success','Category '. $category->category_name . ' updated successfully!');

    }

    public function updatepending(Request $request, $Inventory_ID)
{
    // Validate the incoming request data
    $request->validate([
        'snumberid' => 'required',
        'snumber' => 'required|string|max:255', // Adjust max length as needed
        'rnameimage' => 'nullable|mimes:jpg,png,jpeg|max:5048', // Image is optional for update
        'darrived' => 'nullable|date',
        'dinspect' => 'nullable|date',
    ]);

    // Find the inventory item by its ID
    $inventory = inventory::findOrFail($Inventory_ID);

    // Check if the serial number already exists
    if (inventory::where('serial_number', $request->input('snumber'))->where('Inventory_ID', '<>', $Inventory_ID)->exists()) {
        return redirect()->route('Pending')->with('error', 'Product serial number already exists!');
    }

    // Check if a new report image is uploaded
    if ($request->hasFile('rnameimage')) {
        $newImgName = time() . '-' . $request->input('snumber') . '.' . $request->file('rnameimage')->extension();
        
        // Move to the public directory with correct path formatting
        $request->file('rnameimage')->move(public_path('product_reportdev_images'), $newImgName);
        
        // Optionally delete the old image
        if ($inventory->deliveryR_photo && file_exists(public_path('product_reportdev_images/' . $inventory->deliveryR_photo))) {
            unlink(public_path('product_reportdev_images/' . $inventory->deliveryR_photo));
        }

        $inventory->deliveryR_photo = $newImgName;
    }

    // Update other inventory details
    $inventory->Supplier_ID = $request->input('snumberid');
    $inventory->serial_number = $request->input('snumber');
    $inventory->date_arrived = $request->input('darrived');
    $inventory->date_checked = $request->input('dinspect');

    // Save the updated inventory
    $inventory->save();

    return redirect()->route('Pending')->with('success', 'Serial ' . $inventory->serial_number . ' updated successfully!');
}


public function DefectiveProduct(Request $request)
{
    $productinv = inventory::findOrFail($request->Inventory_ID);

    if ($productinv) {
        $productinv->status = 'Defective';
        $productinv->save();
    
        return redirect()->route('Invside')->with('success', 'Serial ' . $productinv->serial_number . ' set status to defective successfully!');
    }
    return redirect()->route('Invside')->with('error', 'Product not found.');
    
}

public function ReturnedProduct(Request $request)
{
    $productinv = inventory::findOrFail($request->Inventory_ID);

    if ($productinv) {
        $productinv->status = 'Returned';
        $productinv->date_returned = now();
        $productinv->save();
    
        return redirect()->route('Invside')->with('success', 'Serial ' . $productinv->serial_number . ' set status to returned successfully!');
    }
    return redirect()->route('Invside')->with('error', 'Product not found.');
    
}

public function RepairedSupplier(Request $request)
{
    $productinv = inventory::findOrFail($request->Inventory_ID);

    if ($productinv) {
        $productinv->status = 'Repaired';
        $productinv->save();
    
        return redirect()->route('Invside')->with('success', 'Serial ' . $productinv->serial_number . ' set status to repaired successfully!');
    }
    return redirect()->route('Invside')->with('error', 'Product not found.');
    
}

public function RepairSuccess(Request $request)
{
    // Find the inventory item with the related product
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Find the associated repair product using the repair ID from the request
    $prodrepair = repair_products::findOrFail($request->Repair_ID);

    // Set the status to 'Repair Success'
    $prodrepair->status = 'Repair Success'; 
    $prodrepair->save();

    // Update the inventory status to 'Repaired'
    $inventory->status = 'Repaired';
    $inventory->save();

    // Redirect with a success message
    return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' set status to repaired successfully!');
}

public function RepairFailed(Request $request)
{
    // Find the inventory item with the related product
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Find the associated repair product using the repair ID from the request
    $prodrepair = repair_products::findOrFail($request->Repair_ID);

    $prodrepair->status = 'Repair Failed'; 
    $prodrepair->save();

    // Update the inventory status to 'Repaired'
    $inventory->status = 'Repair Failed';
    $inventory->save();

    // Redirect with a success message
    return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' set status to repair failed!');
}

public function ReturnedProductCharged(Request $request)
{
    $productinv = inventory::findOrFail($request->Inventory_ID);

    if ($productinv) {
        $productinv->status = 'Returned w/ charged';
        $productinv->date_returned = now();
        $productinv->save();
    
        return redirect()->route('Invside')->with('success', 'Serial ' . $productinv->serial_number . ' set status to returned successfully!');
    }
    return redirect()->route('Invside')->with('error', 'Product not found.');
    
}

public function SoldCancel(Request $request)
{
    // Find the inventory item with the related product
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Find the associated repair product using the repair ID from the request
    $prodrepair = sold_products::findOrFail($request->Sold_ID);

    $prodrepair->status = 'Canceled'; 
    $prodrepair->save();

    // Update the inventory status to 'Repaired'
    $inventory->status = 'New';
    $inventory->save();

    // Redirect with a success message
    return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' has been cancelled!');
}

public function DefectiveSold(Request $request)
{
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    $productinv = sold_products::findOrFail($request->Sold_ID);

    if ($productinv) {
        $productinv->status = 'Defective';
        $productinv->save();
    
        return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' set status to defective successfully!');
    }
    return redirect()->route('Invside')->with('error', 'Product not found.');
    
}
public function SoldReturn(Request $request)
{
    // Find the inventory item with the related product
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Find the associated repair product using the repair ID from the request
    $prodrepair = sold_products::findOrFail($request->Sold_ID);

    $prodrepair->status = 'Returned Supplier'; 
    $prodrepair->save();

    // Update the inventory status to 'Repaired'
    $inventory->status = 'Returned Customer';
    $inventory->save();

    // Redirect with a success message
    return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' has been returned!');
}

public function SoldRepair(Request $request)
{
    // Find the inventory item with the related product
    $inventory = Inventory::with('product')->findOrFail($request->Inventory_ID);

    // Find the associated repair product using the repair ID from the request
    $prodrepair = sold_products::findOrFail($request->Sold_ID);

    $prodrepair->status = 'Returned Repair'; 
    $prodrepair->save();

    // Update the inventory status to 'Repaired'
    $inventory->status = 'Returned Defective';
    $inventory->save();

    // Redirect with a success message
    return redirect()->route('Invside')->with('success', 'Serial ' . $inventory->serial_number . ' has been returned!');
}


public function UpdateSupplier(Request $request)
{
    $request->validate([
        'supplier_idup' => 'required|exists:supplier,Supplier_ID', // Ensure the supplier exists
        'suppliernameup' => 'required|string|max:255',
        'supplierconup' => 'required|string|max:255',
    ]);

    // Find the supplier by ID
    $supplier = supplier::find($request->input('supplier_idup'));

    if ($supplier) {
        // Check if the updated supplier name already exists (excluding the current supplier)
        if (supplier::where('Company_Name', $request->input('suppliernameup'))
            ->where('Supplier_ID', '!=', $request->input('supplier_idup'))
            ->exists()) {
            return redirect()->route('Suptech')->with('error', 'Supplier with this name already exists!');
        }

        // Update supplier details
        $supplier->Company_Name = $request->input('suppliernameup');
        $supplier->Contact = $request->input('supplierconup');
        $supplier->save();

        return redirect()->route('Suptech')->with('success', 'Supplier ' . $supplier->Company_Name . ' updated successfully!');
    }

    return redirect()->route('Suptech')->with('error', 'Supplier not found!');
}


public function UpdateTechnician(Request $request)
{
    $request->validate([
        'technician_idup' => 'required|exists:technician,Technician_ID', // Ensure the technician exists
        'techniciannameup' => 'required|string|max:255',
        'position_levelup' => 'required|string|max:255',
    ]);

    // Find the technician by ID
    $technician = technicians::find($request->input('technician_idup'));

    if ($technician) {
        // Check if the updated technician name already exists (excluding the current technician)
        if (technicians::where('Name', $request->input('techniciannameup'))
            ->where('Technician_ID', '!=', $request->input('technician_idup'))
            ->exists()) {
            return redirect()->route('Suptech')->with('error', 'Technician with this name already exists!');
        }

        // Update technician details
        $technician->Name = $request->input('techniciannameup');
        $technician->Position_Level = $request->input('position_levelup');
        $technician->save();

        return redirect()->route('Suptech')->with('success', 'Technician ' . $technician->Name . ' updated successfully!');
    }

    return redirect()->route('Suptech')->with('error', 'Technician not found!');
}


}
