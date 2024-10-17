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
        $pageTitle = 'Point of Sale'; 
        return view('Inventory/pos', compact('pageTitle'));
    }
    
    public function inventory() {
        $pageTitle = 'Inventory'; 
        return view('Inventory/inventory', compact('pageTitle'));
    }
    
    public function report() {
        $pageTitle = 'Reports';
        return view('Inventory/Report', compact('pageTitle'));
    }
    public function pending() {
        return view('Inventory/pending');
    }
    
}
