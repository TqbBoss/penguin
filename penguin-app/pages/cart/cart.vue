<template>
	<view class="cart-container flex flex-direction">
		<cu-custom bgColor="bg-header" :isBack="false">
			<block slot="content">购物车</block>
		</cu-custom>
		<view class="cart-body flex flex-direction">
			<scroll-view class="cart-items" :scroll-top="scrollTop" :scroll-y="true">
				<view class="cart-item flex justify-between align-center" v-for="(item,index) in shoppings" :key="item.id">
					<view class="item-left flex justify-between align-center">
						<evan-checkbox v-model="item.is_selected" primary-color="#F1AD01"></evan-checkbox>
						<view class="item-picture">
							<image :src="item.thumbnail" mode="scaleToFill"></image>
						</view>
					</view>
					<view class="cart-right flex flex-direction justify-between">
						<view class="right-title">{{item.name}}</view>
						<view class="flex justify-between align-center">
							<view>￥<text class="item-price">{{item.concessional_price}}</text></view>
							<view class="flex">
								<view class="cart-op op-del" @click="del(item)">一</view>
								<view class="cart-op">{{item.good_num}}</view>
								<view class="cart-op op-add" @click="add(item)">十</view>
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
			<view class="cart-settlement flex align-center justify-between">
				<view>
					<view class="cart-sum">合计: ￥<text class="cart-price">{{totalPrice}}</text>元</view>
					<view class="cart-num">共计<text>{{totalCount}}</text>件商品</view>
				</view>
				<button class="cu-btn round bg-orange" :disabled="canUse" @click="summitOrder">提交订单</button>
			</view>
		</view>
	</view>
</template>

<script>
	import http from '../../common/http.js';
	
	export default {
		data(){
			return {
				shoppings: []
			}
		},
		onShow() {
			let $that = this;
			$that.shoppings = [];
			let data = getApp().globalData.carts;
			data.forEach((item)=>{
				if(!item.thumbnail.startsWith('http')){
					item.thumbnail = this.BASE_URL +item.thumbnail;
				}
				$that.shoppings.push(item);
			});
		},
		computed:{
			canUse(){
				return this.shoppings.length === 0;
			},
			totalCount(){
				let count = 0;
				this.shoppings.forEach((it)=>{
					count += it.good_num;
				})
				return count;
			},
			totalPrice(){
				let price = 0.0;
				this.shoppings.forEach((it)=>{
					price += it.concessional_price * it.good_num;
				})
				return Math.round(price);
			}
		},
		methods:{
			add(data){
				data.good_num += 1;
			},
			del(data){
				if(data.good_num === 1){
					let $that = this;
					uni.showModal({
						content:"是否删除选购商品?",
						success(res) {
							if(res.confirm){
								let index = $that.shoppings.findIndex((item)=>{
									return item.id === data.id;
								})
								if(index >= 0){
									$that.shoppings.splice(index, 1);
								}
							}
						}
					});
				}
			},
			summitOrder(){
				uni.navigateTo({
					url:'/pages/order/order'
				})
			}
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #F5F5F5;
	}
	.cart-container {
		width: 100%;
		height: 100vh;
		
		.cart-body {
			flex: 1;
			
			.cart-items{
				flex: 1;
				padding: 48upx;
				
				.cart-item{
					height: 184upx;
					background-color: white;
					padding: 12upx;
					
					.item-left {
						width: 220upx;
						height: 150upx;
						
						.item-picture {
							width: 150upx;
							height: 100%;
							overflow: hidden;
							
							image {
								width: 100%;
								height: 100%;
							}
						}
					}
					.cart-right {
						flex: 1;
						height: 100%;
						padding: 5upx 20upx;
						
						.right-title {
							font-weight: bolder;
							font-size: 28upx;
						}
						.item-price {
							color: red;
							font-weight: bolder;
						}
						.cart-op {
							width: 50upx;
							height: 50upx;
							line-height: 50upx;
							border-radius: 100%;
							margin: 0upx 10upx;
							text-align: center;
							font-weight: bolder;
							font-size: 28upx;
						}
						.op-del {
							color: #CCCCCC;
							border: #CCCCCC 1px solid;
						}
						.op-add {
							color: white;
							background-color: #F58707;
						}
					}
				}
			}
			.cart-settlement {
				width: 100%;
				height: 100upx;
				background-color: white;
				padding: 10upx 20upx;
			}
			.cart-sum {
				font-weight: bolder;
				font-size: 30upx;
				
				.cart-price {
					color: #F81D10;
					margin: 0upx 12upx;
				}
			}
			.cart-num {
				font-size: 24upx;
				color: #CCCCCC;
			}
		}
	}
	.item-check{
		border-radius: 100%;
	}
	
</style>
