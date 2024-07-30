<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BusinessPickupController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConsumerPickupController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HotspotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Hotspot
Route::get('hotspots', [HotspotController::class, 'get']);
Route::get('hotspot', [HotspotController::class, 'show']);
Route::get('business/hotspots', [HotspotController::class, 'getBiz']);
Route::get('business/hotspot/{hotspotId}', [HotspotController::class, 'showBiz']);

// Company
Route::get('company', [CompanyController::class, 'show']);
Route::post('company/create', [CompanyController::class, 'store']);
Route::post('company/update', [CompanyController::class, 'update']);

// Branch
Route::get('branches', [BranchController::class, 'get']);
Route::get('branch/{branchId}', [BranchController::class, 'show']);
Route::post('branch/update/{branchId}', [BranchController::class, 'update']);
Route::post('branch/create', [BranchController::class, 'store']);

// Event
Route::get('events', [EventController::class, 'get']);
Route::get('event/{eventId}', [EventController::class, 'show']);
Route::post('event/update/{eventId}', [EventController::class, 'update']);
Route::post('event/create', [EventController::class, 'store']);

// Pickup
Route::get('pickups', [ConsumerPickupController::class, 'get']);
Route::post('pickups/history', [ConsumerPickupController::class, 'history']);
Route::get('pickup/{pickupId}', [ConsumerPickupController::class, 'show']);
Route::post('pickup/update/{pickupId}', [ConsumerPickupController::class, 'update']);
Route::post('pickup/create', [ConsumerPickupController::class, 'store']);
Route::get('pickup/bookedSlots/{branchId}', [ConsumerPickupController::class, 'getBookedSlots']);

// Business Pickup
Route::get('business/pickups', [BusinessPickupController::class, 'get']);
Route::get('business/pickup/{pickupId}', [BusinessPickupController::class, 'show']);
Route::post('business/pickup/update/{pickupId}', [BusinessPickupController::class, 'update']);
Route::post('business/pickup/create', [BusinessPickupController::class, 'store']);
Route::get('business/pickup/bookedSlots/{branchId}', [BusinessPickupController::class, 'getBookedSlots']);
