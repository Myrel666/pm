<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Logout 
     * 
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->intended('login');
    }
}
