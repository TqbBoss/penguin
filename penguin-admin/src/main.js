import Vue from 'vue'
import App from './App.vue'
import router from './router';
import axios from 'axios';
import { env_initialize } from './util'; 

Vue.config.productionTip = false;

// 首先加载app.json，该配置文件可以在项目发布后对系统配置进行覆盖
axios.get('app.json')
.then(function(response){
	// 加载环境配置
	env_initialize(response.data);
	// 初始化Vue
	new Vue({
		render: h => h(App),
		router
	}).$mount('#penguin-admin');
})
.catch(function(error){
	console.log(error);
	alert("系统错误：当前站点无法访问!");
});
