<?php
/**
 * Client
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\ErrorParser;
use Pronamic\WordPress\Pay\Gateways\Sisow\XML\TransactionParser;
use SimpleXMLElement;
use WP_Error;

/**
 * Title: Sisow
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
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
	 * @return WP_Error
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
	 * @param string $method     Method.
	 * @param array  $parameters Parameters.
	 * @return string|WP_Error
	 */
	private function send_request( $method, array $parameters = array() ) {
		$url = self::API_URL . '/' . $method;

		return Core_Util::remote_get_body(
			$url,
			200,
			array(
				'method' => 'POST',
				'body'   => $parameters,
			)
		);
	}

	/**
	 * Parse the specified document and return parsed result.
	 *
	 * @param SimpleXMLElement $document Document.
	 * @return WP_Error|Transaction
	 */
	private function parse_document( SimpleXMLElement $document ) {
		$this->error = null;

		$name = $document->getName();

		switch ( $name ) {
			case 'errorresponse':
				$sisow_error = ErrorParser::parse( $document->error );

				$this->error = new WP_Error( 'ideal_sisow_error', $sisow_error->message, $sisow_error );

				return $sisow_error;
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
	 * @return array<int|string, string>|false
	 */
	public function get_directory() {
		$directory = false;

		if ( $this->test_mode ) {
			$directory = array( '99' => __( 'Sisow Bank (test)', 'pronamic_ideal' ) );
		} else {
			// Request.
			$result = $this->send_request( RequestMethods::DIRECTORY_REQUEST );

			if ( $result instanceof WP_Error ) {
				$this->error = $result;

				return $directory;
			}

			// XML.
			$xml = Core_Util::simplexml_load_string( $result );

			if ( is_wp_error( $xml ) ) {
				$this->error = $xml;

				return $directory;
			}

			// Parse.
			if ( $xml instanceof SimpleXMLElement ) {
				$directory = array();

				foreach ( $xml->directory->issuer as $issuer ) {
					$id   = (string) $issuer->issuerid;
					$name = (string) $issuer->issuername;

					$directory[ $id ] = $name;
				}
			}
		}

		return $directory;
	}

	/**
	 * Create an transaction with the specified parameters.
	 *
	 * @param TransactionRequest $request Transaction request.
	 * @return Transaction|false
	 */
	public function create_transaction( TransactionRequest $request ) {
		$result = false;

		// Request.
		$request->sign( $this->merchant_key );

		$response = $this->send_request( RequestMethods::TRANSACTION_REQUEST, $request->get_parameters() );

		if ( $response instanceof WP_Error ) {
			$this->error = $response;

			return $result;
		}

		// XML.
		$xml = Core_Util::simplexml_load_string( $response );

		if ( $xml instanceof WP_Error ) {
			$this->error = $xml;

			return $result;
		}

		// Parse.
		if ( $xml instanceof SimpleXMLElement ) {
			$message = $this->parse_document( $xml );

			if ( $message instanceof Transaction ) {
				$result = $message;
			}
		}

		return $result;
	}

	/**
	 * Get the status of the specified transaction ID.
	 *
	 * @param StatusRequest $request Status request object.
	 * @return Transaction|false
	 */
	public function get_status( StatusRequest $request ) {
		$status = false;

		// Request.
		$request->sign( $this->merchant_key );

		$result = $this->send_request( RequestMethods::STATUS_REQUEST, $request->get_parameters() );

		if ( $result instanceof WP_Error ) {
			$this->error = $result;

			return $status;
		}

		// XML.
		$xml = Core_Util::simplexml_load_string( $result );

		if ( $xml instanceof WP_Error ) {
			$this->error = $xml;

			return $status;
		}

		// Parse.
		if ( $xml instanceof SimpleXMLElement ) {
			$status = $this->parse_document( $xml );

			return $status;
		}

		return $status;
	}
}
