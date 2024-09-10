<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class WC_Block_Payline_CPT extends WC_Block_Abstract_Payline {

	/**
	 * @var string
	 */
	protected $name = 'payline';

	/**
	 * @var string
	 */
	protected $settingsOptionName = 'woocommerce_payline_settings';

	/**
	 * @var string
	 */
	protected $handle = 'wc-payment-method-payline-cpt';

	/**
	 * @var class-string
	 */
	protected $gatewayClass = WC_Gateway_Payline::class;
}
