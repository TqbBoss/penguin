import Vue from 'vue'
import uView from 'uview-ui'
import App from './App.vue'

Vue.use(uView);

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
}).$mount('#app')
