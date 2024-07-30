<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\BusinessPickup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessPickupService
{
  public function createPickup($data)
  {
    DB::beginTransaction();
    try {
      $requestor = Branch::with('address')
        ->findOrFail($data['requestor_branch_id']);

      $pickup = BusinessPickup::create([
        'requestor_branch_id' => $data['requestor_branch_id'],
        'collector_branch_id' => $data['collector_branch_id'],
        'waste_payload' => $data['waste_payload'],
        'requested_at' => now(),
        'request_pickup_time' => $data['request_pickup_time'],
        'contact_number' => $requestor['phone'],
        'street' => $requestor['address']['street'],
        'city' => $requestor['address']['city'],
        'state' => $requestor['address']['state'],
        'zip' => $requestor['address']['zip'],
        'latitude' => $requestor['address']['latitude'],
        'longitude' => $requestor['address']['longitude'],
      ]);

      DB::commit();

      return $pickup;
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
      throw $e;
    }
  }

  public function getPickups($type, $id)
  {
    if (!in_array($type, ['business', 'collector'])) {
      throw new \Exception('Invalid type for fetching business pickups');
    }

    $query = BusinessPickup::with([
      'requestorBranch',
      'collectorBranch',
    ]);

    $company_branches_id = Branch::where('company_id', $id)
      ->get()
      ->pluck('id');

    if ($type === 'business') {
      $query->whereIn('requestor_branch_id', $company_branches_id);
    }

    if ($type === 'collector') {
      $query->where('collector_branch_id', $company_branches_id);
    }

    return $query->get();
  }

  public function getPickup($pickupId)
  {
    return BusinessPickup::with([
      'requestorBranch',
      'requestorBranch.address',
      'collectorBranch',
      'collectorBranch.address',
      'collectorBranch.company',
      'collectorBranch.operationalTime',
    ])
      ->findOrFail($pickupId);
  }

  public function updatePickup($pickupId, $data)
  {
    DB::beginTransaction();
    try {
      $pickup = BusinessPickup::findOrFail($pickupId);

      if ($data['decision'] === 'accept') {
        $pickup->status = 'accepted';
        $pickup->accepted_rejected_at = now();
      } elseif ($data['decision'] === 'reject') {
        $pickup->status = 'rejected';
        $pickup->accepted_rejected_at = now();
      } elseif ($data['decision'] === 'complete') {
        $pickup->completed_at = now();
      }

      if (isset($data['remark'])) {
        $pickup->remark = $data['remark'];
      }
      $pickup->save();
      DB::commit();

      return $pickup;
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
      throw $e;
    }
  }

  public function getBookedSlots($branchId)
  {
    $pickupTimes = BusinessPickup::where('collector_branch_id', $branchId)
      ->whereNull('completed_at')
      ->where('status', 'accepted')
      ->where('request_pickup_time', '>', now())
      ->pluck('request_pickup_time');

    return $pickupTimes ?? [];
  }
}
