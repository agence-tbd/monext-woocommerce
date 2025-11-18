<?php

use PHPUnit\Framework\TestCase;

class Payline_Wallet_Test extends TestCase
{

    // -------------------------------
    // isWalletEnabled()
    // -------------------------------
    public function test_is_wallet_enable()
    {
        $func = PaylineWallet::isWalletEnabled();
        $this->assertTrue($func);
        $this->markTestIncomplete(); //This test need to be tested with an alternative mock
    }

//    public function test_is_wallet_disable(){}//Use $this->createMock to test this usecase

    // -------------------------------
    // getEnvSettingValue()
    // -------------------------------
    public function test_get_env_setting_value()
    {
        $func = PaylineWallet::getEnvSettingValue();
        $this->assertEquals('HOMO', $func);
    }

    // -------------------------------
    // addWalletEndPoint()
    // -------------------------------

    // -------------------------------
    // addUserAccountMenuItem()
    // -------------------------------
    public function test_add_user_account_menu_item()
    {
        $func = PaylineWallet::addUserAccountMenuItem(array (
            'dashboard' => 'Tableau de bord',
            'orders' => 'Commandes',
            'downloads' => 'Téléchargements',
            'edit-address' => 'Adresses',
            'edit-account' => 'Détails du compte',
            'customer-logout' => 'Se déconnecter',
        ));

        $this->assertEquals(array (
            'dashboard' => 'Tableau de bord',
            'orders' => 'Commandes',
            'downloads' => 'Téléchargements',
            'edit-address' => 'Adresses',
            'edit-account' => 'Détails du compte',
            'my-payline-wallet' => 'My Wallet',
            'customer-logout' => 'Se déconnecter',
        ), $func);
    }

    // -------------------------------
    // is_wallet_endpoint_url()
    // -------------------------------
    public function test_is_wallet_endpoint_url_false()
    {
        $ref = new ReflectionMethod(PaylineWallet::class, 'is_wallet_endpoint_url');
        $ref->setAccessible(true);
        $this->assertFalse($ref->invoke(null));
    }
    //public function test_is_wallet_endpoint_url_true(){}//Use $this->createMock to test this usecase

    // -------------------------------
    // getPageTitle()
    // -------------------------------
    public function test_get_page_title()
    {
        $func = PaylineWallet::getPageTitle('test_title', 'my-payline-wallet');
        $this->assertEquals('test_title', $func);
    }
//    public function test_get_page_title_if_is_wallet_endpoint_url(){}//Use same $this->createMock as test_is_wallet_endpoint_url_true

    // -------------------------------
    // payline_add_front_styles()
    // -------------------------------
//    public function test_payline_add_front_styles(){}//Use same $this->createMock as test_is_wallet_endpoint_url_true

    // -------------------------------
    // getPageContent()
    // -------------------------------
}