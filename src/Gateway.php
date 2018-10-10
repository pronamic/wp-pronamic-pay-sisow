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
	 * @see Core_Gateway::payment_method_is_required()
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
		$transaction_request->issuer_id    = $payment->get_issuer();
		$transaction_request->billing_mail = $payment->get_email();

		$transaction_request->set_parameters( array(
			'merchantid'   => $this->config->merchant_id,
			'shopid'       => $this->config->shop_id,
			'payment'      => Methods::transform( $payment->get_method() ),
			'purchaseid'   => substr( $purchase_id, 0, 16 ),
			'entrancecode' => $payment->get_entrance_code(),
			'amount'       => $payment->get_amount()->get_cents(),
			'description'  => substr( $payment->get_description(), 0, 32 ),
			'testmode'     => ( self::MODE_TEST === $this->config->mode ) ? 'true' : 'false',
			'returnurl'    => $payment->get_return_url(),
			'cancelurl'    => $payment->get_return_url(),
			'notifyurl'    => $payment->get_return_url(),
			'callbackurl'  => $payment->get_return_url(),
		) );

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
		}

		// Billing address.
		if ( null !== $payment->get_billing_address() ) {
			$address = $payment->get_billing_address();

			if ( null !== $address->get_name() ) {
				$name = $address->get_name();

				$transaction_request->set_parameters(
					array(
						'billing_firstname' => $name->get_first_name(),
						'billing_lastname'  => $name->get_first_name(),
					)
				);
			}

			$transaction_request->set_parameters(
				array(
					'billing_mail'        => $address->get_email(),
					'billing_company'     => $address->get_company_name(),
					'billing_coc'         => $address->get_kvk_number(),
					'billing_address1'    => $address->get_line_1(),
					'billing_address2'    => $address->get_line_2(),
					'billing_zip'         => $address->get_postal_code(),
					'billing_city'        => $address->get_city(),
					'billing_country'     => $address->get_country_name(),
					'billing_countrycode' => $address->get_country_code(),
					'billing_phone'       => $address->get_phone(),
				)
			);
		}

		// Shipping address.
		if ( null !== $payment->get_shipping_address() ) {
			$address = $payment->get_shipping_address();

			if ( null !== $address->get_name() ) {
				$name = $address->get_name();

				$transaction_request->set_parameters(
					array(
						'shipping_firstname' => $name->get_first_name(),
						'shipping_lastname'  => $name->get_first_name(),
					)
				);
			}

			$transaction_request->set_parameters(
				array(
					'shipping_mail'        => $address->get_email(),
					'shipping_company'     => $address->get_company_name(),
					'shipping_address1'    => $address->get_line_1(),
					'shipping_address2'    => $address->get_line_2(),
					'shipping_zip'         => $address->get_postal_code(),
					'shipping_city'        => $address->get_city(),
					'shipping_country'     => $address->get_country_name(),
					'shipping_countrycode' => $address->get_country_code(),
					'shipping_phone'       => $address->get_phone(),
				)
			);
		}

		// Customer.
		if ( null !== $payment->get_customer() ) {
			$customer = $payment->get_customer();

			$transaction_request->set_parameters(
				array(
					'ipaddress' => $customer->get_ip_address(),
					'gender'    => $customer->get_gender(),
				)
			);

			if ( null !== $customer->get_birth_date() ) {
				$transaction_request->set_parameter( 'birth_date', $customer->get_birth_date()->format( 'ddmmYYYY' ) );
			}
		}

		// Lines.
		if ( null !== $payment->get_lines() ) {
			$lines = $payment->get_lines();

			$x = 1;

			foreach ( $lines as $line ) {
				$net_price = ( null === $line->get_unit_price() ) ? null : $line->get_unit_price()->get_cents();
				$total     = ( null === $line->get_total_amount() ) ? null : $line->get_total_amount()->get_cents();
				$tax       = ( null === $line->get_tax_amount() ) ? null : $line->get_tax_amount()->get_cents();
				$net_total = ( $total - $tax );

				$transaction_request->set_parameters(
					array(
						'product_id_' . $x          => $line->get_id(),
						'product_description_' . $x => $line->get_description(),
						'product_quantity_' . $x    => $line->get_quantity(),
						'product_netprice_' . $x    => $net_price,
						'product_total_' . $x       => $total,
						'product_nettotal_' . $x    => $net_total,
						'product_tax_' . $x         => $tax,
						'product_taxrate_' . $x     => $line->get_tax_percentage() * 100,
					)
				);

				$x++;
			}
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
