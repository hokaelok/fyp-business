<?php

namespace App\Traits;

use App\Http\StatusCode;

trait JsonResponseTrait
{
  public function successResponse($data = null, $notify = null, $status = StatusCode::SUCCESS)
  {
    $response = [
      'success' => true,
      'data' => $data,
      'notify' => $notify,
    ];

    return response()->json($response, $status);
  }

  public function errorResponse($notify = null, $error_code = 'ERROR', $errors = null, $status = StatusCode::BAD_REQUEST)
  {
    $response = [
      'success' => false,
      'error_code' => $error_code,
      'errors' => $errors,
      'notify' => $notify,
    ];

    return response()->json($response, $status);
  }
}
