<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
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
        $guardian = config('services.guardian');
        $token = $request->header('Authorization');

        if (Str::startsWith($token, 'Bearer ')) {
            $token = Str::substr($token, 7);
        }

        $client = new Client();
        $response = $client->request(
            'GET',
            $guardian['url'] . '/api/v1/auth/me',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        dd($response);

        // if ($response->getStatusCode != 200) {
        //     \Log::info($response->getBody());
        //     \Log::info("k===========================================");
        //     $response = json_decode($response->getBody());
        //     \Log::info($response);
        //     \Log::info("k===========================================");

        //     \Log::info($response->status);
        //     \Log::info("k===========================================");
        // }
        return $next($request);
    }
}
