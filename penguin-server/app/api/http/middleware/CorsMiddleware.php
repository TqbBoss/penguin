<?php


namespace app\api\http\middleware;


use app\Request;
use Closure;
use think\facade\Config;
use think\Response;

class CorsMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $header = Config::get('penguin.cors', []);

        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create()->code(204)->header($header);
        } else {
            $response = $next($request)->header($header);
        }
        return $response;
    }
}