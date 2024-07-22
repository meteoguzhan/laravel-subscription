<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ForbiddenException extends HttpResponseException
{
    /**
     * Create a new not found exception instance.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(response()->json([
            'success' => false,
            'message' => $message,
        ], Response::HTTP_FORBIDDEN));
    }
}
