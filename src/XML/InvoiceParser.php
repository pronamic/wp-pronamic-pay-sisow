<?php
/**
 * Invoice parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use Pronamic\WordPress\Pay\Core\XML\Security;
use Pronamic\WordPress\Pay\Gateways\Sisow\Invoice;
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
class InvoiceParser implements Parser {
	/**
	 * Parse XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 *
	 * @return Invoice
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$invoice = new Invoice();

		if ( isset( $xml->invoiceno ) ) {
			$invoice->invoiceno = Security::filter( $xml->invoiceno );
		}

		if ( isset( $xml->documentid ) ) {
			$invoice->documentid = Security::filter( $xml->documentid );
		}

		return $invoice;
	}
}
