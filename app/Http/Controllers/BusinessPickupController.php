<?php

namespace App\Http\Controllers;

use App\Http\StatusCode;
use App\Services\BusinessPickupService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class BusinessPickupController extends Controller
{
  use JsonResponseTrait;

  protected $businessPickupService;

  public function __construct(BusinessPickupService $businessPickupService)
  {
    $this->businessPickupService = $businessPickupService;
  }

  public function store(Request $request)
  {
    try {
      $pickup = $this->businessPickupService->createPickup($request->all());
      return $this->successResponse($pickup, 'Pickup created successfully', StatusCode::CREATED);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while creating the pickup',
        'BUSINESS_PICKUP_CREATION_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function get(Request $request)
  {
    try {
      $type = $request->input('type');
      $id = $request->input('id');
      $pickups = $this->businessPickupService->getPickups($type, $id);

      return $this->successResponse($pickups, null);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching pickups',
        'BUSINESS_PICKUPS_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function show($pickupId)
  {
    try {
      $pickup = $this->businessPickupService->getPickup($pickupId);
      return $this->successResponse($pickup, null);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching the pickup',
        'BUSINESS_PICKUP_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update(Request $request, $pickupId)
  {
    try {
      $pickup = $this->businessPickupService->updatePickup($pickupId, $request->all());
      return $this->successResponse($pickup, 'Pickup updated successfully');
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while updating the pickup',
        'BUSINESS_PICKUP_UPDATE_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getBookedSlots($branchId)
  {
    try {
      $bookedSlots = $this->businessPickupService->getBookedSlots($branchId);
      return $this->successResponse($bookedSlots);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching booked slots',
        'BUSINESS_PICKUP_BOOKED_SLOTS_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
