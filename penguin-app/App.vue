<script>
import Vue from 'vue';	

export default {
	onLaunch: function() {
		uni.getSystemInfo({
			success: function(e) {
				// #ifndef MP
				Vue.prototype.StatusBar = e.statusBarHeight;
				if (e.platform == 'android') {
					Vue.prototype.CustomBar = e.statusBarHeight + 50;
				} else {
					Vue.prototype.CustomBar = e.statusBarHeight + 45;
				};
				// #endif
				// #ifdef MP-WEIXIN
				Vue.prototype.StatusBar = e.statusBarHeight;
				let custom = wx.getMenuButtonBoundingClientRect();
				console.log(custom);
				Vue.prototype.Custom = custom;
				Vue.prototype.CustomBar = custom.bottom + custom.top - e.statusBarHeight;	
				// #endif
			}
		});
	},
	onShow: function() {
		uni.getStorage({
			key: "wx_userinfo",
			fail() {
				console.log("微信登入..");
				uni.login({
					provider: 'weixin',
					success(res) {
						console.log(res);
						uni.getUserInfo({
						  provider: 'weixin',
						  success: function (infoRes) {
						    uni.setStorage({
						    	key: "wx_userinfo",
								data: infoRes.rawData
						    })
						  }
						});
					}
				})
			}
		})
	},
	onHide: function() {
		console.log('App Hide');
	}
};
</script>

<style>
	@import "./lib/colorui/main.css";
	@import "./lib/colorui/icon.css"; 
	
	/* 解决头条小程序组件内引入字体不生效的问题 */
	/* #ifdef MP-TOUTIAO */
	@font-face {
		font-family: uniicons;
		src: url('/static/uni.ttf');
	}
	/* #endif */
</style>
