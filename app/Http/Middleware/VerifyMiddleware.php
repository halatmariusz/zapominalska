<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public function handle($request, Closure $next)
    {
        if ($request->input("hub_mode") === "subscribe"
            && $request->input("hub_verify_token") === env("MESSENGER_VERIFY_TOKEN")) {
            return response($request->input("hub_challenge"), 200);
        }
        return $next($request);
    }
}
