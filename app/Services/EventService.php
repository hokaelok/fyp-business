<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
  public function createEvent($data)
  {
    DB::beginTransaction();
    try {
      $event = Event::create($data);

      DB::commit();

      return $event;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function getEvents($company_id)
  {
    $query = Event::query();
    if ($company_id) {
      $query->where('company_id', $company_id);
    }
    return $query->get();
  }

  public function getEvent($eventId)
  {
    $event = Event::findOrFail($eventId);
    return $event;
  }

  public function updateEvent($eventId, $data)
  {
    $event = Event::findOrFail($eventId);

    DB::beginTransaction();
    try {
      $event->update($data);

      DB::commit();

      return $event;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
