<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function success(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => '¡Operación exitosa!'
        ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function successMessage($message): JsonResponse
    {
        return response()->json(['message' => $message]);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function successData($data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @return false
     */
    public function error(): bool
    {
        return false;
    }
}
