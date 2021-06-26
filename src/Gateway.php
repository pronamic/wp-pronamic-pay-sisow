<?php
/**
 * Gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use Pronamic\WordPress\Pay\Banks\BankAccountDetails;
use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentLineType;

/**
 * Title: Sisow gateway
 * Description:
 * Copyright: 2005-2021 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.4
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
	 * @return array<int, array<string, array<int|string, string>>>
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_directory();

		if ( false !== $result ) {
			$groups[] = array(
				'options' => $result,
			);
		}

		return $groups;
	}

	/**
	 * Get available payment methods.
	 *
	 * @see Core_Gateway::get_available_payment_methods()
	 * @return array<int,string>|null
	 */
	public function get_available_payment_methods() {
		if ( self::MODE_TEST === $this->config->mode ) {
			return null;
		}

		$payment_methods = array();

		// Merchant request.
		$request = new MerchantRequest( $this->config->merchant_id );

		// Get merchant.
		try {
			$result = $this->client->get_merchant( $request );
		} catch ( \Exception $e ) {
			$this->error = new \WP_Error( 'sisow_error', $e->getMessage() );

			return $payment_methods;
		}

		if ( false !== $result ) {
			foreach ( $result->payments as $method ) {
				// Transform to WordPress payment methods.
				$payment_method = Methods::transform_gateway_method( $method );

				if ( $payment_method ) {
					$payment_methods[] = $payment_method;
				}
			}
		}

		/**
		 * Add active payment methods which are not returned by Sisow in merchant response.
		 *
		 * @link https://github.com/wp-pay-gateways/sisow/issues/1
		 */
		if ( false !== \array_search( PaymentMethods::IDEAL, $payment_methods, true ) ) {
			$payment_methods[] = PaymentMethods::BANCONTACT;
			$payment_methods[] = PaymentMethods::BANK_TRANSFER;
			$payment_methods[] = PaymentMethods::BELFIUS;
			$payment_methods[] = PaymentMethods::BUNQ;
			$payment_methods[] = PaymentMethods::EPS;
			$payment_methods[] = PaymentMethods::GIROPAY;
			$payment_methods[] = PaymentMethods::KBC;
			$payment_methods[] = PaymentMethods::SOFORT;

			$payment_methods = \array_unique( $payment_methods );

			// Renumber keys.
			$payment_methods = \array_values( $payment_methods );
		}

		return $payment_methods;
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 * @return array<int,string>
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
	 * @param Payment $payment Payment.
	 *
	 * @throws \Exception Throws exception on transaction error.
	 * @see Core_Gateway::start()
	 */
	public function start( Payment $payment ) {
		// Order and purchase ID.
		$order_id    = $payment->get_order_id();
		$purchase_id = strval( empty( $order_id ) ? (string) $payment->get_id() : $order_id );

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
				'amount'       => $payment->get_total_amount()->get_minor_units(),
				'description'  => substr( (string) $payment->get_description(), 0, 32 ),
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
		$customer = $payment->get_customer();

		if ( null !== $customer ) {
			$request->merge_parameters(
				array(
					'ipaddress' => $customer->get_ip_address(),
					'gender'    => $customer->get_gender(),
				)
			);

			$locale = $customer->get_locale();

			if ( null !== $locale ) {
				/*
				 * @link https://github.com/wp-pay-gateways/sisow/tree/feature/post-pay/documentation#parameter-locale
				 */
				$sisow_locale = strtoupper( substr( $locale, -2 ) );

				$request->set_parameter( 'locale', $sisow_locale );
			}

			$birth_date = $customer->get_birth_date();

			if ( null !== $birth_date ) {
				$request->set_parameter( 'birthdate', $birth_date->format( 'dmY' ) );
			}
		}

		// Billing address.
		$address = $payment->get_billing_address();

		if ( null !== $address ) {
			$name = $address->get_name();

			if ( null !== $name ) {
				$request->merge_parameters(
					array(
						'billing_firstname' => $name->get_first_name(),
						'billing_lastname'  => $name->get_last_name(),
					)
				);

				// Remove accents from first name for AfterPay.
				if ( PaymentMethods::AFTERPAY === $payment->get_method() ) {
					$request->set_parameter( 'billing_firstname', remove_accents( (string) $name->get_first_name() ) );
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
		$address = $payment->get_shipping_address();

		if ( null !== $address ) {
			$name = $address->get_name();

			if ( null !== $name ) {
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
				$net_price = null;

				$unit_price = $line->get_unit_price();

				if ( null !== $unit_price ) {
					$net_price = $unit_price->get_excluding_tax()->get_minor_units();
				}

				// Request parameters.
				$request->merge_parameters(
					array(
						'product_id_' . $x          => $product_id,
						'product_description_' . $x => $line->get_name(),
						'product_quantity_' . $x    => $line->get_quantity(),
						'product_netprice_' . $x    => $net_price,
						'product_total_' . $x       => $line->get_total_amount()->get_including_tax()->get_minor_units(),
						'product_nettotal_' . $x    => $line->get_total_amount()->get_excluding_tax()->get_minor_units(),
					)
				);

				// Tax request parameters.
				$tax_amount = $line->get_tax_amount();

				if ( null !== $tax_amount ) {
					$request->set_parameter( 'product_tax_' . $x, $tax_amount->get_minor_units() );
				}

				$tax_percentage = $line->get_total_amount()->get_tax_percentage();

				if ( null !== $tax_percentage ) {
					$request->set_parameter( 'product_taxrate_' . $x, strval( $tax_percentage * 100 ) );
				}

				$x++;
			}
		}

		// Create transaction.
		$result = $this->client->create_transaction( $request );

		if ( false !== $result ) {
			$payment->set_transaction_id( $result->id );
			$payment->set_action_url( $result->issuer_url );
		}
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		$transaction_id = (string) $payment->get_transaction_id();
		$merchant_id    = $this->config->merchant_id;

		// Process notify and callback requests for payments without transaction ID.
		if ( empty( $transaction_id ) && Core_Util::input_has_vars( \INPUT_GET, array( 'trxid', 'ec', 'status', 'sha1' ) ) ) {
			$transaction_id = \filter_input( \INPUT_GET, 'trxid' );
			$entrance_code  = \filter_input( \INPUT_GET, 'ec' );
			$status         = \filter_input( \INPUT_GET, 'status' );
			$signature      = \filter_input( \INPUT_GET, 'sha1' );

			$notify = new NotifyRequest( $transaction_id, $entrance_code, $status, $merchant_id );

			// Set status if signature validates.
			if ( $notify->get_signature( $this->config->merchant_key ) === $signature ) {
				$payment->set_status( Statuses::transform( $status ) );
			}

			return;
		}

		// Status request.
		$request = new StatusRequest(
			$transaction_id,
			$merchant_id,
			$this->config->shop_id
		);

		try {
			$result = $this->client->get_status( $request );

			if ( false === $result ) {
				return;
			}
		} catch ( \Exception $e ) {
			$this->error = new \WP_Error( 'sisow_error', $e->getMessage() );

			return;
		}

		// Set status.
		$payment->set_status( Statuses::transform( $result->status ) );

		// Set consumer details.
		$consumer_details = $payment->get_consumer_bank_details();

		if ( null === $consumer_details ) {
			$consumer_details = new BankAccountDetails();

			$payment->set_consumer_bank_details( $consumer_details );
		}

		$consumer_details->set_name( $result->consumer_name );
		$consumer_details->set_account_number( $result->consumer_account );
		$consumer_details->set_city( $result->consumer_city );
		$consumer_details->set_iban( $result->consumer_iban );
		$consumer_details->set_bic( $result->consumer_bic );
	}

	/**
	 * Create invoice.
	 *
	 * @param Payment $payment Payment.
	 *
	 * @return bool
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
		try {
			$result = $this->client->create_invoice( $request );
		} catch ( \Exception $e ) {
			$this->error = new \WP_Error( 'sisow_error', $e->getMessage() );

			return false;
		}

		if ( $result instanceof \Pronamic\WordPress\Pay\Gateways\Sisow\Invoice ) {
			$payment->set_status( Core_Statuses::SUCCESS );

			$payment->save();

			return true;
		}

		return false;
	}

	/**
	 * Cancel reservation.
	 *
	 * @param Payment $payment Payment.
	 *
	 * @return bool
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
		try {
			$result = $this->client->cancel_reservation( $request );
		} catch ( \Exception $e ) {
			$this->error = new \WP_Error( 'sisow_error', $e->getMessage() );

			return false;
		}

		if ( false === $result ) {
			return false;
		}

		if ( isset( $result->status ) ) {
			$payment->set_status( Statuses::transform( $result->status ) );

			$payment->save();

			return true;
		}

		return false;
	}
}
