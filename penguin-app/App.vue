<script>
import Vue from 'vue';
import config from 'common/config.js';
import http from './common/http.js';

export default {
	globalData:{
		carts: [],
		count: 0
	},
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
				this.initData();
			}
		});
		uni.$on('token_expire',function(){
		    $that.getAuthUser();
		})
	},
	onHide: function() {
		let data = getApp().globalData.carts.map((item)=>{
			return {
				id: item.id,
				count: item.good_num,
				selected: item.is_selected
			}
		});
		http.post('/api/goods/cart/add?XDEBUG_SESSION_START=14330', data).then((res)=>{
			console.log(res);
		});
	},
	methods: {
		getAuthUser (){
			let $that = this;
			uni.showModal({
			    content: '请先登入',
				showCancel: false,
			    success: function (res) {
					http.refreshToken()
					.then((res)=>{
						$that.initData();
					})
					.catch((errMsg)=>{
						uni.showModal({
						    content: errMsg,
							showCancel: false,
						    success: function (e) {
						        $that.getAuthUser();
						    }
						});
					});
			    }
			});
		},
		initData(){
			let data = getApp().globalData.carts;
			http.get('/api/goods/cart/all')
			.then((res)=>{
				res.forEach((item)=>{
					this.$scope.globalData.count += item.good_num;
					data.push(item);
				})
				uni.$emit('loginOn');
			});
		}
	}
};
</script>

<style>
	@import "./lib/colorui/main.css";
	@import "./lib/colorui/icon.css"; 
	@import "./styles/app.css";
	
	/* 解决头条小程序组件内引入字体不生效的问题 */
	/* #ifdef MP-TOUTIAO */
	@font-face {
		font-family: uniicons;
		src: url('/static/uni.ttf');
	}
	/* #endif */
</style>
