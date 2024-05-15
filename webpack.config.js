const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');
const path = require('path');

const wcDepMap = {
    '@woocommerce/blocks-registry': [ 'wc', 'wcBlocksRegistry' ],
    '@woocommerce/settings': [ 'wc', 'wcSettings' ],
    '@woocommerce/block-data': [ 'wc', 'wcBlocksData' ],
    '@woocommerce/shared-context': [ 'wc', 'wcSharedContext' ],
    '@woocommerce/shared-hocs': [ 'wc', 'wcSharedHocs' ],
    '@woocommerce/price-format': [ 'wc', 'priceFormat' ],
    '@woocommerce/blocks-checkout': [ 'wc', 'blocksCheckout' ],
};

const wcHandleMap = {
    '@woocommerce/blocks-registry': 'wc-blocks-registry',
    '@woocommerce/settings': 'wc-settings',
    '@woocommerce/block-settings': 'wc-settings',
    '@woocommerce/block-data': 'wc-blocks-data-store',
    '@woocommerce/shared-context': 'wc-shared-context',
    '@woocommerce/shared-hocs': 'wc-shared-hocs',
    '@woocommerce/price-format': 'wc-price-format',
    '@woocommerce/blocks-checkout': 'wc-blocks-checkout',
};

const requestToExternal = (request) => {
    if (wcDepMap[request]) {
        return wcDepMap[request];
    }
};

const requestToHandle = (request) => {
    if (wcHandleMap[request]) {
        return wcHandleMap[request];
    }
};

// Export configuration.
module.exports = {
    ...defaultConfig,
    entry: {
        'wc-payment-method-payline-cpt': './assets/js/payment-method/payline-cpt/index.js',
        'wc-payment-method-payline-rec': './assets/js/payment-method/payline-rec/index.js',
        'wc-payment-method-payline-nx': './assets/js/payment-method/payline-nx/index.js',
    },
    output: {
        path: path.resolve( __dirname, './build' ),
        filename: '[name].js',
    },
    plugins: [
        ...defaultConfig.plugins.filter(
            (plugin) =>
                plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
        ),
        new WooCommerceDependencyExtractionWebpackPlugin({
            requestToExternal,
            requestToHandle
        })
    ]
};