<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class WC_Block_Payline_NX extends WC_Block_Abstract_Payline {

	/**
	 * @var string
	 */
	protected $name = 'payline_nx';

	/**
	 * @var string
	 */
	protected $settingsOptionName = 'woocommerce_payline_nx_settings';

	/**
	 * @var string
	 */
	protected $handle = 'wc-payment-method-payline-nx';

	/**
	 * @var class-string
	 */
	protected $gatewayClass = WC_Gateway_Payline_NX::class;
}
