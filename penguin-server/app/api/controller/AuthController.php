<?php


namespace app\api\controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use think\facade\Config;

/**
 * Class AuthController
 * @package app\api\controller
 * Token认证
 */
class AuthController
{
    /**
     * @param string $code
     * @return \Psr\Http\Message\StreamInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * 微信后台登入认证
     */
    public function login(string $code){
        $appId = Config::get('weixin.AppId');
        $appSecret = Config::get('weixin.AppSecret');
        $baseUrl = Config::get('weixin.baseUrl');

        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 5.0
        ]);

        try {
            $response = $client->request('GET', 'sns/jscode2session', [
                'query' => [
                    'appid'         => $appId,
                    'secret'        => $appSecret,
                    'js_code'       => $code,
                    'grant_type'    => 'authorization_code'
                ]
            ]);

        }
        catch (RequestException $e){
            return 'error';
        }

        $jsonData = json_decode($response->getBody());

        return $jsonData;
    }
}