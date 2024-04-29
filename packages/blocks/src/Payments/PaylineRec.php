<?php

namespace Payline\Blocks\Payments;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;


final class PaylineRec extends AbstractPaymentMethodType {
	/**
	 * @var string
	 */
	protected $name = 'payline_rec';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_payline_rec_settings', [] );
		$this->gateway  = new \WC_Gateway_Payline_REC();
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return filter_var( $this->get_setting( 'enabled', false ), FILTER_VALIDATE_BOOLEAN );
	}


	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles()
	{
		wp_register_script( 'wc-payment-method-payline-rec',
			WCPAYLINE_PLUGIN_URL . 'packages/blocks/build/wc-payment-method-payline-rec.js',
			[],
			null,
			true );

		return [ 'wc-payment-method-payline-rec' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return [
			'title'                    => $this->get_setting( 'title' ),
			'description'              => $this->get_setting( 'description' ),
			'supports'                 => $this->get_supported_features(),
		];
	}
}
