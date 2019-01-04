<?php
/**
 * Reservation parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use Pronamic\WordPress\Pay\Core\XML\Security;
use Pronamic\WordPress\Pay\Gateways\Sisow\Reservation;
use SimpleXMLElement;

/**
 * Reservation parser
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.1
 * @since   2.0.1
 */
class ReservationParser implements Parser {
	/**
	 * Parse XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 *
	 * @return Reservation
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$reservation = new Reservation();

		if ( isset( $xml->status ) ) {
			$reservation->status = Security::filter( $xml->status );
		}

		return $reservation;
	}
}
