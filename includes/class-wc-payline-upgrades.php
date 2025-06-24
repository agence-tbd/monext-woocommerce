<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Payline_Upgrades
{

    public static function upgrade_to_1_5_5()
    {
        $oldOptionName = 'woocommerce_payline_settings';
        $newOptionName = 'woocommerce_payline_cpt_settings';
        $oldOptionValue = get_option($oldOptionName);

        if(!empty($oldOptionValue)){
            update_option( $newOptionName, $oldOptionValue );
            delete_option($oldOptionName);
        }

        $settingsKeyToClear = ["merchant_id", "access_key", "environment", "smartdisplay_parameter", "proxy_settings", "proxy_host", "proxy_port", "proxy_login", "proxy_password", "user_error_message_refused", "user_error_message_cancelled", "user_error_message_error", "error_messages",];

        $cptSettings = get_option('woocommerce_payline_cpt_settings');
        $nxSettings = get_option('woocommerce_payline_nx_settings');
        $recSettings = get_option('woocommerce_payline_rec_settings');
        foreach ($settingsKeyToClear as $settingKey){
            $newPaylineSettings[$settingKey] = $cptSettings[$settingKey];
            unset($cptSettings[$settingKey]);
            unset($nxSettings[$settingKey]);
            unset($recSettings[$settingKey]);
        }

        if(isset($cptSettings['primary_contracts']) && $cptSettings($nxSettings['primary_contracts'])){
            $cptContractList = $cptSettings['primary_contracts'];
            $cptSettings['primary_contracts'] = explode(";", $cptContractList);
        }

        if(isset($nxSettings['primary_contracts']) && is_string($nxSettings['primary_contracts'])){
            $nxContractList = $nxSettings['primary_contracts'];
            $nxSettings['primary_contracts'] = explode(";", $nxContractList);
        }

        if(isset($recSettings['primary_contracts']) && is_string($recSettings['primary_contracts'])){
            $recContractList = $recSettings['primary_contracts'];
            $recSettings['primary_contracts'] = explode(";", $recContractList);
        }

        update_option( $oldOptionName, $newPaylineSettings );
        update_option( 'woocommerce_payline_cpt_settings', $cptSettings );
        update_option( 'woocommerce_payline_nx_settings', $nxSettings );
        update_option( 'woocommerce_payline_rec_settings', $recSettings );

    }
}
