<?php
namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status);
    }
}
