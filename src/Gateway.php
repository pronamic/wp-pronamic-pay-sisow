<?php
/**
 * Gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Core\Statuses as Core_Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentLineType;

/**
 * Title: Sisow gateway
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.3
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

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
			'reservation_payments',
		);

		// Client.
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
	 * Get available payment methods.
	 *
	 * @see Core_Gateway::get_available_payment_methods()
	 */
	public function get_available_payment_methods() {
		if ( self::MODE_TEST === $this->config->mode ) {
			return $this->get_supported_payment_methods();
		}

		$payment_methods = array();

		// Merchant request.
		$request = new MerchantRequest( $this->config->merchant_id );

		// Get merchant.
		$result = $this->client->get_merchant( $request );

		// Handle errors.
		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return $payment_methods;
		}

		foreach ( $result->payments as $method ) {
			// Transform to WordPress payment methods.
			$payment_method = Methods::transform_gateway_method( $method );

			if ( $payment_method ) {
				$payment_methods[] = $payment_method;
			}
		}

		return $payment_methods;
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
			PaymentMethods::BILLINK,
			PaymentMethods::BUNQ,
			PaymentMethods::CAPAYABLE,
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
		$purchase_id = strval( empty( $order_id ) ? $payment->get_id() : $order_id );

		// Maximum length for purchase ID is 16 characters, otherwise an error will occur:
		// ideal_sisow_error - purchaseid too long (16).
		$purchase_id = substr( $purchase_id, 0, 16 );

		// New transaction request.
		$request = new TransactionRequest(
			$this->config->merchant_id,
			$this->config->shop_id
		);

		$request->merge_parameters(
			array(
				'payment'      => Methods::transform( $payment->get_method(), $payment->get_method() ),
				'purchaseid'   => substr( $purchase_id, 0, 16 ),
				'entrancecode' => $payment->get_entrance_code(),
				'amount'       => $payment->get_total_amount()->get_cents(),
				'description'  => substr( $payment->get_description(), 0, 32 ),
				'testmode'     => ( self::MODE_TEST === $this->config->mode ) ? 'true' : 'false',
				'returnurl'    => $payment->get_return_url(),
				'cancelurl'    => $payment->get_return_url(),
				'notifyurl'    => $payment->get_return_url(),
				'callbackurl'  => $payment->get_return_url(),
				// Other parameters.
				'issuerid'     => $payment->get_issuer(),
				'billing_mail' => $payment->get_email(),
			)
		);

		// Payment method.
		$this->set_payment_method( null === $payment->get_method() ? PaymentMethods::IDEAL : $payment->get_method() );

		// Additional parameters for payment method.
		if ( PaymentMethods::IDEALQR === $payment->get_method() ) {
			$request->set_parameter( 'qrcode', 'true' );
		}

		// Customer.
		if ( null !== $payment->get_customer() ) {
			$customer = $payment->get_customer();

			$request->merge_parameters(
				array(
					'ipaddress' => $customer->get_ip_address(),
					'gender'    => $customer->get_gender(),
				)
			);

			if ( null !== $customer->get_locale() ) {
				/*
				 * @link https://github.com/wp-pay-gateways/sisow/tree/feature/post-pay/documentation#parameter-locale
				 */
				$sisow_locale = strtoupper( substr( $customer->get_locale(), -2 ) );

				$request->set_parameter( 'locale', $sisow_locale );
			}

			if ( null !== $customer->get_birth_date() ) {
				$request->set_parameter( 'birthdate', $customer->get_birth_date()->format( 'dmY' ) );
			}
		}

		// Billing address.
		if ( null !== $payment->get_billing_address() ) {
			$address = $payment->get_billing_address();

			if ( null !== $address->get_name() ) {
				$name = $address->get_name();

				$request->merge_parameters(
					array(
						'billing_firstname' => $name->get_first_name(),
						'billing_lastname'  => $name->get_last_name(),
					)
				);

				// Remove accents from first name for AfterPay.
				if ( PaymentMethods::AFTERPAY === $payment->get_method() ) {
					$request->set_parameter( 'billing_firstname', remove_accents( $name->get_first_name() ) );
				}
			}

			$request->merge_parameters(
				array(
					'billing_mail'        => $address->get_email(),
					'billing_company'     => $address->get_company_name(),
					'billing_coc'         => $address->get_coc_number(),
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

				$request->merge_parameters(
					array(
						'shipping_firstname' => $name->get_first_name(),
						'shipping_lastname'  => $name->get_last_name(),
					)
				);
			}

			$request->merge_parameters(
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

		// Lines.
		$lines = $payment->get_lines();

		if ( null !== $lines ) {
			$x = 1;

			foreach ( $lines as $line ) {
				// Product ID.
				$product_id = $line->get_id();

				switch ( $line->get_type() ) {
					case PaymentLineType::SHIPPING:
						$product_id = 'shipping';

						break;
					case PaymentLineType::FEE:
						$product_id = 'paymentfee';

						break;
				}

				// Price.
				$unit_price = null;

				if ( null !== $line->get_unit_price() ) {
					$unit_price = $line->get_unit_price()->get_excluding_tax()->get_cents();
				}

				// Request parameters.
				$request->merge_parameters(
					array(
						'product_id_' . $x          => $product_id,
						'product_description_' . $x => $line->get_name(),
						'product_quantity_' . $x    => $line->get_quantity(),
						'product_netprice_' . $x    => $unit_price,
						'product_total_' . $x       => $line->get_total_amount()->get_including_tax()->get_cents(),
						'product_nettotal_' . $x    => $line->get_total_amount()->get_excluding_tax()->get_cents(),
					)
				);

				// Tax request parameters.
				$tax_amount = $line->get_tax_amount();

				if ( null !== $tax_amount ) {
					$request->set_parameter( 'product_tax_' . $x, $tax_amount->get_cents() );
				}

				$tax_percentage = $line->get_total_amount()->get_tax_percentage();

				if ( null !== $tax_percentage ) {
					$request->set_parameter( 'product_taxrate_' . $x, $tax_percentage * 100 );
				}

				$x++;
			}
		}

		// Create transaction.
		$result = $this->client->create_transaction( $request );

		if ( false !== $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->issuer_url );
		} else {
			$this->error = $this->client->get_error();

			return false;
		}
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		$request = new StatusRequest(
			$payment->get_transaction_id(),
			$this->config->merchant_id,
			$this->config->shop_id
		);

		$result = $this->client->get_status( $request );

		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return;
		}

		$transaction = $result;

		$payment->set_status( Statuses::transform( $transaction->status ) );
		$payment->set_consumer_name( $transaction->consumer_name );
		$payment->set_consumer_account_number( $transaction->consumer_account );
		$payment->set_consumer_city( $transaction->consumer_city );
	}

	/**
	 * Create invoice.
	 *
	 * @param Payment $payment Payment.
	 *
	 * @return bool|Invoice
	 */
	public function create_invoice( $payment ) {
		$transaction_id = $payment->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			return false;
		}

		// Invoice request.
		$request = new InvoiceRequest(
			$this->config->merchant_id,
			$this->config->shop_id
		);

		$request->set_parameter( 'trxid', $transaction_id );

		// Create invoice.
		$result = $this->client->create_invoice( $request );

		// Handle errors.
		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return false;
		}

		$payment->set_status( Core_Statuses::SUCCESS );

		$payment->save();

		return $result;
	}

	/**
	 * Cancel reservation.
	 *
	 * @param Payment $payment Payment.
	 *
	 * @return bool|Reservation
	 */
	public function cancel_reservation( $payment ) {
		$transaction_id = $payment->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			return false;
		}

		// Cancel reservation request.
		$request = new CancelReservationRequest(
			$this->config->merchant_id,
			$this->config->shop_id
		);

		$request->set_parameter( 'trxid', $transaction_id );

		// Cancel reservation.
		$result = $this->client->cancel_reservation( $request );

		// Handle errors.
		if ( false === $result ) {
			$this->error = $this->client->get_error();

			return false;
		}

		if ( isset( $result->status ) ) {
			$payment->set_status( Statuses::transform( $result->status ) );

			$payment->save();
		}

		return $result;
	}
}
