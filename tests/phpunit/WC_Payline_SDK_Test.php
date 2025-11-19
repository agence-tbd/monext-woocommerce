<?php

use PHPUnit\Framework\TestCase;

class WC_Payline_SDK_Test extends TestCase
{

    // -------------------------------
    // isValidResponse()
    // -------------------------------
    /**
     * @throws ReflectionException
     */
    public function test_is_valid_response_success()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'isValidResponse');
        $ref->setAccessible(true);
        $result = ['result' => ['code' => '00000']];

        $this->assertTrue($ref->invoke(null, $result));
    }

    /**
     * @throws ReflectionException
     */
    public function test_is_valid_response_fallback()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'isValidResponse');
        $ref->setAccessible(true);
        $result = ['result' => ['code' => '12345']];

        $this->assertTrue($ref->invoke(null, $result, ['12345']));
        $this->assertFalse($ref->invoke(null, $result, ['5321']));
    }

    /**
     * @throws ReflectionException
     */
    public function test_is_valid_response_invalid()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'isValidResponse');
        $ref->setAccessible(true);
        $result = ['result' => ['code' => '99999']];

        $this->assertFalse($ref->invoke(null, $result));
    }

    // -------------------------------
    // getExtensionVersion()
    // -------------------------------
    /**
     * @throws ReflectionException
     */
    public function test_get_extension_version()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'getExtensionVersion');
        $ref->setAccessible(true);

        $version = $ref->invoke(null);
        $this->assertEquals('1.5.6', $version);
    }

    // -------------------------------
    // getMethodSettings()
    // -------------------------------
    public function test_get_method_settings_without_payment_id()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'getMethodSettings');
        $ref->setAccessible(true);

        $settings = $ref->invoke(null);
        $this->assertSame('MERCH', $settings['merchant_id']);
        $this->assertSame('KEY', $settings['access_key']);
    }

    public function test_get_method_settings_with_payment_id()
    {
        $ref = new ReflectionMethod(WC_Payline_SDK::class, 'getMethodSettings');
        $ref->setAccessible(true);

        $settings = $ref->invoke(null, 'payline_nx');
        $this->assertSame('NX_MERCH', $settings['merchant_id']);
        $this->assertSame('NX_KEY', $settings['access_key']);
        $this->assertSame('redirection', $settings['widget_integration']);
    }

    // -------------------------------
    // getSDK()
    // -------------------------------
    public function test_get_sdk_returns_instance()
    {
        $sdk = WC_Payline_SDK::getSDK();
        $this->assertInstanceOf(Payline\PaylineSDK::class, $sdk);
    }

    public function test_get_sdk_returns_null_if_credentials_missing()
    {
//        runkit7_function_redefine('get_option', '$key', 'return [];');
        $sdk = WC_Payline_SDK::getSDK('wrong_sdk_call');
        $this->assertNotNull($sdk);
        $this->markTestIncomplete(); //Parce qu'il faut reussir à avoir un cas où il n'y a pas de
    }

//    // -------------------------------
//    // getPointOfSales()
//    // -------------------------------
//    public function test_get_point_of_sales_returns_list()
//    {
//        $points = WC_Payline_SDK::getPointOfSales();
//        $this->assertIsArray($points);
//        $this->assertCount(1, $points);
//        $this->assertSame('POS1', $points[0]['label']);
//    }
//
    // -------------------------------
    // checkCredentials()
    // -------------------------------
//    public function test_check_credentials_returns_true()
//    {
//        $this->assertTrue(WC_Payline_SDK::checkCredentials());
//    }
}
