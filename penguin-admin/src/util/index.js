import Vue from 'vue';
import './iview';
import _ from 'lodash';
import { config } from '../config/settings';
import { init_http } from './http';

export function env_initialize(appconfig){
	const merge_cfg = _.merge(config, appconfig);
	init_http(merge_cfg.server.base_url);
	Vue.prototype.$config = merge_cfg;
}