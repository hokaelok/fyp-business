<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Company;
use App\Models\OperationalTime;
use Illuminate\Support\Facades\DB;

class CompanyService
{
  public function createCompany($data)
  {
    DB::beginTransaction();
    try {
      $company = Company::create([
        'name' => $data['company_name'],
        'owner_id' => $data['owner_id'],
      ]);

      $branch = Branch::create([
        'company_id' => $company->id,
        'name' => $company->name,
        'branch_type' => $data['branch_type'],
        'is_headquarter' => true,
      ]);

      DB::commit();

      return ['company' => $company, 'branch' => $branch];
    } catch (\Exception $e) {
      DB::rollback();
      return $e->getMessage();
    }
  }

  public function updateCompany($data)
  {
    DB::beginTransaction();

    try {
      $company = Company::findOrFail($data['company_id']);
      $company->update($data);

      $branch = Branch::where('company_id', $company->id)
        ->where('is_headquarter', true)
        ->firstOrFail();
      $branch->update($data);

      $address = Address::updateOrCreate(
        ['branch_id' => $branch->id],
        [
          'street' => $data['street'],
          'city' => $data['city'],
          'state' => $data['state'],
          'zip' => $data['zip'],
          'latitude' => $data['latitude'],
          'longitude' => $data['longitude'],
        ]
      );

      $operationalTime = OperationalTime::updateOrCreate(
        ['branch_id' => $branch->id],
        [
          'open_time' => $data['open_time'],
          'close_time' => $data['close_time'],
        ]
      );

      DB::commit();

      $branch['address'] = $address;
      $branch['operational_time'] = $operationalTime;
      return ['company' => $company, 'branch' => $branch];
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }
  }

  public function getCompany($owner_id = null, $company_id = null)
  {
    if ($owner_id) {
      $company = Company::where('owner_id', $owner_id)->firstOrFail();
    } elseif ($company_id) {
      $company = Company::where('id', $company_id)->firstOrFail();
    } else {
      throw new \Exception('Owner ID or Company ID must be provided');
    }

    $headquarter = Branch::where('company_id', $company->id)
      ->where('is_headquarter', true)
      ->with('address')
      ->with('operationalTime')
      ->firstOrFail();

    return [
      'company' => $company,
      'headquarter' => $headquarter,
    ];
  }
}
