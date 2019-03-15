<?php
/**
 * Merchant parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use Pronamic\WordPress\Pay\Core\XML\Security;
use Pronamic\WordPress\Pay\Gateways\Sisow\Merchant;
use SimpleXMLElement;

/**
 * Merchant parser
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.2
 * @since   2.0.2
 */
class MerchantParser implements Parser {
	/**
	 * Parse XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 *
	 * @return Merchant
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$merchant = new Merchant();

		if ( isset( $xml->merchantid ) ) {
			$merchant->merchant_id = Security::filter( $xml->merchantid );
		}

		if ( isset( $xml->payments->payment ) ) {
			$merchant->payments = (array) $xml->payments->payment;
		}

		return $merchant;
	}
}
