<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Branch;
use App\Models\OperationalTime;
use Illuminate\Support\Facades\DB;

class BranchService
{
  public function createBranch($data)
  {
    DB::beginTransaction();
    try {
      $branch = Branch::create($data);

      $address = Address::create([
        'branch_id' => $branch->id,
        'street' => $data['street'],
        'city' => $data['city'],
        'state' => $data['state'],
        'zip' => $data['zip'],
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
      ]);

      $operation_time = OperationalTime::create([
        'branch_id' => $branch->id,
        'open_time' => $data['open_time'],
        'close_time' => $data['close_time'],
      ]);

      DB::commit();

      $branch['address'] = $address;
      $branch['operationalTimes'] = $operation_time;
      return  $branch;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function getBranches($company_id)
  {
    $branches = Branch::where('company_id', $company_id)
      ->where('is_headquarter', false)
      ->with('address')
      ->with('operationalTime')
      ->get();

    return $branches;
  }

  public function getBranch($branch_id)
  {
    $branch = Branch::where('id', $branch_id)
      ->where('is_headquarter', false)
      ->with('address')
      ->with('operationalTime')
      ->firstOrFail();

    return $branch;
  }

  public function updateBranch($branch_id, $data)
  {
    $branch = Branch::where('id', $branch_id)
      ->where('is_headquarter', false)
      ->firstOrFail();

    $address = Address::where('branch_id', $branch_id)
      ->firstOrFail();

    $operation_time = OperationalTime::where('branch_id', $branch_id)
      ->firstOrFail();

    DB::beginTransaction();
    try {
      $branch->update($data);
      $address->update($data);
      $operation_time->update($data);

      DB::commit();

      $branch['address'] = $address;
      $branch['operationalTime'] = $operation_time;
      return $branch;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
