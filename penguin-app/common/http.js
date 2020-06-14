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
    post: function (url, data, header) {
        header = header || "application/x-www-form-urlencoded";
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
                    resolve(result)
                },
                fail: function (e) {
                    reject(e)
                }
            })
        });
    },
    get: function (url, header) {
        header = header || "application/x-www-form-urlencoded";
        url = config.APISERVER + url;
		let h = { "content-type": header }
		let token = uni.getStorageSync("penguin_token");
		if(token){
			h.Authorization = `Bearer ${token}`;
		}
        return new Promise((succ, error) => {
            uni.request({
                url: url,
                method: "GET",
                header: {
                    "content-type": header,
                    "Authorization": `Bearer ${token}`
                },
                success: function (result) {
                    succ.call(self, result.data)
                },
                fail: function (e) {
                    error.call(self, e)
                }
            });
        });
    }
};
