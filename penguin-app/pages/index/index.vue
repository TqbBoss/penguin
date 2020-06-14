<template>
	<view class="container flex flex-direction">
		<cu-custom bgColor="FFFFFF" :isBack="false">
			<block slot="content">首页</block>
		</cu-custom>
		<view class="flex align-center justify-center go-white">
			<mehaotian-search class="go-search" placeholder="搜索商品" :mode="2" button="inside" @search="search" :show="false"></mehaotian-search>
		</view>
		<view id="content" class="flex go-bottom-box">
			<scroll-view class="left-tabs" :scroll-top="scrollTop" :scroll-y="true">
				<view :class="['go-tab', item.active ? 'go-tab-active' : 'go-tab-normal']" v-for="(item,index) in tabItems" :key="item.name" @click="clickTab(index)">
					<view class="go-tab-content">{{item.name}}</view>
				</view>
			</scroll-view>
			<scroll-view class="right-goods" :scroll-top="scrollTop" :scroll-y="true">
				<view class="tui-card go-goods-card">
					<view class="tui-card-header" :tui-header-line="false">
						<view class="tui-header-left">
							<text class="go-card-p">{{goods.title}}</text>
						</view>
					</view>
					<view class="tui-card-body go-card-p">
						<view class="flex flex-wrap">
							<view class="go-good flex" v-for="good in goods.data" :key="good.id">
								<view class="go-good-header">
									<image :src="good.thumbnail" mode="scaleToFill"></image>
								</view>
								<view class="go-good-body flex flex-direction justify-between">
									<view class="good-title">{{good.name}}</view>
									<view class="flex flex-direction goods-des">
										<view class="flex goods-h">
											<view class="nprice">￥{{good.concessional_price}}</view>
											<view class="oprice">￥{{good.original_price}}</view>
										</view>
										<view class="flex goods-h goods-border-bottom justify-between">
											<view class="sales">总销售量{{good.sold}}件</view>
											<view :id="good.dom_id" class="goods-btn" @click="addToCart(good)">
												<text>+</text>
											</view>
										</view>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
		<view v-if="ball.show" :animation="animationData" class="go-ball" :style="{left:ball.left+'px',top:ball.top+'px'}">
			<image :src="ball.image" mode="scaleToFill"></image>
		</view>
	</view>
</template>

<script>
	import http from '../../common/http.js';
	
	export default {
		data() {
			return {
				scrollTop: 0,
				currentTabIndex: 1,
				isAnimate: false,
				timer: 0,
				cart_shoppings: 0,
				animationData: {},
				ball: {
					left: 0,
					top: 0,
					image: '../../static/images/goods/1.jpg',
					show: false
				},
				goods: {
					title: '',
					data: []
				},
				tabItems: []
			}
		},
		onLoad() {
			let $that = this;
			http.get('/api/goods/all').then((res)=>{
				if(res.code == 200){
					let result = res.data;
					let active = true;
					this.tabItems = [];
					for(let m in result) {
						let transAttData = result[m].map((it)=>{
							it.dom_id = 'id_' + it.id;
							it.thumbnail = this.BASE_URL +it.thumbnail;
							return it;
						})
					    this.tabItems.push({
							name: m,
							active: active,
							data: transAttData
						});
						if(active){
							this.goods.data = [];
							this.goods.title = m;
							transAttData.forEach((good)=>{
								this.goods.data.push(good);
							});
						}
						active = false;
					}; 
				}
			});
			uni.$on('loginOn',function(){
			    $that.getCartCount();
			})
		},
		onPullDownRefresh(){
			setTimeout(function(){
				uni.stopPullDownRefresh();
				uni.showToast({
					title:"已刷新"
				});
			},1000);
		},
		methods: {
			search(value) {
				
			},
			clickTab(index) {
				this.tabItems[this.currentTabIndex].active = false;
				this.currentTabIndex = index;
				this.tabItems[index].active = true;
				
				this.goods.data = [];
				this.goods.title = this.tabItems[index].name;
				this.tabItems[index].data.forEach((good)=>{
					this.goods.data.push(good);
				})
			},
			addToCart(menu){
				http.post(`/api/goods/cart/add/${menu.id}/1?XDEBUG_SESSION_START=13575`).then((item)=>{
					if(item.data.code == 200){
						this.animateAddToCart(menu);
					}
					else{
						uni.showToast({
						    title: '添加失败',
						    duration: 2000
						});
					}
				});
			},
			animateAddToCart(menu) {
				if(this.isAnimate){
					return;
				}
				let $that = this;
				$that.isAnimate = true;
				$that.ball.image = menu.thumbnail;
				let animation = uni.createAnimation({
					duration:150,
					timeFunction: 'ease',
					delay: 0,
					transformOrigin: '50% 50%'
				});
				let point = uni.createSelectorQuery().select('#' + menu.dom_id);
				point.boundingClientRect(data1 => {
					$that.ball.left = data1.left;
					$that.ball.top = data1.top;
					$that.ball.show = true;
					let cartTab = uni.createSelectorQuery().select('#content');
					cartTab.boundingClientRect(data2 => {
						let cart_x = (data2.left + data2.right) / 2;
						let cart_y = data2.bottom + 30;
						animation.translate(-50,-25).step();
						animation.translate(cart_x - data1.left - 25, cart_y - data1.top + 50).scale(0.1, 0.1).step();
						$that.animationData = animation.export();
						setTimeout(function(){
							$that.ball.show = false;
							$that.animationData = {};
							$that.isAnimate = false;
							$that.cart_shoppings += 1;
							uni.setTabBarBadge({
								index: 1,
								text: $that.cart_shoppings + ''
							});
						},300);
					}).exec();
				}).exec();
			},
			getCartCount() {
				let $that = this;
				http.get('/api/goods/cart/count').then((item)=>{
					if(item.code == 200){
						$that.cart_shoppings = item.count;
						if($that.cart_shoppings > 0){
							uni.setTabBarBadge({
								index: 1,
								text: $that.cart_shoppings + ''
							});
						}
					}
				});
			}
		}
	}
