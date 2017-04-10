<?php

/**
 * Title: Sisow gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.2
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Constructs and initialize an Sisow gateway
	 *
	 * @param Pronamic_WP_Pay_Gateways_Sisow_Config $config
	 */
	public function __construct( Pronamic_WP_Pay_Gateways_Sisow_Config $config ) {
		parent::__construct( $config );

		$this->supports = array(
			'payment_status_request',
		);

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_WP_Pay_Gateways_Sisow_Client( $config->merchant_id, $config->merchant_key );
		$this->client->set_test_mode( Pronamic_IDeal_IDeal::MODE_TEST === $config->mode );
	}

	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_directory();

		if ( $result ) {
			$groups[] = array(
				'options' => $result,
			);
		} else {
			$this->error = $this->client->get_error();
		}

		return $groups;
	}

	/////////////////////////////////////////////////

	public function get_issuer_field() {
		$payment_method = $this->get_payment_method();

		if ( null === $payment_method || Pronamic_WP_Pay_PaymentMethods::IDEAL === $payment_method ) {
			return array(
				'id'       => 'pronamic_ideal_issuer_id',
				'name'     => 'pronamic_ideal_issuer_id',
				'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
				'required' => true,
				'type'     => 'select',
				'choices'  => $this->get_transient_issuers(),
			);
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Get supported payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER,
			Pronamic_WP_Pay_PaymentMethods::BANCONTACT,
			Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD,
			Pronamic_WP_Pay_PaymentMethods::IDEAL,
			Pronamic_WP_Pay_PaymentMethods::PAYPAL,
			Pronamic_WP_Pay_PaymentMethods::SOFORT,
		);
	}

	/**
	 * Is payment method required to start transaction?
	 *
	 * @see Pronamic_WP_Pay_Gateway::payment_method_is_required()
	 */
	public function payment_method_is_required() {
		return true;
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_Payment $payment ) {
		$order_id    = $payment->get_order_id();
		$purchase_id = empty( $order_id ) ? $payment->get_id() : $order_id;

		// Maximum length for purchase ID is 16 characters, otherwise an error will occur:
		// ideal_sisow_error - purchaseid too long (16)
		$purchase_id = substr( $purchase_id, 0, 16 );

		$transaction_request = new Pronamic_WP_Pay_Gateways_Sisow_TransactionRequest();
		$transaction_request->merchant_id   = $this->config->merchant_id;
		$transaction_request->shop_id       = $this->config->shop_id;

		$payment_method = $payment->get_method();

		if ( is_null( $payment_method ) ) {
			$payment_method = Pronamic_WP_Pay_PaymentMethods::IDEAL;
		}

		$this->set_payment_method( $payment_method );

		$transaction_request->payment = Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::transform( $payment_method );

		if ( empty( $transaction_request->payment ) && ! empty( $payment_method ) ) {
			// Leap of faith if the WordPress payment method could not transform to a Mollie method?
			$transaction_request->payment = $payment_method;
		}

		$transaction_request->set_purchase_id( $purchase_id );
		$transaction_request->amount        = $payment->get_amount();
		$transaction_request->issuer_id     = $payment->get_issuer();
		$transaction_request->test_mode     = Pronamic_IDeal_IDeal::MODE_TEST === $this->config->mode;
		$transaction_request->set_entrance_code( $payment->get_entrance_code() );
		$transaction_request->description   = $payment->get_description();
		$transaction_request->billing_mail  = $payment->get_email();
		$transaction_request->return_url    = $payment->get_return_url();
		$transaction_request->cancel_url    = $payment->get_return_url();
		$transaction_request->callback_url  = $payment->get_return_url();
		$transaction_request->notify_url    = $payment->get_return_url();

		$result = $this->client->create_transaction( $transaction_request );

		if ( false !== $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->issuer_url );
		} else {
			$this->error = $this->client->get_error();
		}
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$result = $this->client->get_status( $payment->get_transaction_id() );

		if ( $result instanceof Pronamic_WP_Pay_Gateways_Sisow_Error ) {
			$this->error = $this->client->get_error();

			return;
		}

		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return;
		}

		if ( $result instanceof Pronamic_WP_Pay_Gateways_Sisow_Transaction ) {
			$transaction = $result;

			$payment->set_status( $transaction->status );
			$payment->set_consumer_name( $transaction->consumer_name );
			$payment->set_consumer_account_number( $transaction->consumer_account );
			$payment->set_consumer_city( $transaction->consumer_city );
		}
	}
}
