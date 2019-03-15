<?php
/**
 * Merchant
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow merchant
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.2
 * @since   2.0.2
 */
class Merchant {
	/**
	 * Merchant ID.
	 *
	 * @var string
	 */
	public $merchant_id;

	/**
	 * Payment methods.
	 *
	 * @var array
	 */
	public $payments;

	/**
	 * Create an string representation of this object
	 *
	 * @return string
	 */
	public function __toString() {
		$pieces = array(
			$this->merchant_id,
			implode( ', ', $this->payments ),
		);

		$pieces = array_map( 'trim', $pieces );

		$pieces = array_filter( $pieces );

		$string = implode( PHP_EOL, $pieces );

		return $string;
	}
}
