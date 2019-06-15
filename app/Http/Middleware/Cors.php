<?php
 
namespace  App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{

    /**
     * CREATE CORS MIDDLEWARE
     *
     * @param $request
     * @param Closure $next
     *
     * @return Closure
     */

    public function handle($request, Closure $next)
    {
        if ($request->getMethod() === "OPTIONS") {
            return response()->json(['success' => 'ok'], 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        }
        $sAccept = $request->header('Accept');
        $aAccept = explode(',', $sAccept);
        $bAcceptJson = false;
        foreach ($aAccept as $accept) {
            if ($accept == 'application/json') {
                $bAcceptJson = true;
            }
        }
        if ($bAcceptJson) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        } else {
            return $next($request);
        }


    }

}