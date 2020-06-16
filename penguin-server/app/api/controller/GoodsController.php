<?php


namespace app\api\controller;


use app\api\http\exception\ParamException;
use app\api\model\Goods;
use app\api\security\ErrorCode;
use app\BaseController;
use app\Request;
use think\facade\Db;

class GoodsController extends BaseController
{
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 获取所有商品
     */
    public function getAll(){
        $data = Db::name("category")
            ->alias('b')
            ->join('goods a','a.category_id = b.id')
            ->select()
            ->all();
        $categoryData = [];
        foreach ($data as $item) {
            $key = $item['category'];
            if(isset($categoryData[$key])){
                $categoryData[$key][] = $item;
            }
            else{
                $categoryData[$key] = [ $item ];
            }
        }
        return json([
            'code'      => ErrorCode::$OK,
            'message'   => '',
            'data'      => $categoryData
        ]);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 当前购物车添加商品总数
     */
    public function getCartGoodsCount(Request $request){
        $userInfo = $request->getUserInfo();
        $data = Db::name('cart')
            ->field('SUM(good_num) AS num')
            ->group('uid')
            ->where('uid', $userInfo->uid)
            ->select()
            ->all();

        return json([
            'count'     => count($data) > 0 ? $data[0]['num'] : 0,
            'code'      => ErrorCode::$OK,
            'message'   => ''
        ]);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * 商品添加购物车
     */
    public function refreshCart(Request $request){
        $body = $request->post();
        $userInfo = $request->getUserInfo();
        Db::name('cart')->where('uid', $userInfo->uid)->delete();

        $data = [];
        foreach ($body as $value){
            array_push($data, [
                'uid'       => $userInfo->uid,
                'good_id'   => $value['id'],
                'good_num'  => $value['count'],
                'is_selected'  => $value['selected']
            ]);
        }
        try {
            Db::name('cart')->insertAll($data);
        }
        catch (\Exception $e){
            return json([
                'code'      => ErrorCode::$AddCartFail,
                'message'   => '添加购物车失败'
            ]);
        }
        return json([
            'code'      => ErrorCode::$OK,
            'message'   => '已添加到购物车'
        ]);
    }

    public function getCartGoods(Request $request){
        $userInfo = $request->getUserInfo();
        $data = Db::name("cart")
            ->alias('b')
            ->join('goods a','a.id = b.good_id')
            ->where('b.uid', $userInfo->uid)
            ->column('b.good_num,b.is_selected,a.*', 'b.id');
        return json($data);
    }
}