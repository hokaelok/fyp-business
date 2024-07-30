<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\ConsumerPickup;

class ConsumerPickupService
{
  public function createPickup($data)
  {
    return ConsumerPickup::create([
      'requestor_id' => $data['user_id'],
      'branch_id' => $data['branch_id'],
      'waste_payload' => $data['waste_payload'],
      'requested_at' => now(),
      'request_pickup_time' => $data['request_pickup_time'],
      'contact_number' => $data['contact_number'],
      'street' => $data['street'],
      'city' => $data['city'],
      'state' => $data['state'],
      'zip' => $data['zip'],
      'latitude' => $data['latitude'],
      'longitude' => $data['longitude'],
    ]);
  }

  public function getPickups($type, $id)
  {
    if (!in_array($type, ['consumer', 'business'])) {
      throw new \Exception('Invalid type for fetching consumer pickups');
    }

    $branches = Branch::where('company_id', $id)->pluck('id');

    $query = ConsumerPickup::with([
      'branch',
      'branch.address',
      'branch.company',
      'branch.operationalTime'
    ]);
    if ($type === 'business') {
      $query->whereIn('branch_id', $branches);
    }
    if ($type === 'consumer') {
      $query->where('requestor_id', $id);
    }

    return $query->get();
  }

  public function getPickup($pickupId)
  {
    return ConsumerPickup::with([
      'branch',
      'branch.address',
      'branch.company',
      'branch.operationalTime'
    ])
      ->findOrFail($pickupId);
  }

  public function updatePickup($pickupId, $data)
  {
    $pickup = ConsumerPickup::findOrFail($pickupId);

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

    return $pickup;
  }

  public function getBookedSlots($branchId)
  {
    $pickupTimes = ConsumerPickup::where('branch_id', $branchId)
      ->whereNull('completed_at')
      ->where('status', 'accepted')
      ->where('request_pickup_time', '>', now())
      ->pluck('request_pickup_time');

    return $pickupTimes ?? [];
  }

  public function getPickupsHistory($data)
  {
    $data = collect($data);

    $data->transform(function ($transaction) {
      if ($transaction['consumer_pickup_id']) {
        $consumerPickup = ConsumerPickup::with('branch')->find($transaction['consumer_pickup_id']);

        if ($consumerPickup) {
          $transaction['consumerPickup'] = $consumerPickup;
        }
      }
      return $transaction;
    });

    return $data;
  }
}
