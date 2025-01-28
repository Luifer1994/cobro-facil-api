<?php

namespace App\Facades;

use Illuminate\Support\Facades\Response as BaseResponse;
use Illuminate\Http\JsonResponse;

/**
 * @method static JsonResponse json(string $message = '', $data = null, int $statusCode = 200)
 */
class Response extends BaseResponse
{
}
