<?php

namespace App\Http\Controllers;

use App\Http\StatusCode;
use App\Services\ConsumerPickupService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class ConsumerPickupController extends Controller
{
  use JsonResponseTrait;

  protected $consumerPickupService;

  public function __construct(ConsumerPickupService $consumerPickupService)
  {
    $this->consumerPickupService = $consumerPickupService;
  }

  public function store(Request $request)
  {
    try {
      $pickup = $this->consumerPickupService->createPickup($request->all());
      return $this->successResponse($pickup, 'Pickup created successfully', StatusCode::CREATED);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while creating the pickup',
        'CONSUMER_PICKUP_CREATION_ERROR',
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
      $pickups = $this->consumerPickupService->getPickups($type, $id);

      return $this->successResponse($pickups, null);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching pickups',
        'CONSUMER_PICKUPS_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function show($pickupId)
  {
    try {
      $pickup = $this->consumerPickupService->getPickup($pickupId);
      return $this->successResponse($pickup, null);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching the pickup',
        'CONSUMER_PICKUP_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update(Request $request, $pickupId)
  {
    try {
      $pickup = $this->consumerPickupService->updatePickup($pickupId, $request->all());
      return $this->successResponse($pickup, 'Pickup updated successfully');
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while updating the pickup',
        'CONSUMER_PICKUP_UPDATE_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getBookedSlots($branchId)
  {
    try {
      $bookedSlots = $this->consumerPickupService->getBookedSlots($branchId);
      return $this->successResponse($bookedSlots);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching booked slots',
        'CONSUMER_PICKUP_BOOKED_SLOTS_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function history(Request $request)
  {
    try {
      $pickups = $this->consumerPickupService->getPickupsHistory($request->all());
      return $this->successResponse($pickups);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching pickup history',
        'CONSUMER_PICKUP_HISTORY_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
