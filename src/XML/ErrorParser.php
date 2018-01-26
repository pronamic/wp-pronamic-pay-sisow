<?php
use Pronamic\WordPress\Pay\Core\XML\Security;

/**
 * Title: Error XML parser
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_XML_ErrorParser implements Pronamic_WP_Pay_Gateways_Sisow_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Pronamic_WP_Pay_Gateways_Sisow_Error(
			Security::filter( $xml->errorcode ),
			Security::filter( $xml->errormessage )
		);

		return $error;
	}
}
