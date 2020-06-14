<?php

return [
    'wx'    => [
        'key'           => 'penguin',
        // 微信小程序AppID
        'AppId'         => env('weixin.appid', ''),
        // 微信小程序AppSecret
        'AppSecret'         => env('weixin.appsecret', ''),
        // 微信Api访问基地址
        'baseUrl'       => 'https://api.weixin.qq.com'
    ],
    'cors'      => [
        'allowOrigin' => '*',
        'allowHeaders' => '*',
        'allowMethods' => 'GET,POST,OPTIONS',
        'maxAge' => '86400'
    ]
];