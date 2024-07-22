<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends HttpResponseException
{
    /**
     * Create a new validation exception instance.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        parent::__construct(response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
