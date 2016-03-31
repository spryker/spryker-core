/**
 * This file is part of Antelope frontend automation tool
 * (c) Spryker Systems GmbH
 * For full copyright and license information, please view the LICENSE file that was distributed with this source code.
 */

'use strict';

let path = require('path');
let R = require('ramda');
let globby = require('globby');
let cwd = process.cwd();

// webpack
let webpack = require('webpack');
let ExtractTextPlugin = require('extract-text-webpack-plugin');

// anchor
let anchor = globby.sync([
    '**/spryker-zed-gui-commons.entry.js'
], {
    cwd: cwd,
    nocase: true
});

let guiPath = path.join(cwd, anchor[0], '../../../../');
let bundlesPath = path.join(guiPath, '../');
let isDebug = process.argv.indexOf('--debug') > -1;
let isProduction = process.argv.indexOf('--production') > -1;

let config = {
    antelope: {},
    resolve: {
        alias: {
            ZedGui: `${path.basename(guiPath)}/assets/Zed/js/modules/commons`
        }
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
    plugins: [
        new webpack.optimize.CommonsChunkPlugin('spryker-zed-gui-commons', 'assets/js/spryker-zed-gui-commons.js'),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',

            // legacy provider
            SprykerAjax: 'Gui/assets/Zed/js/modules/legacy/SprykerAjax',
            SprykerAjaxCallbacks: 'Gui/assets/Zed/js/modules/legacy/SprykerAjaxCallbacks',
            SprykerAlert: 'Gui/assets/Zed/js/modules/legacy/SprykerAlert'
        }),
        new ExtractTextPlugin('assets/css/[name].css', {
            allChunks: true
        }),
        new webpack.DefinePlugin({
            PRODUCTION: isProduction,
            WATCH: context.has('watch'),
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
    debug: isDebug,
};

if (isProduction) {
    config.plugins = config.plugins.concat([
        new webpack.optimize.UglifyJsPlugin({
            comments: false,
            sourceMap: isDebug,
            compress: {
                warnings: isDebug
            },
            mangle: {
                except: ['$', 'exports', 'require']
            }
        })
    ]);
}

module.exports = config;
