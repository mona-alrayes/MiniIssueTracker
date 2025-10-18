<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    MethodNotAllowedHttpException,
    NotFoundHttpException,
    UnauthorizedHttpException,
    UnsupportedMediaTypeHttpException
};
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // ApiException (custom service exception)
        $this->renderable(function (ApiException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse($e->getMessage(), $e->getStatusCode());
        });

        // Model or route not found
        $this->renderable(function ($e, $request) {
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
                return $this->formatErrorResponse('The requested model was not found.', 404);
            }
        });

        // Method not allowed
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('The HTTP method is not allowed for this route.', 405);
        });

        // Access denied / unauthorized
        $this->renderable(function ($e, $request) {
            if ($e instanceof UnauthorizedHttpException || $e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
                Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
                return $this->formatErrorResponse('You do not have permission to access this resource.', 403);
            }
        });

        // Authentication
        $this->renderable(function (AuthenticationException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('Unauthenticated, please login.', 401);
        });

        // Too many requests
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('Too many requests. Please slow down.', 429);
        });

        // Bad request
        $this->renderable(function (BadRequestHttpException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('Bad request. Please check your input.', 400);
        });

        // Unsupported media type
        $this->renderable(function (UnsupportedMediaTypeHttpException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('Unsupported media type.', 415);
        });

        // Query exception
        $this->renderable(function (QueryException $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->formatErrorResponse('A database query error occurred.', 500);
        });

        // Generic Exception
        $this->renderable(function (\Exception $e, $request) {
            Log::error($e->getMessage(), ['trace'=>$e->getTraceAsString()]);

            if (config('app.debug')) {
                return parent::render($request, $e); // full debug info in local
            }

            return $this->formatErrorResponse('An unexpected error occurred.', 500);
        });
    }

    /**
     * Format the error response for JSON output.
     */
    protected function formatErrorResponse(string $message, int $statusCode, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
