<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ApiACL
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('guardian.enabled') && config('guardian.acl')) {
            $guardian = config('guardian');
            $token = $request->header('Authorization');

            if (Str::startsWith($token, 'Bearer ')) {
                $token = Str::substr($token, 7);
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->withToken($token)
                ->get($guardian['url'] . '/api/v1/auth/me/permissions',);

            if ($response->status() != 200) {
                return response()->json($response->json(), $response->status());
            }

            $permissions = collect($response->json()['data']['permissions']);

            if (!$permissions) {
                return response()->json([
                    'message' => trans('message.permission_not_found'),
                    'error' => trans('auth.permission_denied')
                ], 403);
            }

            $route = $request->route()->getName();
            $permission = $permissions->where('name', $route)->first();

            if (!$permission) {
                return response()->json([
                    'message' => trans('message.no_permission'),
                    'error' => trans('auth.permission_denied')
                ], 403);
            }
        }

        return $next($request);
    }
}
