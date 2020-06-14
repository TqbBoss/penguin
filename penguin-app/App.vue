<script>
import Vue from 'vue';
import config from 'common/config.js';
import http from './common/http.js';

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
				Vue.prototype.Custom = custom;
				Vue.prototype.CustomBar = custom.bottom + custom.top - e.statusBarHeight;	
				// #endif
				Vue.prototype.BASE_URL = config.APISERVER;
				Vue.prototype.USER_INFO = {
					nickName: '',
					avatar: '',
					cartCount: 0
				}
			}
		});
	},
	onShow: function() {
		let $that = this;
		http.ready().then((success)=>{
			if(!success){
				$that.getAuthUser();
			}
			else{
				uni.$emit('loginOn');
			}
		});
	},
	onHide: function() {
		console.log('App Hide');
	},
	methods: {
		getAuthUser (){
			let $that = this;
			uni.showModal({
			    content: '请先登入',
				showCancel: false,
			    success: function (res) {
			        http.login()
			        .then((res)=>{
						http.post('/api/login?XDEBUG_SESSION_START=13575', {
							"code": res.code,
							"encryptedData": res.encryptedData,
							"iv": res.iv
						}, 'application/json')
						.then((response)=>{
							if(response.data.code == 200){
								uni.setStorageSync("penguin_token", response.data.token);
								uni.setStorage({
									key: "wx_userinfo",
									data: res.rawData
								});
								uni.$emit('loginOn');
							}
							else{
								uni.showModal({
								    content: response.message,
									showCancel: false,
								    success: function (e) {
								        $that.getAuthUser();
								    }
								});
							}
						})
						.catch((e)=>{
							console.error(e);
							uni.showModal({
							    content: e.message,
								showCancel: false,
							    success: function (e) {
							        $that.getAuthUser();
							    }
							});
						});
			        })
			        .catch((e)=>{
			        	uni.showModal({
			        	    content: '登入失败',
			        		showCancel: false,
			        	    success: function (e) {
			        	        $that.getAuthUser();
			        	    }
			        	});
			        });
			    }
			});
		}
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
