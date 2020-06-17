<template>
	<block v-if="stack">
		<view class="fa-stack" :style="{fontSize: `${size}rpx`}" @tap="_onTap">
			<block v-for="(iconItem, iconIndex) in stack" :key="iconIndex">
				<FontAwesome
					:stackIndex="iconItem.stackIndex"
					:type="iconItem.type"
					:size="iconItem.size || '40rpx'"
					:color="iconItem.color || false"
					:fw="iconItem.fw || false"
					:rotate="iconItem.rotate || false"
					:flip="iconItem.flip || false"
					:spin="iconItem.spin || false"
					:pulse="iconItem.pulse || false"
					:border="iconItem.border || false"
					:pullLeft="iconItem.pullLeft || false"
					:pullRight="iconItem.pullRight || false"
					:inverse="iconItem.inverse || false">
				</FontAwesome>
			</block>
		</view>
	</block>
	<block v-else>
		<!-- #ifdef MP-WEIXIN -->
			<view
				@tap="_onTap"
				:class="[
					type,
					{'fa-fw' : fw},
					rotate ? `fa-rotate-${rotate}` : '',
					flip ? `fa-flip-${flip}` : '',
					{'fa-spin' : spin},
					{'fa-pulse' : pulse},
					{'fa-border' : border},
					{'fa-pull-left' : pullLeft},
					{'fa-pull-right' : pullRight},
					stackIndex ? `fa-stack-${stackIndex}x` : '',
					{'fa-inverse' : inverse}
				]"
				:style="[
					{fontSize: `${size}rpx`},
					color ? {color} : {}
				]">
			</view>
		<!-- #endif -->
		<!-- #ifndef MP-WEIXIN -->
			<view
				@tap="_onTap"
				:class="[
					type,
					{'fa-fw' : fw},
					{[`fa-rotate-${rotate}`] : rotate},
					{[`fa-flip-${flip}`] : flip},
					{'fa-spin' : spin},
					{'fa-pulse' : pulse},
					{'fa-border' : border},
					{'fa-pull-left' : pullLeft},
					{'fa-pull-right' : pullRight},
					{[`fa-stack-${stackIndex}x`] : stackIndex},
					{'fa-inverse' : inverse}
				]"
				:style="[
					{fontSize: `${size}rpx`},
					color ? {color} : {}
				]">
			</view>
		<!-- #endif -->
	</block>
</template>

<script>
	import FontAwesome from '@/components/Ammmm-FontAwesome/index.vue'
	export default {
		name: 'FontAwesome',
		components: {
			FontAwesome
		},
		props: {
			type: {
				type: String
			},
			size: {
				type: [String, Number],
				default: '40'
			},
			color: {
				type: [String, Boolean],
				default: false
			},
			fw: {
				type: Boolean,
				default: false
			},
			rotate: {
				type: [String, Boolean, Number],
				default: false
			},
			flip: {
				type: [String, Boolean],
				default: false
			},
			spin: {
				type: Boolean,
				default: false
			},
			pulse: {
				type: Boolean,
				default: false
			},
			border: {
				type: Boolean,
				default: false
			},
			pullLeft: {
				type: Boolean,
				default: false
			},
			pullRight: {
				type: Boolean,
				default: false
			},
			stack: {
				type: [Array, Boolean],
				default: false
			},
			stackIndex: {
				type: [String, Boolean, Number],
				default: false
			},
			inverse: {
				type: Boolean,
				default: false
			},
			opacity: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				
			};
		},
		methods: {
			_onTap() {
				this.$emit('click');
			}
		}
	}
</script>

<style lang="scss" scoped>
	@import "css/fontawesome.scss";
	@import "css/solid.scss";
	@import "css/brands.scss";
	@import "css/regular.scss";
</style>
