<?php


namespace app\api\service;


use app\api\http\exception\AuthException;
use app\api\http\HttpCode;
use app\api\security\ErrorCode;
use app\api\security\WxDataCrypt;
use GuzzleHttp\Exception\RequestException;
use think\facade\Config;
use think\facade\Db;
use think\facade\Log;

class AuthService
{
    /**
     * @param string $code
     * @param string $iv
     * @param string $encryptedData
     * @return array|\think\Model
     * 获取认证用户
     */
    public function getAuthUser(string $code, string $iv, string $encryptedData){
        if ($code == null || $code == ''){
            throw new AuthException(HttpCode::build(ErrorCode::$AuthFail, '认证失败'));
        }
        $appid = Config::get('penguin.wx.AppId');
        $appsecret = Config::get('penguin.wx.AppSecret');
        try {
            $response = app('wxHttpClient')->request('GET', 'sns/jscode2session', [
                'query' => [
                    'appid'         => $appid,
                    'secret'        => $appsecret,
                    'js_code'       => $code,
                    'grant_type'    => 'authorization_code'
                ]
            ]);
        }
        catch (RequestException $e){
            Log::error("请求微信服务获取用户权限失败：" . $e->getMessage());
            throw new AuthException(HttpCode::build(ErrorCode::$AuthFail, $e->getMessage()));
        }
        $jsonData = json_decode($response->getBody());
        if (property_exists($jsonData,'errcode') && $jsonData->errcode != 0){
            Log::error("请求微信服务获取用户权限失败：" . $jsonData->errmsg);
            throw new AuthException(HttpCode::build(ErrorCode::$AuthFail, $jsonData->errmsg));
        }
        try {
            $pc = new WxDataCrypt($appid, $jsonData->session_key);
            $errCode = $pc->decryptData($encryptedData, $iv, $data );
            if ($errCode == ErrorCode::$OK){
                $user = Db::name('user')->where('openid', $jsonData->openid)->limit(1)->find();
                if ($user == null){
                    $user = [
                        'uid'       => uniqid('u_', true),
                        'openid'    => $jsonData->openid,
                        'rawdata'   => json_encode($data->watermark),
                        'nickname'  => $data->nickName,
                        'gender'    => $data->gender,
                        'avatarurl' => $data->avatarUrl
                    ];
                    Db::name('user')->insert($user);
                }
                else{
                    $user['rawdata'] = json_encode($data->watermark);
                    Db::name('user')->where('uid', $user['uid'])->update(['rawdata' => $user['rawdata']]);
                }
                return $user;
            }
            else{
                throw new AuthException(HttpCode::build($errCode, '认证失败'));
            }
        }
        catch (\Exception $e){
            Log::error("服务异常：" . $e->getMessage());
            throw new AuthException(HttpCode::build(ErrorCode::$AuthFail, $e->getMessage()));
        }
    }
}