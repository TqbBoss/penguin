<template>
	<view class="order-box">
		<cu-custom bgColor="bg-header" :isBack="true">
			<block slot="content">填写订单</block>
			<block slot="backText">返回</block>
		</cu-custom>
		<view class="address bg-header">
			<view class="address-card">
				<FontAwesome class="position-img" type="fa fa-2x  fa-street-view" color="#6f6f6f"></FontAwesome>
				<view class="addr">湖北省武汉市江夏区藏龙岛</view>
				<FontAwesome class="position-img" type="fa fa-2x  fa-arrow-right" color="#6f6f6f"></FontAwesome>
			</view>
		</view>
	</view>
</template>

<script>
</script>

<style lang="scss">
	page {
		background-color: #F5F5F5;
	}
	.order-box {
		.address {
			width: 100%;
			height: 100upx;
			overflow: visible;
			display: flex;
			justify-content: center;
			
			.address-card {
				width: 90%;
				height: 120upx;
				margin-top:30upx;
				background-color: #FFFFFF;
				border-radius: 20upx;
				border: #FDFDFD 1px solid;
				display: flex;
				align-items: center;
				justify-content: space-between;
				color: black;
				
				.position-img {
					margin-left: 24upx;
					margin-right: 24upx;
				}
				
				.addr {
					font-size: 12px;
					font-weight: bolder;
				}
			}
		}
	}
</style>
