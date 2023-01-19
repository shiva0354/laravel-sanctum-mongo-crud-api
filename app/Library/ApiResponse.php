<?php

namespace App\Library;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /*
     * Send success response to the client with the given data
     */
    public static function success($data, ?string $message = 'OK',  ?string $debug = null): JsonResponse
    {
        return self::response(200, 'success', $message, $debug, $data);
    }

    /*
     * We failed to perform the requested action due to issues with the request (eg. invalid otp).
     */
    public static function failed(string $message, ?array $errors = null, ?string $debug = null): JsonResponse
    {
        return self::response(400, 'failed', $message, $debug, null, $errors);
    }

    /*
     * Some part of the request is invalid. list of errors as (key => array of strings) pair is sent in response
     */
    public static function invalid(string $message, ?array $errors = null, ?string $debug = null): JsonResponse
    {
        return self::response(422, 'invalid', $message, $debug, null, $errors);
    }

    /*
     * Request is Unauthenticated. Auth token is missing or invalid.
     */
    public static function unauthenticated(string $message = 'Unauthenticated', ?string $debug = null): JsonResponse
    {
        return self::response(401, 'unauthenticated', $message, $debug);
    }

    /*
     * The request is forbidden for the current user due to permissions
     */
    public static function forbidden(string $message = 'Forbidden', ?string $debug = null): JsonResponse
    {
        return self::response(403, 'forbidden', $message, $debug);
    }

    /*
     * The requested resource was not found on the server
     */
    public static function notfound(string $message = 'Not Found', ?string $debug = null): JsonResponse
    {
        return self::response(404, 'notfound', $message, $debug);
    }

    /*
     * The request method is not allowed
     */
    public static function methodNotAllowed(string $message = 'Method Not Allowed', ?string $debug = null): JsonResponse
    {
        return self::response(405, 'disallowed', $message, $debug);
    }

    /*
     * The client is using an older version of app and must upgrade
     */
    public static function upgrade($message = 'Upgrade Required', ?string $debug = null): JsonResponse
    {
        return self::response(426, 'upgrade', $message, $debug);
    }

    /*
     * The client is hitting our server too many times
     */
    public static function tooManyRequest($message = 'Too Many Request', ?string $debug = null): JsonResponse
    {
        return self::response(429, 'too many request', $message, $debug);
    }

    /*
     * An exception occurred while processing the request.
     */
    public static function exception(\Throwable $ex, ?string $debug = null): JsonResponse
    {
        return self::response(500, 'error', 'Server Error: ' . $ex->getMessage(), $debug);
    }

    private static function response(int $code, string $status, string $message, ?string $debug = null, $data = null, ?array $errors = null): JsonResponse
    {
        $result = ['code' => $code, 'status' => $status, 'message' => $message];
        if ($data) {
            $result['data'] = $data;
        }

        if ($debug) {
            $result['debug'] = $debug;
        }

        if ($errors) {
            $result['errors'] = $errors;
        }

        return response()->json($result, $code, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT);
    }
}
