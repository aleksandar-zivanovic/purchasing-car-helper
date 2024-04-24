// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enablePostCssLoader()
    .configureDevServerOptions(options => {
        options.historyApiFallback = {
            rewrites: [
                { from: /^\/show\/[0-9]+/, to: '/index.html' }, // Redirect all /show/* paths to index.html
                // Add more rewrite rules if needed for other routes
            ],
        };
    });

module.exports = Encore.getWebpackConfig();