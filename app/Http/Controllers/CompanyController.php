<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //database insert kora hoyacha 

class CompanyController extends Controller
{
    //compony add korar jonno page t dakhanor jonno
    public function create(){
        return view('companies.create');
    }

    //form ar data receved kora save korar jonno
    public function store(Request $request){
        //form thaka asa data validation (kono ghor faka rakha jaba na)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20'
        ]);

        //Query Builder dea direct 'companies' tabe a data insert kora
        DB::table('companies')->insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'status'=>'trial', //notun company default trial a thakba 
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        //data save hobaer por akti success message soho dashbord a back korba.
        return redirect('/')->with('success', $request->name . ' has been successfully registered!');
    }
}
