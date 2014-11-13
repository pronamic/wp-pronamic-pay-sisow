<?php

/**
 * Title: Sisow gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * Constructs and initialize an Sisow gateway
	 *
	 * @param Pronamic_WP_Pay_Gateways_Sisow_Config $config
	 */
	public function __construct( Pronamic_WP_Pay_Gateways_Sisow_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		$this->client = new Pronamic_WP_Pay_Gateways_Sisow_Client( $config->merchant_id, $config->merchant_key );
		$this->client->set_test_mode( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST );
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
		return array(
			'id'       => 'pronamic_ideal_issuer_id',
			'name'     => 'pronamic_ideal_issuer_id',
			'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
			'required' => true,
			'type'     => 'select',
			'choices'  => $this->get_transient_issuers(),
		);
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 *
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data, Pronamic_Pay_Payment $payment, $payment_method = null ) {
		$order_id    = $data->get_order_id();
		$purchase_id = empty( $order_id ) ? $payment->get_id() : $order_id;

		$result = $this->client->create_transaction(
			$data->get_issuer_id(),
			$purchase_id,
			$data->get_amount(),
			$data->get_description(),
			$data->get_entrance_code(),
			add_query_arg( 'payment', $payment->get_id(), home_url( '/' ) )
		);

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
		} elseif ( false === $result ) {
			$this->error = $this->client->get_error();
		} else {
			$transaction = $result;

			$payment->set_status( $transaction->status );
			$payment->set_consumer_name( $transaction->consumer_name );
			$payment->set_consumer_account_number( $transaction->consumer_account );
			$payment->set_consumer_city( $transaction->consumer_city );
		}
	}
}
