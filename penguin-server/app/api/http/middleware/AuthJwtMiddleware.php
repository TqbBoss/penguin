<?php


namespace app\api\http\middleware;


use app\api\http\exception\AuthException;
use app\Request;

class AuthJwtMiddleware
{
    public function handle(Request $request, \Closure $next){
        // 忽略登入请求
        if ($request->isPost() && $request->baseUrl() == '/api/login'){
            return $next($request);
        }

        // 验证Token
        $jwtToken = trim(ltrim($request->header('Authorization', ''), 'Bearer'));
        $authInfo = jwtDecode($jwtToken);

        // 自定义方法
        Request::macro('getUserInfo', function () use (&$authInfo) {
            return $authInfo;
        });
        Request::macro('isLogin', function () use (&$authInfo) {
            return !is_null($authInfo);
        });
        return $next($request);
    }
}