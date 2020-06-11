<?php
// 应用公共文件

/**
 * 生成访问令牌
 */
if (!function_exists('jwtEncode')){
    function jwtEncode($data){
        $key = \think\facade\Config::get('weixin.key');
        $time = time();
        $token = [
            'iss' => 'https://www.tqboss.cn', //签发者
            'aud' => 'https://www.tqboss.cn', //接收者
            'iat' => $time, //签发时间
            'nbf' => $time , //可访问时间
            'exp' => $time+7200, //过期时间,2个小时后
            'data' => $data
        ];
        return \Firebase\JWT\JWT::encode($token, $key);
    }
}

/**
 * 验证访问令牌
 */
if(!function_exists('jwtDecode')){
    function jwtDecode($token){
        $key = \think\facade\Config::get('weixin.key');
        try {
            $decoded = \Firebase\JWT\JWT::decode($token, $key, ['HS256']);
            return new \app\api\model\ResultReport(true, $decoded["data"]);
        }
        catch (\Firebase\JWT\SignatureInvalidException $e){
            return new \app\api\model\ResultReport(false, "", "令牌验证失败");
        }
        catch (\Firebase\JWT\BeforeValidException $e){
            return new \app\api\model\ResultReport(false, "", "令牌未开启");
        }
        catch (\Firebase\JWT\ExpiredException $e){
            return new \app\api\model\ResultReport(false, "", "令牌过期");
        }
        catch (Exception $e){
            return new \app\api\model\ResultReport(false, "", "验证错误");
        }
    }
}
