<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;
use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = null;
        try {
            $token = $request->header('Authorization');
            $headers = ['Authorization' => $token];
            $URL = env('SSO_URL') . "/me";
            // http://gatewayservice.sit.kmutt.ac.th/api/oauth/token?client_secret=b46Ivmua&client_id=IlNvm&code=SOn2MTlB1I
            $client = new Client(['base_uri' => $URL, 'headers' => $headers]);
            $response = $client->request('GET', $URL,);
            $body = json_decode($response->getBody(), true);
            return $next($request);
        } catch (\Throwable $th) {
            $responseBody = $th->getResponse();
            $body = json_decode($responseBody->getBody(), true);
            return response()->json($body, $responseBody->getStatusCode());
        }
    }
}
