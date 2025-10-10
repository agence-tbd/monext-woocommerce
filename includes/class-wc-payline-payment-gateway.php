<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Payline\PaylineSDK;
use Monolog\Logger;

class WC_Payline_SDK
{
    protected static $merchantSettings = null;

    /**
     * Get Payline instance
     * @return PaylineSDK|bool
     */
    public static function getSDK($paymentId = null)
    {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $pathLog = trailingslashit( dirname( WC_Log_Handler_File::get_log_file_path( 'payline' ) ) ) . trailingslashit( 'payline' );
        if (!is_dir($pathLog)) {
            @mkdir($pathLog, 0777, true);
        }
        $usedBy = [];
        $usedBy[] = 'WP '.get_bloginfo('version');

        $woocommerceinfo = get_plugins('/woocommerce');
        $usedBy[] = (!empty($woocommerceinfo)) ? current($woocommerceinfo)['Name'] .' '. current($woocommerceinfo)['Version'] : 'wooComm';
        $usedBy[] = 'v'.self::getExtensionVersion();

        $settings = self::getMethodSettings($paymentId);

        if(empty($settings['merchant_id']) || empty($settings['access_key'])) {
            return null;
        }

        $SDK = new PaylineSDK(
            $settings['merchant_id'],
            $settings['access_key'],
            $settings['proxy_host'] ?? '',
            $settings['proxy_port'] ?? '',
            $settings['proxy_login'] ?? '',
            $settings['proxy_password'] ?? '',
            $settings['environment'],
            $pathLog,
            ($settings['environment'] == PaylineSDK::ENV_HOMO) ? Logger::DEBUG : Logger::INFO
        );
        $SDK->usedBy(implode(' - ',$usedBy));

        return $SDK;
    }

    /**
     * Get all point of sales (POS) related to the current account
     * @return array
     */
    public static function getPointOfSales()
    {
        $posListForSelect = array();

        if (self::checkCredentials()
            && isset(self::$merchantSettings['listPointOfSell'])
            && is_array(self::$merchantSettings['listPointOfSell'])
            && isset(self::$merchantSettings['listPointOfSell']['pointOfSell'])
            && is_array(self::$merchantSettings['listPointOfSell']['pointOfSell'])
        )
        {
            $pointOfSell = self::$merchantSettings['listPointOfSell']['pointOfSell'];
            //Case with only one POS
            if(!empty($pointOfSell['label']) && isset($pointOfSell['contracts'])) {
                self::$merchantSettings['listPointOfSell']['pointOfSell'] = array($pointOfSell);
            }
            return self::$merchantSettings['listPointOfSell']['pointOfSell'];
        }

        return $posListForSelect;
    }

    /**
     * Get merchant settings
     * @return array
     */
    public static function getMerchantSettings()
    {
        static $merchantSettings = null;

        if ($merchantSettings === null && self::getSDK()) {
            // Get merchant settings
            $merchantSettings = self::getSDK()->getMerchantSettings(array());

            $result = (is_array($merchantSettings) && !empty($merchantSettings['result']) && self::isValidResponse($merchantSettings));
            if ($result) {
                self::$merchantSettings = $merchantSettings;
            }
        }

        return $merchantSettings;
    }

    /**
     * Check if credentials are correct
     * @return bool
     */
    public static function checkCredentials()
    {
        static $result = null;

        if ($result === null) {
            // Get merchant settings
            $merchantSettings = self::getMerchantSettings();

            $result = (is_array($merchantSettings) && !empty($merchantSettings['result']) && self::isValidResponse($merchantSettings));
            if ($result) {
                self::$merchantSettings = $merchantSettings;
            }
        }

        return $result;
    }

    /**
     * Check if an API response is valid or not (check code 00000)
     * @param array $result
     * @param array $validFallbackCodeList
     * @return bool
     */
    protected static function isValidResponse($result, $validFallbackCodeList = array())
    {
        $validClassic  = (is_array($result) && isset($result['result']['code']) && $result['result']['code'] == '00000');
        $validFallback = (is_array($result) && isset($result['result']['code']) && in_array($result['result']['code'], $validFallbackCodeList));

        return ($validClassic || $validFallback);
    }

    /**
     * Return plugin version
     * @return mixed
     */
    protected static function getExtensionVersion()
    {
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return get_plugin_data( WCPAYLINE_PLUGIN_PATH.'woocommerce-payline.php' )['Version'];
    }

    /**
     * Get global and gateway settings
     * @param $paymentId
     * @return false|mixed|null
     */
    protected static function getMethodSettings($paymentId = null)
    {
        $settings = get_option('woocommerce_payline_settings');

        if (!empty($paymentId)){
            $paymentIdSettings = get_option('woocommerce_'.$paymentId.'_settings');
            $paymentIdSettings = array_filter($paymentIdSettings);
            if(!empty($paymentIdSettings)){
                $settings = array_merge($settings, $paymentIdSettings);
            }
	}


        return $settings;
    }
}
