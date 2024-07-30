<?php

namespace App\Http\Controllers;

use App\Http\StatusCode;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
  use JsonResponseTrait;

  protected $companyService;

  public function __construct(CompanyService $companyService)
  {
    $this->companyService = $companyService;
  }

  public function store(Request $request)
  {
    try {
      $result = $this->companyService->createCompany($request->all());
      return $this->successResponse([
        'company' => $result['company'],
        'branch' => $result['branch'],
      ], null, StatusCode::CREATED);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while creating company and HQ branch.',
        'CREATION_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function show(Request $request)
  {
    $owner_id = $request->owner_id;
    $company_id = $request->company_id;

    if (!$owner_id && !$company_id) {
      return $this->errorResponse(
        'Owner ID or Company ID is required',
        'VALIDATION_ERROR',
        null,
        StatusCode::BAD_REQUEST
      );
    }

    try {
      $data = $this->companyService->getCompany($owner_id, $company_id);
      return $this->successResponse($data, null, StatusCode::SUCCESS);
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return $this->errorResponse(
        'Company not found',
        'NOT_FOUND',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update(Request $request)
  {
    try {
      $data = $this->companyService->updateCompany($request->all());
      return $this->successResponse($data, null, StatusCode::SUCCESS);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while updating company and HQ branch.',
        'CREATION_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
      );
    }
  }
}
