<?php


namespace app\api\controller;

use app\api\security\ErrorCode;
use app\BaseController;

/**
 * Class AuthController
 * @package app\api\controller
 * Token认证
 */
class AuthController extends BaseController
{
    /**
     * @param string $code
     * @param string $iv
     * @param string $encryptedData
     * @return \think\response\Json
     * 微信后台登入认证
     */
    public function login(string $code, string $iv, string $encryptedData){
        $user = app('api_auth')->getAuthUser($code, $iv, $encryptedData);
        $token = jwtEncode([
           'uid'    =>  $user['uid']
        ]);
        return json([
            'token'     =>  $token,
            'code'      =>  ErrorCode::$OK,
            'message'   => ''
        ]);
    }

    public function decrypt(){

    }
}