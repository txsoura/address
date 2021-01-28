<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ApiAuth
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
        if (config('guardian.enabled')) {
            $guardian = config('guardian');
            $token = $request->header('Authorization');

            if (Str::startsWith($token, 'Bearer ')) {
                $token = Str::substr($token, 7);
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
            ])
                ->withToken($token)
                ->get($guardian['url'] . '/api/v1/auth/me',);

            if ($response->status() != 200) {
                return response()->json($response->json(), $response->status());
            }
        }

        return $next($request);
    }
}
