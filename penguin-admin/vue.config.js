const path = require("path");
const Webpack = require("webpack");
const WebpackBar = require('webpackbar');
const FileManagerPlugin = require('filemanager-webpack-plugin');
const ScriptExtHtmlWebpackPlugin = require('script-ext-html-webpack-plugin');
const CompressionWebpackPlugin = require('compression-webpack-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const penguin = require('./src/config/penguin');

const date = new Date();
const current = `${date.getFullYear()}${date.getMonth()+1}${date.getDate()}${date.getHours()}${date.getMinutes()}${date.getSeconds()}`;

function resolve(dir) {
  return path.join(__dirname, dir);
}

module.exports = {
  publicPath: '/',
  assetsDir: '',
  outputDir: 'dist',
  filenameHashing: true,
  lintOnSave: 'default',
  transpileDependencies: [],
  devServer: {
    hot: true,
    port: 8080,
    open: true,
    noInfo: false,
    overlay: {
      warnings: true,
      errors: true,
    }
  },
  configureWebpack(config) {
	let plugins = [
		new Webpack.ProvidePlugin({
          echarts: "echarts",
          "window.echarts": "echarts"
        }),
        new WebpackBar({
          name: `(TqbBoss)penguin-admin项目编译 =>`,
        })
	];
	if(process.env.NODE_ANALYZER === 'builder'){
	    plugins.push(new BundleAnalyzerPlugin())
	}
    return {
      resolve: {
        alias: {
          "@": resolve("src"),
          "^": resolve("src/components"),
		  "*": resolve("node_modules")
        },
      },
      plugins: plugins
    };
  },
  chainWebpack(config) {
    config.when(process.env.NODE_ENV === "development", (config) => {
      config.devtool("cheap-module-eval-source-map");
    });
    config.when(process.env.NODE_ENV !== "development", (config) => {
	  // 取消webpack打包后的包体积过大的错误提示
      config.performance.set("hints", false);
	  // 禁用devtool
      config.devtool("none");
	  // 优化runtime.js内联到index.html
      config
        .plugin("ScriptExtHtmlWebpackPlugin")
        .after("html")
        .use(ScriptExtHtmlWebpackPlugin, [{ inline: /runtime\..*\.js$/ }])
        .end();
	  // 代码分割
      config.optimization.splitChunks({
        chunks: "all",
        cacheGroups: {
          libs: {
            name: "lib-modules",
            test: /[\\/]node_modules[\\/]/,
            priority: 10,
            chunks: "initial",
          },
		  elementUI: {
		    name: "lib-iview",
		    priority: 20,
		    test: /[\\/]node_modules[\\/]_?view-design(.*)/,
		  },
          commons: {
            name: "lib-penguin",
            test: resolve("src/components"),
            minChunks: 3,
            priority: 5,
            reuseExistingChunk: true,
          },
        },
      });
	  // 提取runtime
      config.optimization.runtimeChunk("single");
	  // 追加项目备注信息
      config
        .plugin("banner")
        .use(Webpack.BannerPlugin, [{
			banner: `项目名:${penguin.project}, 版本:${penguin.version}, 来源:${penguin.git}, 作者:${penguin.author}, 地址:${penguin.site}, 声明:${penguin.statement}`
		}])
        .end();
    });
	// 压缩 40k以上进行gzip压缩
    config
      .plugin("compression")
      .use(CompressionWebpackPlugin, [
        {
          filename: "[path].gz[query]",
          algorithm: "gzip",
          test: new RegExp("\\.(html|js|css)$"),
          threshold: 40960,
          minRatio: 0.8,
        },
      ])
      .end();
	// 打包归档
    config.when(process.env.NODE_ENV === "production", (config) => {
      config
        .plugin("fileManager")
        .use(FileManagerPlugin, [
          {
            onEnd: {
              delete: ["./dist/data"],
              archive: [
                {
                  source: "./dist",
                  destination: `./dist/penguin_addmin_${current}.zip`,
                },
              ],
            },
          },
        ])
        .end();
    });
  },
  runtimeCompiler: true,
  productionSourceMap: false,
  css: {
    requireModuleExtension: true,
    sourceMap: true,
    loaderOptions: {
      scss: {
        prependData: '@import "~@/styles/app.scss";',
      }
    },
  },
};