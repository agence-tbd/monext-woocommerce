<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Payline_Upgrades
{

    public static function upgrade_to_1_5_6()
    {
        $oldOptionName = 'woocommerce_payline_settings';
        $newOptionName = 'woocommerce_payline_cpt_settings';
        $oldOptionValue = get_option($oldOptionName);

        if(get_option($newOptionName) !== false){
            return;
        }

        $newPaylineSettings = [];

        if(!empty($oldOptionValue)){
            update_option( $newOptionName, $oldOptionValue );
            delete_option($oldOptionName);
        }

        $settingsKeyToClear = [
            "merchant_id",
            "access_key",
            "environment",
            "smartdisplay_parameter",
            "proxy_settings",
            "proxy_host",
            "proxy_port",
            "proxy_login",
            "proxy_password",
            "user_error_message_refused",
            "user_error_message_cancelled",
            "user_error_message_error",
            "error_messages",
        ];

        $cptSettings = get_option('woocommerce_payline_cpt_settings');
        $nxSettings = get_option('woocommerce_payline_nx_settings');
        $recSettings = get_option('woocommerce_payline_rec_settings');
        foreach ($settingsKeyToClear as $settingKey){
            if(empty($cptSettings[$settingKey])) {
                continue;
            }
            $newPaylineSettings[$settingKey] = $cptSettings[$settingKey];
            unset($cptSettings[$settingKey]);

            if(isset($nxSettings[$settingKey])) {
                unset($nxSettings[$settingKey]);
            }
            if(isset($recSettings[$settingKey])) {
                unset($recSettings[$settingKey]);
            }
        }

        if(isset($cptSettings['primary_contracts']) && is_string($cptSettings['primary_contracts'])) {
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

        $newPaylineSettings["enabled"] = "yes";
        //Save it the first time, to be able to call SDK in following getPointOfSales
        update_option( $oldOptionName, $newPaylineSettings, true );

        $gateway = new WC_Gateway_Payline();
        $posListForSelect = WC_Payline_SDK::getPointOfSales();
        $mainContract = $oldOptionValue['main_contract'] ?? false;
        if($posListForSelect && $mainContract) {
            foreach ($posListForSelect as $pos) {
                if (isset($pos['contracts']['contract'])
                    && is_array($pos['contracts'])
                    && is_array($pos['contracts']['contract'])
                ){
                    $contractsList = $pos['contracts']['contract'];
                    $firstKey = key($contractsList);
                    if(!is_numeric($firstKey) && isset($contractsList['contractNumber'])) {
                        $contractsList = [$contractsList];
                    }

                    foreach ($contractsList as $contract) {
                        if ($contract['contractNumber'] == $mainContract) {
                            $gateway->updatePointOfSalesList($pos['label']);
                            update_option( 'woocommerce_payline_pos_list',  serialize($posListForSelect));
                            $gateway->updateContractList($pos['contracts']);
                            $newPaylineSettings['pos'] = $pos['label'];
                            break 2;
                        }
                    }
                }
            }

        }

        //Save it the second time, to save new pos value
        update_option( $oldOptionName, $newPaylineSettings, true );
        update_option( 'woocommerce_payline_cpt_settings', $cptSettings, true );
        update_option( 'woocommerce_payline_nx_settings', $nxSettings, true );
        update_option( 'woocommerce_payline_rec_settings', $recSettings, true );

    }
}
