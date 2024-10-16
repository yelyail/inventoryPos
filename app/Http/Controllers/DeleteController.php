<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\product_unactive;
use App\Models\rejected_products;
use App\Models\pending_products;
use App\Models\inventory;
use App\Models\sold_products;
use Illuminate\Support\Facades\File;




class DeleteController extends Controller
{
   
    public function deleteProduct($id)
{
    $product = Product::find($id);
    
    if ($product) {
        $product->status = 'Inactive'; 
        $product->save();
        
    }
    

    return redirect()->route('Product')->with('success','Product '. $product->product_name . ' phased out successfully!');
}

public function reactivateProduct($id)
{
    $product = Product::find($id);

    if ($product) {
        $product->status = 'Active';
        $product->save();

        // Use $product to get the product name
        return redirect()->route('Product')->with('success', 'Product ' . $product->product_name . ' reactivated successfully!');
    }

    // Optionally handle the case where the product is not found
    return redirect()->route('Product')->with('error', 'Product not found.');
}



   public function ApproveProduct($id)
{
    $productinv = inventory::find($id);

    if ($productinv) {
        $productinv->status = 'New';
        $productinv->date_approved = now();
        $productinv->save();
    
        return redirect()->route('Pending')->with('success', 'Serial ' . $productinv->serial_number . ' approved successfully!');
    }
    return redirect()->route('Product')->with('error', 'Product not found.');
    
}

}
