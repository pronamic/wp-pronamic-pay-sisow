<?php
/**
 * Error parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use Pronamic\WordPress\Pay\Core\XML\Security;
use Pronamic\WordPress\Pay\Gateways\Sisow\Error as Sisow_Error;
use SimpleXMLElement;

/**
 * Title: Error XML parser
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class ErrorParser implements Parser {
	/**
	 * Parse the specified XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Sisow_Error(
			Security::filter( $xml->errorcode ),
			Security::filter( $xml->errormessage )
		);

		return $error;
	}
}
