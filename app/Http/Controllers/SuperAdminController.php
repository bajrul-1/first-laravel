<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function dashboard(){
        //Get company count
        $count = DB::table('companies')->selectRaw('
        COUNT(*) as total,
        COUNT(CASE WHEN status = "active" THEN 1 END) as active,
        COUNT(CASE WHEN status = "trial" THEN 1 END) as trial
    ')->first();

    //View dashboard and send data
    return view('super_admin.dashboard', [
        'totalCompanies'  => $count->total,
        'activeCompanies' => $count->active,
        'trialCompanies'  => $count->trial,
        'companies'       => getAllCompany()
    ]);
    }

    public function companyIndex(){
        $companies = getAllCompany();
        return view('super_admin.companies', compact('companies'));
    }

    public function manageCompany($id){
        $company = getCompanyById($id);

        if($company){
            $adminUser = checkIsAdminCreated($company->id);
            return view('super_admin.manage_company', compact('company', 'adminUser'));
        }else{
            abort(404, 'Company registry not found.');
        }
    }

    public function generateAccess($id){
        $company = getCompanyById($id);

        if($company){
            $adminEmail    = $company->email ?? 'admin' . $company->id . '@bakeryerp.com';
            $plainPassword = Str::random(8);

            DB::table('users')->insert([
                'company_id'          => $company->id,
                'name'                => $company->name . ' Admin',
                'email'               => $adminEmail,
                'password'            => Hash::make($plainPassword),
                'temporary_password'  => $plainPassword,
                'is_password_changed' => false,
                'role'                => 'company_admin',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            return redirect('/company/manage/'.$id)->with('success', 'Temporary access token generated and logged into secure buffer!');
        }else{
            return redirect()->back()->with('error', 'Company registry not found.');
        }
    }

    public function editCompany($id){
        $company = getCompanyById($id);

        if($company){
            return view('super_admin.edit_company', compact('company'));
        }else{
            abort(404);
        }
    }

    public function updateCompany(Request $request, $id){
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        updateCompanyById($id, [
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'updated_at' => now()
        ]);
        return redirect('companies')->with('success', 'Company enterprise specifications updated successfully.!');
    }

    public function suspendCompany($id){
        suspendCompanyById($id);

        return redirect('/companies')->with('success', 'Tenant status has been forcefully set to Suspended. Gateway routing blocked.');
    }

}
