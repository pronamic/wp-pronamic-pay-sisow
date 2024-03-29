<?php
/**
 * Request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: iDEAL Sisow transaction request
 * Description:
 * Copyright: Copyright (c) 2015
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Request {
	/**
	 * Parameters.
	 *
	 * @var array<string,int|string|null>
	 */
	private $parameters = array();

	/**
	 * Construct request.
	 *
	 * @param string      $merchant_id    Merchant ID.
	 * @param string|null $shop_id        Shop ID.
	 */
	public function __construct( $merchant_id, $shop_id = null ) {
		$this->set_parameter( 'merchantid', $merchant_id );
		$this->set_parameter( 'shopid', $shop_id );
	}

	/**
	 * Get parameter.
	 *
	 * @param string $parameter Parameter.
	 * @return int|string|null
	 */
	public function get_parameter( $parameter ) {
		if ( isset( $this->parameters[ $parameter ] ) ) {
			return $this->parameters[ $parameter ];
		}

		return null;
	}

	/**
	 * Set parameter.
	 *
	 * @param string          $parameter Parameter.
	 * @param int|string|null $value     Value.
	 * @return void
	 */
	public function set_parameter( $parameter, $value ) {
		$this->parameters[ $parameter ] = $value;
	}

	/**
	 * Get parameters.
	 *
	 * @return array<string,int|string|null>
	 */
	public function get_parameters() {
		return $this->parameters;
	}

	/**
	 * Merge parameters.
	 *
	 * @param array<string,int|string|null> $parameters Parameters.
	 * @return void
	 */
	public function merge_parameters( $parameters ) {
		$this->parameters = array_merge( $this->parameters, $parameters );
	}

	/**
	 * Get signature data.
	 *
	 * @return array<int,int|string|null>
	 */
	public function get_signature_data() {
		return array();
	}

	/**
	 * Get signature.
	 *
	 * @param string $merchant_key Merchant key.
	 * @return string
	 */
	public function get_signature( $merchant_key ) {
		$data = $this->get_signature_data();

		$data[] = $merchant_key;

		$string = implode( '', $data );

		$signature = sha1( $string );

		return $signature;
	}

	/**
	 * Sign this request with the specified merchant key.
	 *
	 * @param string $merchant_key Merchant key.
	 * @return void
	 */
	public function sign( $merchant_key ) {
		$signature = $this->get_signature( $merchant_key );

		$this->set_parameter( 'sha1', $signature );
	}
}
