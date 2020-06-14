<?php


namespace app\api\middleware;


use app\api\exception\AuthException;
use app\Request;

class AuthJwtMiddleware
{
    public function handle(Request $request, \Closure $next){
        // 忽略登入请求
        if ($request->isPost() && $request->controller() == 'AuthController' && $request->action() == 'login'){
            return $next($request);
        }
        // 验证Token
        $authInfo = null;
        $jwtToken = trim(ltrim($request->header('Authorization', ''), 'Bearer'));
        try {
            $authInfo = jwtDecode($jwtToken);
        } catch (AuthException $e) {
            return json($e->getMessage(), $e->getCode());
        }
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