<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

abstract class WC_Block_Abstract_Payline extends AbstractPaymentMethodType {

	/**
	 * @var string
	 */
	protected $settingsOptionName;

	/**
	 * @var bool
	 */
	protected $defaultStatus = false;

	/**
	 * @var string
	 */
	protected $handle;

	/**
	 * @var class-string
	 */
	protected $gatewayClass;


    protected $gateway;


	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( $this->settingsOptionName, [] );
		$this->gateway  = $this->gatewayClass;
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return filter_var( $this->get_setting( 'enabled', $this->defaultStatus ), FILTER_VALIDATE_BOOLEAN );
	}


	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		wp_register_script( $this->handle,
			WCPAYLINE_PLUGIN_URL . 'build/' . $this->handle . '.js',
			[],
			null,
			true );

		return [ $this->handle ];
	}

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     * @throws \Automattic\WooCommerce\StoreApi\Exceptions\RouteException
     */
	public function get_payment_method_data()
    {
        $payline_widget_div = '';

        if (($this->settings['widget_integration'] != 'redirection'))
        {
            /** @var WC_Abstract_Payline $gateway */
            $gateway = new $this->gateway;
            $gateway->process_scripts();

            $order_id = null;
            if ( function_exists('WC') && WC()->session && method_exists( WC()->session, 'get' ) ) {
                $order_id = WC()->session->get( 'store_api_draft_order' );
            }

            if(empty($order_id)){
                $order_id = $gateway->getNewDraftedOrderId();
            }

            if (!empty($order_id)) {
                $payline_widget_div = $gateway->getPaylineWidget($order_id);
            }
        }

        return [
            'title'       => $this->get_setting( 'title' ),
            'description' => $this->get_setting( 'description' ),
            'supports'    => $this->get_supported_features(),
            'payline_widget_div' => $payline_widget_div,
			'widget_integration' => $this->settings['widget_integration']
        ];
	}
}
