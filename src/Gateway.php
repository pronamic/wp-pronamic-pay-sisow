<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Sisow gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Client.
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Constructs and initialize an Sisow gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->supports = array(
			'payment_status_request',
		);

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		$this->client = new Client( $config->merchant_id, $config->merchant_key );
		$this->client->set_test_mode( self::MODE_TEST === $config->mode );
	}

	/**
	 * Get issuers
	 *
	 * @see Core_Gateway::get_issuers()
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

	/**
	 * Get supported payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::AFTERPAY,
			PaymentMethods::BANK_TRANSFER,
			PaymentMethods::BANCONTACT,
			PaymentMethods::BELFIUS,
			PaymentMethods::BUNQ,
			PaymentMethods::IN3,
			PaymentMethods::CREDIT_CARD,
			PaymentMethods::FOCUM,
			PaymentMethods::GIROPAY,
			PaymentMethods::IDEAL,
			PaymentMethods::IDEALQR,
			PaymentMethods::KLARNA_PAY_LATER,
			PaymentMethods::PAYPAL,
			PaymentMethods::SOFORT,
		);
	}

	/**
	 * Is payment method required to start transaction?
	 *
	 * @see Core_Gateway_Gateway::payment_method_is_required()
	 */
	public function payment_method_is_required() {
		return true;
	}

	/**
	 * Start
	 *
	 * @see Core_Gateway::start()
	 *
	 * @param Payment $payment Payment.
	 */
	public function start( Payment $payment ) {
		// Order and purchase ID.
		$order_id    = $payment->get_order_id();
		$purchase_id = empty( $order_id ) ? $payment->get_id() : $order_id;

		// Maximum length for purchase ID is 16 characters, otherwise an error will occur:
		// ideal_sisow_error - purchaseid too long (16).
		$purchase_id = substr( $purchase_id, 0, 16 );

		// New transaction request.
		$transaction_request               = new TransactionRequest();
		$transaction_request->merchant_id  = $this->config->merchant_id;
		$transaction_request->shop_id      = $this->config->shop_id;
		$transaction_request->amount       = $payment->get_amount()->get_amount();
		$transaction_request->issuer_id    = $payment->get_issuer();
		$transaction_request->test_mode    = self::MODE_TEST === $this->config->mode;
		$transaction_request->description  = $payment->get_description();
		$transaction_request->billing_mail = $payment->get_email();
		$transaction_request->return_url   = $payment->get_return_url();
		$transaction_request->cancel_url   = $payment->get_return_url();
		$transaction_request->callback_url = $payment->get_return_url();
		$transaction_request->notify_url   = $payment->get_return_url();

		$transaction_request->set_purchase_id( $purchase_id );
		$transaction_request->set_entrance_code( $payment->get_entrance_code() );

		// Payment method.
		$payment_method = $payment->get_method();

		if ( is_null( $payment_method ) ) {
			$payment_method = PaymentMethods::IDEAL;
		}

		$this->set_payment_method( $payment_method );

		$transaction_request->payment = Methods::transform( $payment_method );

		if ( empty( $transaction_request->payment ) && ! empty( $payment_method ) ) {
			// Leap of faith if the WordPress payment method could not transform to a Sisow method?
			$transaction_request->payment = $payment_method;
		}

		// Additional parameters for payment method.
		switch ( $transaction_request->payment ) {
			case Methods::IDEALQR:
				$transaction_request->qrcode = true;

				break;
			case Methods::AFTERPAY:
			case Methods::CAPAYABLE:
			case Methods::FOCUM:
			case Methods::KLARNA:
				$billing_address  = $payment->get_billing_address();
				$shipping_address = $payment->get_shipping_address();
				$birth_date       = $payment->get_contact()->get_birth_date();

				$transaction_request->billing_address = array(
					'first_name'   => $billing_address->get_name()->get_first_name(),
					'last_name'    => $billing_address->get_name()->get_last_name(),
					'company'      => $billing_address->get_company_name(),
					'company_coc'  => $billing_address->get_company_coc(),
					'line_1'       => $billing_address->get_line_1(),
					'line_2'       => $billing_address->get_line_2(),
					'zip'          => $billing_address->get_zip(),
					'city'         => $billing_address->get_city(),
					'country'      => $billing_address->get_country(),
					'country_code' => $billing_address->get_country_code(),
					'phone'        => $billing_address->get_phone(),
				);

				$transaction_request->shipping_address = array(
					'first_name'   => $shipping_address->get_name()->get_first_name(),
					'last_name'    => $shipping_address->get_name()->get_last_name(),
					'company'      => $shipping_address->get_company_name(),
					'company_coc'  => $shipping_address->get_company_coc(),
					'line_1'       => $shipping_address->get_line_1(),
					'line_2'       => $shipping_address->get_line_2(),
					'zip'          => $shipping_address->get_zip(),
					'city'         => $shipping_address->get_city(),
					'country'      => $shipping_address->get_country(),
					'country_code' => $shipping_address->get_country_code(),
					'phone'        => $shipping_address->get_phone(),
					'email'        => $shipping_address->get_email(),
				);

				$transaction_request->ip_address = $payment->get_contact()->get_ip_address();
				$transaction_request->birth_date = ( $birth_date instanceof \DateTime ) ? $payment->get_contact()->get_birth_date()->format( 'ddmmYYYY' ) : null;
				$transaction_request->gender     = $payment->get_contact()->get_gender();

				$transaction_request->set_products( $payment->get_order_items() );

				break;
		}

		// Create transaction.
		$result = $this->client->create_transaction( $transaction_request );

		if ( false !== $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->issuer_url );
		} else {
			$this->error = $this->client->get_error();
		}
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		$result = $this->client->get_status( $payment->get_transaction_id() );

		if ( $result instanceof Error ) {
			$this->error = $this->client->get_error();

			return;
		}

		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return;
		}

		if ( $result instanceof Transaction ) {
			$transaction = $result;

			$payment->set_status( $transaction->status );
			$payment->set_consumer_name( $transaction->consumer_name );
			$payment->set_consumer_account_number( $transaction->consumer_account );
			$payment->set_consumer_city( $transaction->consumer_city );
		}
	}
}
