<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getAllCompany')) {
    function getAllCompany(){
        return DB::table('companies')->orderBy('id', 'desc')->get();
    }
}

if(!function_exists('getCompanyById')){
    function getCompanyById($id){
        return DB::table('companies')->where('id', $id)->first();
    }
}

if(!function_exists('updateCompanyById')){
    function updateCompanyById($id, $data){
        return DB::table('companies')->where('id', $id)->update($data);
    }
}

if(!function_exists('suspendCompanyById')){
    function suspendCompanyById($id){
        return DB::table('companies')->where('id', $id)->update([
            'status' => 'suspended',
            'updated_at' => now()
        ]);
    }
}

if(!function_exists('checkIsAdminCreated')){
    function checkIsAdminCreated($companyId){
        return DB::table('users')->where('company_id', $companyId)->where('role', 'company_admin')->first();
    }
}

if (!function_exists('redirectBasedOnCompanyAndRole')) {
    /**
     * 🌐 Global Company and Role-based Redirect Helper
     *
     * @param \App\Models\User|null $user
     * @return \Illuminate\Http\RedirectResponse
     */
    function redirectBasedOnCompanyAndRole($user = null){
        $user = $user ?? Auth::user();

        if (!$user) {
            return redirect('/login');
        }
        $companySlug = $user->company_slug;

        if ($user->role === 'owner') {
            return redirect()->to("/{$companySlug}/dashboard");
        } 
        if ($user->role === 'manager') {
            return redirect()->to("/{$companySlug}/manager");
        } 
        if ($user->role === 'employee') {
            return redirect()->to("/{$companySlug}/employee");
        }
        Auth::logout();
        return redirect('/login')->with('error', 'Unauthorized node access.');
    }
}


