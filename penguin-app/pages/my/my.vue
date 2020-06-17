<template>
	<view>
		<cu-custom bgColor="bg-header" :isBack="false">
			<block slot="content">个人中心</block>
		</cu-custom>
		<view class="my-header my-nav">
			<view class="flex justify-center align-center">
				<template v-if="isLogin">
					<view class="my-portrait">
						<image :src="userInfo.avatarUrl" mode="scaleToFill"></image>
					</view>
					<view v-if="" class="my-user">{{userInfo.nickName}}</view>
				</template>
				<template v-else>
					<view class="my-portrait">
						<image src="../../static/images/my/nologin.jpg" mode="scaleToFill"></image>
					</view>
					<view class="my-nologin">未登入</view>
				</template>
			</view>
			<view class="my-edit">编辑</view>
		</view>
		<view class="my-box">
			<view class="my-card">
				<view class="mycard-header">
					<view class="flex align-center justify-between my-border-b">
						<view class="card-title">我的订单</view>
						<view class="card-sub">查看全部订单></view>
					</view>
				</view>
				<view class="mycard-body flex align-center justify-between">
					<view class="card-menu">
						<image src="../../static/images/my/1.png" mode="scaleToFill"></image>
						<text>待付款</text>
					</view>
					<view class="card-menu">
						<image src="../../static/images/my/2.png" mode="scaleToFill"></image>
						<text>已付款</text>
					</view>
					<view class="card-menu">
						<image src="../../static/images/my/3.png" mode="scaleToFill"></image>
						<text>已发货</text>
					</view>
				</view>
			</view>
			<view class="my-footer">
				<view class="my-footer-text">TqbBoss 技术支持</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data(){
			return {
				isLogin: false,
				userInfo: {
					nickName: "",
					avatarUrl: ""
				}
				
			};
		},
		onLoad() {
			let $that = this;
			uni.getStorage({
				key:"wx_userinfo",
				success(res) {
					console.log(res);
					let uinfo = JSON.parse(res.data);
					$that.userInfo.nickName = uinfo.nickName;
					$that.userInfo.avatarUrl = uinfo.avatarUrl;
					$that.isLogin = true;
				}
			})
		},
		methods:{
			nav(){
				uni.navigateTo({
					url:'../demo/demo'
				})
			}
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #F5F5F5;
	}
	.my-header{
		width: 100%;
		height: 180upx;
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 24upx 48upx 24upx 48upx;
	}
	.my-nologin{
		font-size: 32upx;
		font-weight: bolder;
		margin-left: 20upx;
		text-decoration: underline;
	}
	.my-portrait {
		width: 120upx;
		height: 120upx;
		background-color: #FDFDFD;
		border-radius: 100%;
		overflow: hidden;
		
		image {
			width: 100%;
			height: 100%;
		}
	}
	.my-user {
		font-size: 32upx;
		font-weight: bolder;
		margin-left: 20upx;
	}
	.my-edit {
		border: #fdfdfd 1px solid;
		padding: 4upx 12upx;
	}
	.my-box {
		width: 100%;
		padding: 24upx 48upx;
		
		.my-card{
			border: #FCFCFC 1px solid;
			border-radius: 30upx;
			background-color: white;
			box-shadow: 2px 2px 8px #CCCCCC;
			
			.mycard-header {
				padding: 48upx;
				.card-title {
					font-weight: bolder;
					font-size: 32upx;
				}
			}
			.mycard-body{
				padding: 48upx;
				padding-top: 0upx;
				
				.card-menu {
					width: 120upx;
					height: 160upx;
					
					image {
						width: 100%;
						height: 120upx;
					}
					
					text {
						margin-top: 10upx;
						height: 30upx;
						line-height: 30upx;
						text-align: center;
						display: block;
					}
				}
			}
			.card-sub {
				color: #CCCCCC;
				font-size: 26upx;
				line-height: 26upx;
			}
		}
		.my-footer{
			color: #CECECE;
			text-align: center;
			font-size: 16upx;
			margin-top: 50upx;
			display: flex;
			justify-content: center;
			align-items: center;
			
			.my-footer-text{
				padding: 4upx 12upx;
				color: #CCCCCC;
			}
		}
		.my-footer:before, .my-footer:after{
			content: "";
			flex: 1;
			border-top: #CECECE 1px solid; 
			display: inline-block;
			vertical-align: middle;
		}
	}
	.my-border-b {
		padding-bottom: 24upx;
		border-bottom: #F7F7F7 2upx solid;
	}
</style>
