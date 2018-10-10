<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Pay_Util;
use Pronamic\WordPress\Pay\Payments\Items;

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
	 * @var array
	 */
	private $parameters = array();

	public function get_parameter( $parameter ) {
		if ( isset( $this->parameters[ $parameter ] ) ) {
			return $this->parameters[ $parameter ];
		}

		return null;
	}

	public function set_parameter( $parameter, $value ) {
		$this->parameters[ $parameter ] = $value;
	}

	public function get_parameters() {
		return $this->parameters;
	}

	public function set_parameters( $parameters ) {
		$this->parameters = array_merge( $this->parameters, $parameters );
	}

	public function get_signature_data() {
		return array();
	}

	public function get_signature( $merchant_key ) {
		$data = $this->get_signature_data();

		$data[] = $merchant_key;

		$string = implode( '', $data );

		$signature = sha1( $string );

		return $signature;
	}

	public function sign( $merchant_key ) {
		$signature = $this->get_signature( $merchant_key );

		$this->set_parameter( 'sha1', $signature );
	}
}
