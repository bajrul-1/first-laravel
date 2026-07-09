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


