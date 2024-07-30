<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Event;

class HotspotService
{
  public function getHotspots()
  {
    $branches = Branch::whereHas('address')
      ->with('company')
      ->with('address')
      ->get()
      ->map(function ($branch) {
        $branch->type = 'hotspot';
        return $branch;
      });

    $events = Event::with('company')
      ->where('end_time', '>=', now())
      ->get()
      ->map(function ($event) {
        return [
          'id' => $event->id,
          'company_id' => $event->company_id,
          'name' => $event->name,
          'image' => $event->image,
          'description' => $event->description,
          'start_time' => $event->start_time,
          'end_time' => $event->end_time,
          'type' => 'event',
          'address' => [
            'street' => $event->street,
            'city' => $event->city,
            'state' => $event->state,
            'zip' => $event->zip,
            'latitude' => $event->latitude,
            'longitude' => $event->longitude,
          ],
          'company' => [
            'id' => $event->company->id,
            'name' => $event->company->name,
            'owner_id' => $event->company->owner_id,
            'logo' => $event->company->logo,
            'website' => $event->company->website,
          ],
        ];
      });

    return array_merge($branches->toArray(), $events->toArray());
  }

  public function getHotspot($id, $type)
  {
    $data = null;

    if ($type === 'hotspot') {
      $branch = Branch::with('company')
        ->with('address')
        ->with('operationalTime')
        ->findOrFail($id);

      $data = $branch;
    } else if ($type === 'event') {
      $event = Event::with('company')->findOrFail($id);
      $data = [
        'id' => $event->id,
        'company_id' => $event->company_id,
        'name' => $event->name,
        'image' => $event->image,
        'description' => $event->description,
        'start_time' => $event->start_time,
        'end_time' => $event->end_time,
        'type' => 'event',
        'address' => [
          'street' => $event->street,
          'city' => $event->city,
          'state' => $event->state,
          'zip' => $event->zip,
          'latitude' => $event->latitude,
          'longitude' => $event->longitude,
        ],
        'company' => [
          'id' => $event->company->id,
          'name' => $event->company->name,
          'owner_id' => $event->company->owner_id,
          'logo' => $event->company->logo,
          'website' => $event->company->website,
        ],
      ];
    }

    return $data;
  }

  public function getBusinessHotspots()
  {
    $branches = Branch::whereHas('address')
      ->where('branch_type', 'collector')
      ->with('company')
      ->with('address')
      ->get();

    return $branches;
  }

  public function getBusinessHotspot($hotspotId)
  {
    return Branch::where('branch_type', 'collector')
      ->with('company')
      ->with('address')
      ->with('operationalTime')
      ->findOrFail($hotspotId);
  }
}
