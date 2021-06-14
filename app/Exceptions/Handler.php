<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.entry_not_found', ['model' => str_replace('App\\Models\\', '', $exception->getModel())])
            ], 404);
        }

        if ($exception instanceof RelationNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.relation_not_found')
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 405);
        }

        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.route_not_found')
            ], 404);
        }

        if ($exception instanceof RouteNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.route_not_found')
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
