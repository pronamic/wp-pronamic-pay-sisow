<?php
/**
 * Transaction parser
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow\XML;

use Pronamic\WordPress\DateTime\DateTime;
use Pronamic\WordPress\Pay\Core\Util;
use Pronamic\WordPress\Pay\Core\XML\Security;
use Pronamic\WordPress\Pay\Gateways\Sisow\Transaction;
use SimpleXMLElement;

/**
 * Transaction parser
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class TransactionParser implements Parser {
	/**
	 * Parse XML element.
	 *
	 * @param SimpleXMLElement $xml XML element to parse.
	 * @return Transaction
	 */
	public static function parse( SimpleXMLElement $xml ) {
		$transaction = new Transaction();

		// Transaction request.
		if ( isset( $xml->trxid ) ) {
			$transaction->id = Security::filter( $xml->trxid );
		}

		if ( isset( $xml->issuerurl ) ) {
			$transaction->issuer_url = urldecode( Security::filter( $xml->issuerurl ) );
		}

		// Status response.
		if ( isset( $xml->status ) ) {
			$transaction->status = Security::filter( $xml->status );
		}

		if ( isset( $xml->amount ) ) {
			$transaction->amount = Util::cents_to_amount( Security::filter( $xml->amount ) );
		}

		if ( isset( $xml->purchaseid ) ) {
			$transaction->purchase_id = Security::filter( $xml->purchaseid );
		}

		if ( isset( $xml->description ) ) {
			$transaction->description = Security::filter( $xml->description );
		}

		if ( isset( $xml->entrancecode ) ) {
			$transaction->entrance_code = Security::filter( $xml->entrancecode );
		}

		if ( isset( $xml->issuerid ) ) {
			$transaction->issuer_id = Security::filter( $xml->issuerid );
		}

		if ( isset( $xml->timestamp ) ) {
			$transaction->timestamp = new DateTime( Security::filter( $xml->timestamp ) );
		}

		if ( isset( $xml->consumername ) ) {
			$transaction->consumer_name = Security::filter( $xml->consumername );
		}

		if ( isset( $xml->consumeraccount ) ) {
			$transaction->consumer_account = Security::filter( $xml->consumeraccount );
		}

		if ( isset( $xml->consumercity ) ) {
			$transaction->consumer_city = Security::filter( $xml->consumercity );
		}

		return $transaction;
	}
}
