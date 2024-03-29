<?php
/**
 * Invoice
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow invoice
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.1
 * @since   2.0.1
 */
class Invoice {
	/**
	 * Invoice number.
	 *
	 * @var string
	 */
	public $invoiceno;

	/**
	 * Document ID.
	 *
	 * @var string
	 */
	public $documentid;

	/**
	 * Create an string representation of this object
	 *
	 * @return string
	 */
	public function __toString() {
		return implode(
			' ',
			array(
				$this->invoiceno,
				$this->documentid,
			)
		);
	}
}
