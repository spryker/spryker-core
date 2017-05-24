/**
 * Demoshop theme comfiguration
 */

'use strict';

const path = require('path');
const cwd = process.cwd();

const R = antelope.remote('ramda');
const webpack = antelope.remote('webpack');
const ExtractTextPlugin = antelope.remote('extract-text-webpack-plugin');

let bundlesPath = path.join(__dirname, '../../../');
let guiPath = path.join(__dirname, '../../');
let guiFolder = path.basename(guiPath);

let config = {
    entry: antelope.entryPoints,
    resolve: {
        root: antelope.paths.root.concat([bundlesPath]),
        alias: {
            ZedGui: `${guiFolder}/assets/Zed/js/modules/commons`,
            ZedGuiEditorConfiguration: `${guiFolder}/assets/Zed/js/modules/editor`,
            ZedGuiModules: `${guiFolder}/assets/Zed/js/modules`
        }
    },
    resolveLoader: {
        root: antelope.paths.loaders
    },
    output: {
        path: path.join(cwd, './public/Zed'),
        filename: 'assets/js/[name].js'
    },
    module: {
        loaders: [{
            test: /\.css\??(\d*\w*=?\.?)+$/i,
            loader: ExtractTextPlugin.extract('style', 'css')
        }, {
            test: /\.scss$/i,
            loader: ExtractTextPlugin.extract('style', 'css!resolve-url!sass?sourceMap')
        }, {
            test: /\.(ttf|woff2?|eot)\??(\d*\w*=?\.?)+$/i,
            loader: 'file?name=/assets/fonts/[name].[ext]'
        }, {
            test: /\.(jpe?g|png|gif|svg)\??(\d*\w*=?\.?)+$/i,
            loader: 'file?name=/assets/img/[name].[ext]'
        }]
    },
    sassLoader: {
        includePaths: antelope.paths.loaders
    },
    plugins: [
        new webpack.optimize.CommonsChunkPlugin('spryker-zed-gui-commons', 'assets/js/spryker-zed-gui-commons.js'),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',

            // legacy provider
            SprykerAjax: `${guiFolder}/assets/Zed/js/modules/legacy/SprykerAjax`,
            SprykerAjaxCallbacks: `${guiFolder}/assets/Zed/js/modules/legacy/SprykerAjaxCallbacks`,
            SprykerAlert: `${guiFolder}/assets/Zed/js/modules/legacy/SprykerAlert`
        }),
        new ExtractTextPlugin('assets/css/[name].css', {
            allChunks: true
        }),
        new webpack.DefinePlugin({
            PRODUCTION: antelope.options.production,
            DEV: !antelope.options.production,
            WATCH: antelope.options.watch,
            'require.specified': 'require.resolve'
        })
    ],
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    },
    progress: true,
    failOnError: false,
    devtool: 'sourceMap',
    debug: antelope.options.debug,
    watch: antelope.options.watch
};

if (antelope.options.production) {
    config.plugins = config.plugins.concat([
        new webpack.optimize.UglifyJsPlugin({
            comments: false,
            sourceMap: false,
            compress: {
                warnings: false
            },
            mangle: {
                except: ['$', 'exports', 'require']
            }
        })
    ]);
}

module.exports = config;
