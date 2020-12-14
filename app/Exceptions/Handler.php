<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => trans('message.not_found'),
                    'error' => trans('message.entry_not_found', ['model' => str_replace('App\\Models\\', '', $e->getModel())])
                ], 404);
            }

            if ($e instanceof UnauthorizedHttpException) {
                $preException = $e->getPrevious();

                if ($preException instanceof
                    \Tymon\JWTAuth\Exceptions\TokenExpiredException
                ) {
                    return response()->json([
                        'message' => trans('auth.unauthenticated'),
                        'error' => trans('auth.token_expired')
                    ], 401);
                } else if ($preException instanceof
                    \Tymon\JWTAuth\Exceptions\TokenInvalidException
                ) {
                    return response()->json([
                        'message' => trans('auth.unauthenticated'),
                        'error' => trans('auth.token_invalid')
                    ], 401);
                } else if ($preException instanceof
                    \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
                ) {
                    return response()->json([
                        'message' => trans('auth.unauthenticated'),
                        'error' => trans('auth.token_blacklisted')
                    ], 401);
                } else if ($preException instanceof
                    \Tymon\JWTAuth\Exceptions\JWTException
                ) {
                    return response()->json([
                        'message' => trans('auth.unauthenticated'),
                        'error' => trans('auth.token_cannot_parse')
                    ], 401);
                }

                if ($e->getMessage() === 'Token not provided') {
                    return response()->json([
                        'message' => trans('auth.unauthenticated'),
                        'error' => trans('auth.token_not_provided')
                    ], 401);
                }
            }

            if ($e instanceof
                \Tymon\JWTAuth\Exceptions\JWTException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.already_logged_out')
                ], 422);
            }

            if ($e instanceof
                \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
            ) {
                return response()->json([
                    'message' => trans('auth.not_found'),
                    'error' => trans('auth.user_not_found')
                ], 404);
            }

            return parent::render($request, $e);
        });
    }
}
