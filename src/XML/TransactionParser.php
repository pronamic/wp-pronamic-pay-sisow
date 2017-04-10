<?php

/**
 * Title: Error XML parser
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_XML_TransactionParser implements Pronamic_WP_Pay_Gateways_Sisow_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$transaction = new Pronamic_WP_Pay_Gateways_Sisow_Transaction();

		// Transaction request
		if ( isset( $xml->trxid ) ) {
			$transaction->id               = Pronamic_WP_Pay_XML_Security::filter( $xml->trxid );
		}

		if ( isset( $xml->issuerurl ) ) {
			$transaction->issuer_url       = urldecode( Pronamic_WP_Pay_XML_Security::filter( $xml->issuerurl ) );
		}

		// Status response
		if ( isset( $xml->status ) ) {
			$transaction->status           = Pronamic_WP_Pay_XML_Security::filter( $xml->status );
		}

		if ( isset( $xml->amount ) ) {
			$transaction->amount           = Pronamic_WP_Util::cents_to_amount( Pronamic_WP_Pay_XML_Security::filter( $xml->amount ) );
		}

		if ( isset( $xml->purchaseid ) ) {
			$transaction->purchase_id      = Pronamic_WP_Pay_XML_Security::filter( $xml->purchaseid );
		}

		if ( isset( $xml->description ) ) {
			$transaction->description      = Pronamic_WP_Pay_XML_Security::filter( $xml->description );
		}

		if ( isset( $xml->entrancecode ) ) {
			$transaction->entrance_code    = Pronamic_WP_Pay_XML_Security::filter( $xml->entrancecode );
		}

		if ( isset( $xml->issuerid ) ) {
			$transaction->issuer_id        = Pronamic_WP_Pay_XML_Security::filter( $xml->issuerid );
		}

		if ( isset( $xml->timestamp ) ) {
			$transaction->timestamp        = new DateTime( Pronamic_WP_Pay_XML_Security::filter( $xml->timestamp ) );
		}

		if ( isset( $xml->consumername ) ) {
			$transaction->consumer_name    = Pronamic_WP_Pay_XML_Security::filter( $xml->consumername );
		}

		if ( isset( $xml->consumeraccount ) ) {
			$transaction->consumer_account = Pronamic_WP_Pay_XML_Security::filter( $xml->consumeraccount );
		}

		if ( isset( $xml->consumercity ) ) {
			$transaction->consumer_city    = Pronamic_WP_Pay_XML_Security::filter( $xml->consumercity );
		}

		return $transaction;
	}
}
