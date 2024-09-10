<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class WC_Block_Payline_REC extends WC_Block_Abstract_Payline {

	/**
	 * @var string
	 */
	protected $name = 'payline_rec';

	/**
	 * @var string
	 */
	protected $settingsOptionName = 'woocommerce_payline_rec_settings';

	/**
	 * @var string
	 */
	protected $handle = 'wc-payment-method-payline-rec';

	/**
	 * @var class-string
	 */
	protected $gatewayClass = WC_Gateway_Payline_REC::class;
}
