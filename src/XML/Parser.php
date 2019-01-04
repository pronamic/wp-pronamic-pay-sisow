<?php
/**
 * Parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use SimpleXMLElement;

/**
 * Title: XML parser
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
interface Parser {
	/**
	 * Parse the specified XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 */
	public static function parse( SimpleXMLElement $xml );
}
