# penguin
tp6、uni-app、mysql、vue构建的线上小型购物app前后端

## 项目结构
名称 | 描述 | 环境要求 | 备注
:-: | :-: | :-: | :-:
penguin-server | 基于ThinkPHP6、mysql的php后台服务 | php7.1+、workerman、mysql8.0+ | 通过workerman的socket连接来实现消息推送
penguin-app | 基于uni-app跨平台解决方案构建的前端小程序 | HBuilderX2.7.9、微信开发者工具 | 目前主要优化、测试方向为微信小程序
penguin-admin | 使用vue框架构建的后台管理系统，用于商品的发布和管理 | vue-cli3.0+ | 空项目，暂未着手实现