</script>

<style>
	.container {
		font-size: 14px;
		line-height: 24px;
		width: 100%;
		height: 100vh;
	}
	.go-search {
		width: 700upx;
	}
	.go-white{
		background-color: #FFFFFF;
	}
	.left-tabs {
		width: 180upx;
		height: 100%;
		background-color: #F7F7F7;
	}
	.right-goods {
		flex: 1;
		height: 100%;
		padding: 12upx;
		background-color: #FDFDFD;
	}
	.go-tab {
		width: 100%;
		text-align: center;
		line-height: 130upx;
	}
	.go-tab-normal {
		background-color: #F7F7F7;
	}
	.go-tab-active {
		background-color: white;
		font-weight: bolder;
		display: flex;
		flex-direction: column;
		position: relative;
	}
	.go-tab-active > .go-tab-content:before{
	    content:"";
	    border:6upx solid #ef1e15;
	    width:0;
	    height: 22upx;
		left: 0upx;
		top: 50upx;
	    position:absolute;
	}
	.go-goods-card {
		background-color: #FFFFFF;
		box-shadow: 1px #CCCCCC;
	}
	.go-bottom-box {
		overflow: hidden;
	}
	.go-card-p {
		padding: 16upx;
	}
	.go-good {
		width: 100%;
		margin-bottom: 25upx;
	}
	.go-good > .go-good-header {
		width: 180upx;
	}
	.go-good-header > image {
		width: 100%;
		height: 160upx;
	}
	.go-good > .go-good-body {
		flex: 1;
		padding-left: 12upx;
	}
	.good-title {
		font-size: 25upx;
		color: black;
	}
	.goods-des {
		width: 100%;
		height: 90upx;
	}
	.goods-des > .goods-h {
		height: 45upx;
	}
	.goods-h > .nprice {
		color: red;
		font-weight: bolder;
		margin-right: 25upx;
		font-size: 35upx;
	}
	.goods-h > .oprice {
		color: #c1c1c1;
		font-size: 30upx;
		text-decoration: line-through;
	}
	.goods-border-bottom {
		border-bottom: #F7F7F7 1upx solid;
	}
	.goods-h > .sales {
		color: #c1c1c1;
		font-size: 20upx;
	}
	.goods-h > .goods-btn{
		width: 80upx;
		height: 40upx;
		display: flex;
		justify-content:flex-end;
	}
	.goods-btn > text {
		width: 40upx;
		height: 40upx;
		line-height: 40upx;
		font-weight: bolder;
		color: white;
		background-color: #d6b911;
		border-radius: 100%;
		text-align: center;
	}
	.go-ball {
		width: 80upx;
		height: 80upx;
		position:absolute;
		border-radius: 100%;
		z-index:100;
		opacity: 0.8;
		overflow: hidden;
	}
	.go-ball > image {
		width: 100%;
		height: 100%;
	}
</style>
