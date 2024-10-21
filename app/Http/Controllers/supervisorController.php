<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\supplier;
use App\Models\category;
use App\Models\product;
use App\Models\User;

class supervisorController extends Controller
{
    public function dashboard() {
        $pageTitle = 'Dashboard'; 
        return view('Inventory/dashboard', compact('pageTitle'));
    }
    
    public function pos() {
        return view('Inventory/pos');
    }
    
    public function inventory() {
        return view('Inventory/inventory');
    }
    
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
        return view('Inventory/pending');
    }
    
}
