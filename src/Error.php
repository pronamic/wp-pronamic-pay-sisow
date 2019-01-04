<?php
/**
 * Error
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow error
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Error {
	/**
	 * Sisow error code.
	 *
	 * @var string
	 */
	public $code;

	/**
	 * Sisow error message.
	 *
	 * @var string
	 */
	public $message;

	/**
	 * Constructs and initializes an Sisow error object.
	 *
	 * @param string $code    Code.
	 * @param string $message Message.
	 */
	public function __construct( $code, $message ) {
		$this->code    = $code;
		$this->message = $message;
	}

	/**
	 * Create an string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->code . ' ' . $this->message;
	}
}
