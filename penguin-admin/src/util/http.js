import Vue from 'vue'
import axios from 'axios'
import _ from 'lodash';

export init_http = function(baseUrl){
	var instance = axios.create({baseURL: val});
	// 请求拦截器
	instance.interceptors.request.use(function (config) {
		let auth = config.headers['Authorization'];
		if(_.isEmpty(auth)){
			console.log("token 空");
		}
	    return config;
	}, function (error) {
	    // 异常通用处理
	    var msg;
	    if (error.request) {
	        msg = `请求未响应: ${error.request}`;
	    } else {
	        msg = `请求失败: ${error.message}`;
	    }
	    return Promise.reject(msg);
	});
	
	//响应拦截器
	instance.interceptors.response.use(function (config) {
	    return config.data;
	}, function (error) {
	    return Promise.reject(`请求失败: 错误码: ${error.code}	${error.message}`);
	});
	
	Vue.prototype.$http = instance;
}