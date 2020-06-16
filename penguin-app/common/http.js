import config from './config.js'

export default {
	ready: function (){
		return new Promise((resolve, reject) => {
		    uni.getStorage({
		        key: "penguin_token",
		        success: function (result) {
		            resolve(result.data.length > 0);
		        },
		        fail: function (e) {
		            resolve(false);
		        }
		    })
		});
	},
	login: function (){
		return new Promise((resolve, reject) => {
		    uni.login({
		    	provider: 'weixin',
		    	success(res) {
		    		uni.getUserInfo({
		    		  provider: 'weixin',
		    		  success: function (infoRes) {
						  resolve({...infoRes, code: res.code});
		    		  },
					  fail:function(e){
					  	reject(e);
					  }
		    		});
		    	}
		    });
		});
	},
	refreshToken(){
		let $that = this;
		return new Promise((resolve, reject) => {
		    $that.login()
		    .then((res)=>{
		    	$that.post('/api/login', {
		    		"code": res.code,
		    		"encryptedData": res.encryptedData,
		    		"iv": res.iv
		    	})
		    	.then((response)=>{
		    		if(response.data.code == 200){
		    			uni.setStorageSync("penguin_token", response.data.token);
		    			uni.setStorage({
		    				key: "wx_userinfo",
		    				data: res.rawData
		    			});
		    			resolve(response.data.token);
		    		}
		    		else{
		    			reject(response.data.message);
		    		}
		    	})
		    	.catch((e)=>{
		    		reject(e);
		    	});
		    })
		    .catch((e)=>{
		    	reject(e);
		    });
		});
	},
    post: function (url, data, header) {
		let $that = this;
        header = header || "application/json";
        url = config.APISERVER + url;
        let h = { "content-type": header }
        let token = uni.getStorageSync("penguin_token");
        if(token){
        	h.Authorization = `Bearer ${token}`;
        }
        return new Promise((resolve, reject) => {
            uni.request({
                url: url,
                data: data,
                method: "POST",
                header: {
                    "content-type": header,
                    "Authorization": `Bearer ${token}`
                },
                success: function (result) {
					if(result.data.code && result.data.code === 601){
						uni.$emit('token_expire');
					}
                    resolve(result)
                },
                fail: function (e) {
                    reject(e)
                }
            })
        });
    },
    get: function (url, header) {
        header = header || "application/json";
        url = config.APISERVER + url;
		let h = { "content-type": header }
		let token = uni.getStorageSync("penguin_token");
		if(token){
			h.Authorization = `Bearer ${token}`;
		}
        return new Promise((resolve, reject) => {
            uni.request({
                url: url,
                method: "GET",
                header: {
                    "content-type": header,
                    "Authorization": `Bearer ${token}`
                },
                success: function (result) {
					if(result.data.code && result.data.code === 601){
						uni.$emit('token_expire');
					}
                    resolve(result.data)
                },
                fail: function (e) {
                    reject(e)
                }
            });
        });
    }
};
