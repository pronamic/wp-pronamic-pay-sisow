<?php
/**
 * Client
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\ErrorParser;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\InvoiceParser;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\MerchantParser;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\ReservationParser;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\TransactionParser;
use SimpleXMLElement;
use WP_Error;

/**
 * Title: Sisow
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Client {
	/**
	 * Sisow REST API endpoint URL.
	 *
	 * @var string
	 */
	const API_URL = 'https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx';

	/**
	 * Sisow merchant ID.
	 *
	 * @var string
	 */
	private $merchant_id;

	/**
	 * Sisow merchant key.
	 *
	 * @var string
	 */
	private $merchant_key;

	/**
	 * Indicator to use test mode or not.
	 *
	 * @var boolean
	 */
	private $test_mode;

	/**
	 * Error.
	 *
	 * @var WP_Error|null
	 */
	private $error;

	/**
	 * Constructs and initializes a Sisow client object.
	 *
	 * @param string $merchant_id  Merchant ID.
	 * @param string $merchant_key Merchant key.
	 */
	public function __construct( $merchant_id, $merchant_key ) {
		$this->merchant_id  = $merchant_id;
		$this->merchant_key = $merchant_key;
	}

	/**
	 * Error.
	 *
	 * @return WP_Error|null
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Set test mode.
	 *
	 * @param boolean $test_mode True if test mode, false otherwise.
	 */
	public function set_test_mode( $test_mode ) {
		$this->test_mode = $test_mode;
	}

	/**
	 * Send request with the specified action and parameters.
	 *
	 * @param string       $method  Method.
	 * @param Request|null $request Request.
	 *
	 * @return false|SimpleXMLElement
	 */
	private function send_request( $method, Request $request = null ) {
		$url = self::API_URL . '/' . $method;

		if ( null !== $request ) {
			$request->sign( $this->merchant_key );
		}

		$result = Core_Util::remote_get_body(
			$url,
			200,
			array(
				'method' => 'POST',
				'body'   => ( null === $request ) ? null : $request->get_parameters(),
			)
		);

		if ( $result instanceof WP_Error ) {
			$this->error = $result;

			return false;
		}

		if ( ! is_string( $result ) ) {
			return false;
		}

		// XML.
		$xml = Core_Util::simplexml_load_string( $result );

		if ( $xml instanceof WP_Error ) {
			$this->error = $xml;

			return false;
		}

		return $xml;
	}

	/**
	 * Parse the specified document and return parsed result.
	 *
	 * @param SimpleXMLElement $document Document.
	 *
	 * @return WP_Error|Invoice|Merchant|Reservation|Transaction|Error
	 */
	private function parse_document( SimpleXMLElement $document ) {
		$this->error = null;

		$name = $document->getName();

		switch ( $name ) {
			case 'cancelreservationresponse':
				$reservation = ReservationParser::parse( $document->reservation );

				return $reservation;
			case 'checkmerchantresponse':
				$merchant = MerchantParser::parse( $document->merchant );

				return $merchant;
			case 'errorresponse':
				$sisow_error = ErrorParser::parse( $document->error );

				$this->error = new WP_Error( 'ideal_sisow_error', $sisow_error->message, $sisow_error );

				return $sisow_error;
			case 'invoiceresponse':
				$invoice = InvoiceParser::parse( $document->invoice );

				return $invoice;
			case 'transactionrequest':
				$transaction = TransactionParser::parse( $document->transaction );

				return $transaction;
			case 'statusresponse':
				$transaction = TransactionParser::parse( $document->transaction );

				return $transaction;
			default:
				return new WP_Error(
					'ideal_sisow_error',
					/* translators: %s: XML document element name */
					sprintf( __( 'Unknwon Sisow message (%s)', 'pronamic_ideal' ), $name )
				);
		}
	}

	/**
	 * Get directory.
	 *
	 * @return array|false
	 */
	public function get_directory() {
		if ( $this->test_mode ) {
			return array(
				'99' => __( 'Sisow Bank (test)', 'pronamic_ideal' ),
			);
		}

		// Request.
		$result = $this->send_request( RequestMethods::DIRECTORY_REQUEST );

		if ( false === $result ) {
			return false;
		}

		// Parse.
		$directory = array();

		foreach ( $result->directory->issuer as $issuer ) {
			$id   = (string) $issuer->issuerid;
			$name = (string) $issuer->issuername;

			$directory[ $id ] = $name;
		}

		return $directory;
	}

	/**
	 * Get merchant.
	 *
	 * @param MerchantRequest $merchant_request Merchant request.
	 *
	 * @return Merchant|bool
	 */
	public function get_merchant( MerchantRequest $merchant_request ) {
		// Request.
		$response = $this->send_request( RequestMethods::CHECK_MERCHANT_REQUEST, $merchant_request );

		if ( false === $response ) {
			return false;
		}

		// Parse.
		$message = $this->parse_document( $response );

		if ( $message instanceof Merchant ) {
			return $message;
		}

		return false;
	}

	/**
	 * Create an transaction with the specified parameters.
	 *
	 * @param TransactionRequest $request Transaction request.
	 *
	 * @return Transaction|false
	 */
	public function create_transaction( TransactionRequest $request ) {
		// Request.
		$response = $this->send_request( RequestMethods::TRANSACTION_REQUEST, $request );

		if ( false === $response ) {
			return false;
		}

		// Parse.
		$message = $this->parse_document( $response );

		if ( $message instanceof Transaction ) {
			return $message;
		}

		return false;
	}

	/**
	 * Create invoice for reservation payment.
	 *
	 * @param InvoiceRequest $request Invoice request.
	 *
	 * @return Invoice|false
	 */
	public function create_invoice( InvoiceRequest $request ) {
		// Request.
		$response = $this->send_request( RequestMethods::INVOICE_REQUEST, $request );

		if ( false === $response ) {
			return false;
		}

		// Parse.
		$message = $this->parse_document( $response );

		if ( $message instanceof Invoice ) {
			return $message;
		}

		return false;
	}

	/**
	 * Cancel reservation payment.
	 *
	 * @param CancelReservationRequest $request Reservation cancellation request.
	 *
	 * @return Reservation|false
	 */
	public function cancel_reservation( CancelReservationRequest $request ) {
		$request->set_parameter( 'shopid', null );

		// Request.
		$response = $this->send_request( RequestMethods::CANCEL_RESERVATION_REQUEST, $request );

		if ( false === $response ) {
			return false;
		}

		// Parse.
		$message = $this->parse_document( $response );

		if ( $message instanceof Reservation ) {
			return $message;
		}

		return false;
	}

	/**
	 * Get the status of the specified transaction ID.
	 *
	 * @param StatusRequest $request Status request object.
	 *
	 * @return Transaction|false
	 */
	public function get_status( StatusRequest $request ) {
		// Request.
		$response = $this->send_request( RequestMethods::STATUS_REQUEST, $request );

		if ( false === $response ) {
			return false;
		}

		// Parse.
		$message = $this->parse_document( $response );

		if ( $message instanceof Transaction ) {
			return $message;
		}

		return false;
	}
}
