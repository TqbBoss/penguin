<?php


namespace app\admin\controller;


use app\api\model\User;
use think\facade\Db;

class IndexController
{
    public function index(){
        $user = Db::name('user')->where('openid', 'obtsA0cfVYGb9wBn9SmTMi3WqALM')->limit(1)->find();
        return json($user);
    }
}