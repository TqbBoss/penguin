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
							<text class="go-card-p">推荐分类</text>
						</view>
					</view>
					<view class="tui-card-body go-card-p">
						<view class="flex flex-wrap">
							<view class="go-good flex" v-for="menu in menus" :key="menu.id">
								<view class="go-good-header">
									<image :src="menu.image" mode="scaleToFill"></image>
								</view>
								<view class="go-good-body flex flex-direction justify-between">
									<view class="good-title">{{menu.title}}</view>
									<view class="flex flex-direction goods-des">
										<view class="flex goods-h">
											<view class="nprice">￥{{menu.price}}</view>
											<view class="oprice">￥{{menu.old_price}}</view>
										</view>
										<view class="flex goods-h goods-border-bottom justify-between">
											<view class="sales">月销售量{{menu.month_sales}}件</view>
											<view :id="menu.id" class="goods-btn" @click="addToCart(menu)">
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
				menus: [{
					id: "id_1",
					title: "厄瓜多尔虾",
					price: 59.9,
					old_price: 119.8,
					month_sales: 223,
					image: '../../static/images/goods/1.jpg'
				},{
					id: "id_2",
					title: "马面鱼",
					price: 59.9,
					old_price: 119.8,
					month_sales: 223,
					image: '../../static/images/goods/2.jpg'
				},{
					id: "id_3",
					title: "大海螺",
					price: 59.9,
					old_price: 119.8,
					month_sales: 223,
					image: '../../static/images/goods/3.jpg'
				},{
					id: "id_4",
					title: "红斑鱼",
					price: 59.9,
					old_price: 119.8,
					month_sales: 223,
					image: '../../static/images/goods/4.jpg'
				}],
				tabItems: [{
					name: '推荐分类',
					active: false
				},{
					name: '进口超市',
					active: true
				},{
					name: '国际名牌',
					active: false
				},{
					name: '奢侈品',
					active: false
				},{
					name: '男装',
					active: false
				},{
					name: '女装',
					active: false
				},{
					name: '童装',
					active: false
				},{
					name: '轻奢',
					active: false
				},{
					name: '手机数码',
					active: false
				},{
					name: '电脑办公',
					active: false
				},{
					name: '游戏',
					active: false
				},{
					name: '生鲜',
					active: false
				},{
					name: '水果',
					active: false
				}]
			}
		},
		onShow() {
			if(this.cart_shoppings > 0){
				uni.setTabBarBadge({
					index: 1,
					text: this.cart_shoppings + ''
				});
			}
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
			},
			addToCart(menu) {
				if(this.isAnimate){
					return;
				}
				let $that = this;
				$that.isAnimate = true;
				$that.ball.image = menu.image;
				let animation = uni.createAnimation({
					duration:150,
					timeFunction: 'ease',
					delay: 0,
					transformOrigin: '50% 50%'
				});
				let point = uni.createSelectorQuery().select('#' + menu.id);
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
