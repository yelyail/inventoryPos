<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('Inventory/supplier');
    }
    public function user() {
        return view('Inventory/usermanagement');
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
