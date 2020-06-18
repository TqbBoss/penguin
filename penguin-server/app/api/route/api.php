<?php
use think\facade\Route;

Route::post('login', 'AuthController/login')
    ->middleware(\app\api\http\middleware\AuthJwtMiddleware::class);

Route::get('goods/all', 'GoodsController/getAll');
Route::post('goods/cart/add', 'GoodsController/refreshCart')
    ->middleware(\app\api\http\middleware\AuthJwtMiddleware::class);
Route::get('goods/cart/all', 'GoodsController/getCartGoods')
    ->middleware(\app\api\http\middleware\AuthJwtMiddleware::class);