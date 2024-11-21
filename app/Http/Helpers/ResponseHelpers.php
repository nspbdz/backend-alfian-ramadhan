<?php

namespace App\Http\Helpers;

class ResponseHelpers
{
    public static function sendError($message, $errors = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public static function sendSuccess($message, $data = [], $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'code' => $code,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}