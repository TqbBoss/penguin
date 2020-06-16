<?php
declare (strict_types = 1);

namespace app;

use app\api\service\AuthService;
use GuzzleHttp\Client;
use think\facade\Config;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 注册guzzle client
        $baseUrl = Config::get('penguin.wx.baseUrl');
        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 5.0,
            'verify'   => false
        ]);
        $this->app->bind('wxHttpClient', $client);

        // 注册认证服务
        $this->app->bind('api_auth', AuthService::class);
    }

    public function boot()
    {
        // 服务启动
    }
}
