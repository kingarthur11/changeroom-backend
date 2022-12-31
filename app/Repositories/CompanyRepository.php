<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends BaseRepository
{
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Company::class;
    }

    public function createCompany($request)
    {
        $user_auth = Auth::user();

        if ($user_auth->company_size == 3) {
            return ['status' => false, 'response_message' => 'Maximum number of company to create reached', 'data' => 'Maximum number of company to create reached'];
        }

        try {
            DB::beginTransaction();
            $data = $this->create_company($request, $user_auth->id);
            User::where('id', $user_auth->id)->update([ 'company_size' => DB::raw('company_size + ' . 1 . ''), ]);
            DB::commit();
            return ['status' => true, 'response_message' => 'Company data created successfully', 'data' => $data];
        } catch (\Exception $e) {
            DB::rollBack();
            logger('error' . $e->getMessage() . ' =>>>>' . $e->getTraceAsString());
            return ['status' => false, 'response_message' => 'User registration process was not successful', 'data' => 'User registration process was not successful'];
        }
    }

    /**
     * @Route("Route", name="RouteName")
     */
    public function create_company($request, $user_auth_id)
    {
        $company = $this->create([
            'company_name' =>  $request->company_name,
            'company_email' =>  $request->company_email,
            'service_id' =>  $request->service_id,
            'country_id' =>  $request->country_id,
            'user_id' =>  $user_auth_id,
        ]);
        
        return $company;
    }

    public function updateCompany($request)
    {
        $user_auth = Auth::user();

        $company = new Company();

        $company = $company->where('id', $request->company_id)->where('user_id', $user_auth->id)->first();
        if (empty($company)) {
            return ['status' => false, 'response_message' => 'Company not found', 'data' => 'Company not found'];
        }

        $company = Company::where('id', $request->company_id)->update([
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'country_id' => $request->country_id,
            'service_id' => $request->service_id,
          ]);

        if(!$company) {
            return ['status' => false, 'response_message' => 'Update was not successful', 'data' => 'Update was not successful'];
        }
        return ['status' => true, 'response_message' => 'Company data updated successfully', 'data' => $company];
        
    }

    public function getCompany($company_id) {

        $user_auth = Auth::user();

        $company = new Company();

        $company = $company->where('id', $company_id)->where('user_id', $user_auth->id)->with('service')->first();
        if (empty($company)) {
            return ['status' => false, 'response_message' => 'Company not found', 'data' => 'Company not found'];
        }
        return ['status' => true, 'response_message' => 'Company retrieved successfully', 'data' => $company];
    }

    public function getAllCompanies($request) {

        $user_auth = Auth::user();
        $company = new Company();
        $company = $company->where('user_id', $user_auth->id)->with('service')->orderBy('created_at', 'DESC');

        if ($request->has('paginate')) {
            $company = $company->paginate(10);
        } else {
            $company = $company->get();
        }
        return ['status' => true, 'response_message' => 'Companies retrieved successfully', 'data' => $company];
    }

    public function deleteCompany($company_id)
    {
        $user_auth = Auth::user();

        $company = new Company();
        $company = $company->where('id', $company_id)->where('user_id', $user_auth->id)->first();
        if (empty($company)) {
            return ['status' => false, 'response_message' => 'Company not found', 'data' => 'Company not found'];
        }

        $company->delete();

        return ['status' => true, 'response_message' => 'Company deleted successfully' ];
    }

}
