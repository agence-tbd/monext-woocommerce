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
	 * @var bool
	 */
	protected $defaultStatus = false;

	/**
	 * @var string
	 */
	protected $handle = 'wc-payment-method-payline-cpt';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
        parent::initialize();
		$this->gateway  = new \WC_Gateway_Payline();
	}
}
