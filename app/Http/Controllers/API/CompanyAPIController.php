<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCompanyAPIRequest;
use App\Http\Requests\API\UpdateCompanyAPIRequest;
use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class CompanyAPIController
 */
class CompanyAPIController extends AppBaseController
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the Companies.
     * GET|HEAD /companies
     */
    public function getCompany(Request $request): JsonResponse
    {
        $response = $this->companyRepository->getAllCompanies($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Display a listing of the Companies.
     * GET|HEAD /companies
     */
    public function getUserCompany(Request $request): JsonResponse
    {
        $response = $this->companyRepository->get_user_companies($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Store a newly created Company in storage.
     * POST /companies
     */
    public function store(CreateCompanyAPIRequest $request): JsonResponse
    {
        $response = $this->companyRepository->createCompany($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Display the specified Company.
     * GET|HEAD /companies/{id}
     */
    public function show($company_id): JsonResponse
    {
        $response = $this->companyRepository->getCompany($company_id);
        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Update the specified Company in storage.
     * PUT/PATCH /companies/{id}
     */
    public function update($company_id, UpdateCompanyAPIRequest $request): JsonResponse
    {
        $response = $this->companyRepository->updateCompany($request, $company_id);
        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Remove the specified Company from storage.
     * DELETE /companies/{id}
     *
     * @throws \Exception
     */
    public function destroy($company_id): JsonResponse
    {
        $response = $this->companyRepository->deleteCompany($company_id);
        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }
}
