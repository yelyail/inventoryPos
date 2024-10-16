<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\ValidationException;



class AuthController extends Controller
{
    public  function login(){
        return view('Inventory/login');
    }

    public function loginAction(Request $request){
        Validator::make($request->all(),[
        'username' => 'required|username',
        'password' => 'required'
        ])->validate();

    }
}
