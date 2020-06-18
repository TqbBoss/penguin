<template>
	<div id="app">
		<Button>Default</Button>
		<Button type="primary">Primary</Button>
		<Button type="dashed">Dashed</Button>
		<Button type="text">Text</Button>
		<br><br>
		<Button type="info">Info</Button>
		<Button type="success">Success</Button>
		<Button type="warning">Warning</Button>
		<Button type="error">Error</Button>
		<br><br>
		<i-switch v-model="switch1" />
		<i-circle :percent="80">
			<span class="demo-Circle-inner" style="font-size:24px">80%</span>
		</i-circle>
	</div>
</template>

<script>
export default {
	name: 'App',
	data() {
		return {
			switch1: true,
			time1: (new Date()).getTime() - 60 * 3 * 1000,
			time2: (new Date()).getTime() - 86400 * 3 * 1000
		}
	},
	components: {
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
