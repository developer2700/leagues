<?php
 
namespace App\Http\Middleware;

use Closure;
use Exception;
use http\Env\Request;

class Json
{

    /**
     * CREATE JSON RESPONSE
     *
     * @param $request
     * @param Closure $next
     *
     * @return Closure
     */
    public function handle($request, Closure $next)
    {


        if ($request->headers->has('accept-app')) {
            try {
                $response = $next($request);
                return response()->json([
                    "status" => true,
                    "code" => 200,
                    "message" => "",
                    "error" => "",
                    "data" => $response->original
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => false,
                    "code" => $e->getCode(),
                    "message" => $e->getMessage(),
                    "error" => $e->getMessage(),
                    "data" => $response->original
                ], $e->getCode());
            }
        } else {
            return $next($request);
        }

    }

}