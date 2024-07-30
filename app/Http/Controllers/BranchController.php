<?php

namespace App\Http\Controllers;

use App\Http\StatusCode;
use App\Services\BranchService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use JsonResponseTrait;

    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function store(Request $request)
    {
        try {
            $result = $this->branchService->createBranch($request->all());
            return $this->successResponse($result, null, StatusCode::CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'An error occurred while creating the branch and address',
                'BRANCH_CREATION_ERROR',
                [$e->getMessage()],
                StatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }

    public function get(Request $request)
    {
        try {
            $company_id = $request->query('company_id');
            $result = $this->branchService->getBranches($company_id);
            return $this->successResponse($result);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'An error occurred while fetching branches',
                'BRANCHES_FETCH_ERROR',
                [$e->getMessage()],
                StatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }

    public function show($branchId)
    {
        try {
            $result = $this->branchService->getBranch($branchId);
            return $this->successResponse($result);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Branch not found',
                'BRANCH_FETCH_ERROR',
                [$e->getMessage()],
                StatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }

    public function update(Request $request, $branchId)
    {
        try {
            $result = $this->branchService->updateBranch($branchId, $request->all());
            return $this->successResponse($result);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'An error occurred while updating the branch and address',
                'BRANCH_UPDATE_ERROR',
                [$e->getMessage()],
                StatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }
}
