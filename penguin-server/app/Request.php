<?php
namespace app;

// 应用请求对象类
use Spatie\Macroable\Macroable;

/**
 * Class Request
 * @package app
 * 支持自定义方法
 */
class Request extends \think\Request
{
    use Macroable;
}
