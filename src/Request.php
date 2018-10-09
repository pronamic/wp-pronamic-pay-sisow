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
	private $parameters;

	/**
	 * Construct request.
	 */
	public function __construct() {
		$this->parameters = array();
	}

	public function set_parameter( $parameter, $value ) {
		$this->parameters[ $parameter ] = $value;
	}

	public function set_parameters( $parameters ) {
		$this->parameters = array_merge( $this->parameters, $parameters );
	}
}
