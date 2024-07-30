<?php

namespace App\Http\Controllers;

use App\Services\HotspotService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
  use JsonResponseTrait;

  protected $hotspotService;

  public function __construct(HotspotService $hotspotService)
  {
    $this->hotspotService = $hotspotService;
  }

  public function get()
  {
    try {
      $hotspots = $this->hotspotService->getHotspots();
      return $this->successResponse($hotspots);
    } catch (\Exception $e) {
      $notify = [
        'title' => 'Hotspot Fetch Failed',
        'description' => 'Failed to fetch hotspots',
      ];
      return $this->errorResponse($notify, 'HOTSPOT_FETCH_ERROR', [$e->getMessage()]);
    }
  }

  public function show(Request $request)
  {
    try {
      $id = $request->query('id');
      $type = $request->query('type');
      $hotspot = $this->hotspotService->getHotspot($id, $type);

      return $this->successResponse($hotspot);
    } catch (\Exception $e) {
      $notify = [
        'title' => 'Hotspot Fetch Failed',
        'description' => 'Failed to fetch hotspot',
      ];
      return $this->errorResponse($notify, 'HOTSPOT_FETCH_ERROR', [$e->getMessage()]);
    }
  }

  public function getBiz()
  {
    try {
      $hotspots = $this->hotspotService->getBusinessHotspots();
      return $this->successResponse($hotspots);
    } catch (\Exception $e) {
      $notify = [
        'title' => 'Hotspot Fetch Failed',
        'description' => 'Failed to fetch hotspots',
      ];
      return $this->errorResponse($notify, 'HOTSPOT_FETCH_ERROR', [$e->getMessage()]);
    }
  }

  public function showBiz($hotspotId)
  {
    try {
      $hotspot = $this->hotspotService->getBusinessHotspot($hotspotId);
      return $this->successResponse($hotspot);
    } catch (\Exception $e) {
      $notify = [
        'title' => 'Hotspot Fetch Failed',
        'description' => 'Failed to fetch hotspot',
      ];
      return $this->errorResponse($notify, 'HOTSPOT_FETCH_ERROR', [$e->getMessage()]);
    }
  }
}
