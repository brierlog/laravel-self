<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @throws \Exception
     * @return void
     *
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function render($request, Exception $e): JsonResponse
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        }

        $e = $this->prepareException($e);

        if ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }
        if ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        }
        if ($e instanceof UnauthorizedHttpException) {
            return response()->json(['errors' => 'Unauthorized'], $e->getStatusCode());
        }

        return $this->prepareJsonResponse($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        return response()->json(['errors' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request): JsonResponse
    {
        $errors = array_values($e->errors());
        $errors = Arr::get($errors, '0.0', 'Validation error');

        return response()->json(['errors' => $errors], $e->status);
    }

    protected function prepareJsonResponse($request, Exception $e): JsonResponse
    {
        return new JsonResponse(
            $this->convertExceptionToArray($e),
            $this->isHttpException($e) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->isHttpException($e) ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    protected function convertExceptionToArray(Exception $e): array
    {
        $errorMessage = $this->buildStrErrorBasedOnEnv($e);
        if ($e instanceof NotFoundHttpException) {
            $errorMessage = 'Not Found';
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            $errorMessage = 'Method Not Allowed';
        }
        if ($e instanceof ModelNotFoundException) {
            $errorMessage = trans('errors.resource_not_found');
        }

        return ['errors' => $errorMessage];
    }

    private function buildStrErrorBasedOnEnv(Exception $e): string
    {
        $msg = __('errors.internal_server_error');

        return isProd() ? $msg : $msg.' [Message]: '.$e->getMessage().' [File]:'.$e->getFile().' [line]:'.$e->getLine();
    }
}
