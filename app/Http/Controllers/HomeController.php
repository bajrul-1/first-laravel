<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected function redirectBasedOnRole($role){
        switch($role){
            case 'super_admin':
                return redirect('/super_admin/dashboard');
            case 'company_admin':
                return redirect('/company_dashboard');
            case 'employee':
                return redirect('/employee_dashboard');
            default:
                Auth::logout();
                return redirect('/')->with('error', 'Unauthorized access operational node.');
        }
    }

    public function index(){
        if(Auth::check()){
            $user = Auth::user();
            return $this->redirectBasedOnRole($user->role);
        }else{
            return view('welcome');
        }
    }

    public function loginPage(){
        if(Auth::check()){
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }
    
}