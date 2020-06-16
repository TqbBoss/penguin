<template>
  <div id="app">
    <u-action-sheet :list="list" v-model="show"></u-action-sheet>
  </div>
</template>

<script>
import uActionSheet from "uview-ui/components/u-action-sheet/u-action-sheet.vue";
	
export default {
	name: 'App',
	data() {
		return {
			list: [{
				text: '点赞',
				color: 'blue',
				fontSize: 28
			}, {
				text: '分享'
			}, {
				text: '评论'
			}],
			show: true
		}
	},
	components: {
		uActionSheet
	}
}
</script>

<style lang="scss">
#app {
	font-family: Avenir, Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #2c3e51;
	margin-top: 60px;
}
</style>
