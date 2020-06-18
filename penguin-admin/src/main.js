import Vue from 'vue'
import App from './App.vue'
// 全局引入view-design（非按需加载）
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUI);

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
}).$mount('#penguin-admin')
