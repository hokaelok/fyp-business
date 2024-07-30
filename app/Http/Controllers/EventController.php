<?php

namespace App\Http\Controllers;

use App\Http\StatusCode;
use App\Services\EventService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class EventController extends Controller
{
  use JsonResponseTrait;

  protected $eventService;

  public function __construct(EventService $eventService)
  {
    $this->eventService = $eventService;
  }

  public function store(Request $request)
  {
    try {
      $event = $this->eventService->createEvent($request->all());
      return $this->successResponse($event, null, StatusCode::CREATED);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while creating the event',
        'EVENT_CREATION_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function get(Request $request)
  {
    try {
      $company_id = $request->query('company_id');
      $events = $this->eventService->getEvents($company_id);

      return $this->successResponse($events);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching events',
        'EVENTS_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function show($eventId)
  {
    try {
      $event = $this->eventService->getEvent($eventId);
      return $this->successResponse($event);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while fetching the event',
        'EVENT_FETCH_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
      );
    }
  }

  public function update(Request $request, $eventId)
  {
    try {
      $event = $this->eventService->updateEvent($eventId, $request->all());
      return $this->successResponse($event);
    } catch (\Exception $e) {
      return $this->errorResponse(
        'An error occurred while updating the event',
        'EVENT_UPDATE_ERROR',
        [$e->getMessage()],
        StatusCode::INTERNAL_SERVER_ERROR,
      );
    }
  }
}
