<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

class PasteResponse
{
    public function createSuccessResponse(string $message): JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
            'message' => $message
        ], JsonResponse::HTTP_OK);
    }

    public function createErrorResponse(string $message): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $message
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
