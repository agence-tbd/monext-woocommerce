<?php

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../');
}

require __DIR__ . '/../../vendor/autoload.php';

//require __DIR__.'/../../../../../wp-load.php';
//require_once ABSPATH . '/../../../../wp-includes/version.php';
//require_once ABSPATH . '/../../../../wp-includes/plugin.php';
//require_once ABSPATH . '/../../../../wp-admin/includes/plugin.php';
//require_once ABSPATH . '/../../../../wp-admin/plugins.php';
//require_once 'wp-admin/includes/plugin.php';

//pas possible parce qu'il faut plein de fonction wordpress
//require_once __DIR__ . '/../../woocommerce-payline.php';


require_once __DIR__ . '/../../includes/class-wc-payline-payment-gateway.php';


//////////////////
///....MOCK....///
//////////////////
if (!function_exists('get_option')) {
    function get_option($key) {
        $defaults = [
            'woocommerce_payline_settings' => [
                'merchant_id' => 'MERCH',
                'access_key' => 'KEY',
                'environment' => 'HOMO'
            ],
            'woocommerce_payline_nx_settings' => [
                'merchant_id' => 'NX_MERCH',
                'access_key' => 'NX_KEY',
                'environment' => 'HOMO',
                'widget_integration' => 'redirection'
            ],
            'woocommerce_wrong_sdk_call_settings' => [
                'environment' => 'HOMO',
                'widget_integration' => 'redirection'
            ]
        ];
        return $defaults[$key] ?? [];
    }
}

if (!function_exists('get_plugins')) {
    function get_plugins($path = '') {
        $plugins = array (
            'payline/woocommerce-payline.php' =>
                array (
                    'WC requires at least' => '',
                    'WC tested up to' => '4.9.2',
                    'Woo' => '',
                    'Name' => 'Payline',
                    'PluginURI' => 'https://docs.payline.com/display/DT/Plugin+WooCommerce',
                    'Version' => '1.5.6',
                    'Description' => 'integrations of Payline payment solution in your WooCommerce store',
                    'Author' => 'Monext',
                    'AuthorURI' => 'http://www.monext.fr',
                    'TextDomain' => 'monext-online-woocommerce',
                    'DomainPath' => '',
                    'Network' => false,
                    'RequiresWP' => '',
                    'RequiresPHP' => '',
                    'UpdateURI' => '',
                    'RequiresPlugins' => 'woocommerce',
                    'Title' => 'Payline',
                    'AuthorName' => 'Monext',
                ),
            'woocommerce/woocommerce.php' =>
                array (
                    'WC requires at least' => '',
                    'WC tested up to' => '',
                    'Woo' => '',
                    'Name' => 'WooCommerce',
                    'PluginURI' => 'https://woocommerce.com/',
                    'Version' => '10.3.5',
                    'Description' => 'An ecommerce toolkit that helps you sell anything. Beautifully.',
                    'Author' => 'Automattic',
                    'AuthorURI' => 'https://woocommerce.com',
                    'TextDomain' => 'woocommerce',
                    'DomainPath' => '/i18n/languages/',
                    'Network' => false,
                    'RequiresWP' => '6.7',
                    'RequiresPHP' => '7.4',
                    'UpdateURI' => '',
                    'RequiresPlugins' => '',
                    'Title' => 'WooCommerce',
                    'AuthorName' => 'Automattic',
                ),
        );

        return $plugins[$path] ?? [];
    }
}

if (!function_exists('get_plugin_data')) {
    function get_plugin_data($path) {
        return [
            'Name' => 'Payline',
            'PluginURI' => 'https://docs.payline.com/display/DT/Plugin+WooCommerce',
            'Version' => '1.5.6',
            'Description' => 'integrations of Payline payment solution in your WooCommerce store',
            'Author' => 'Monext',
            'AuthorURI' => 'http://www.monext.fr',
            'TextDomain' => 'monext-online-woocommerce',
            'DomainPath' => '',
            'Network' => false,
            'RequiresWP' => '',
            'RequiresPHP' => '',
            'UpdateURI' => '',
            'RequiresPlugins' => 'woocommerce',
            'Title' => 'Payline',
            'AuthorName' => 'Monext',
        ];
    }
}

if (!function_exists('trailingslashit')) {
    function trailingslashit($string) {
        return rtrim($string, '/') . '/';
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path( $file ) {
        return trailingslashit( dirname( $file ) );
    }
}

if (!function_exists('get_bloginfo')) {
    function get_bloginfo($show) {
        return '6.8.3';
    }
}

if (!class_exists('WC_Log_Handler_File')) {
    class WC_Log_Handler_File {
        public static function get_log_file_path($name) {
            return sys_get_temp_dir() . '/payline.log';
        }
    }
}

if (!defined('WCPAYLINE_PLUGIN_PATH')) {
    define('WCPAYLINE_PLUGIN_PATH', plugin_dir_path(__FILE__));
}