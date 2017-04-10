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
class Pronamic_WP_Pay_Gateways_Sisow_XML_ErrorParser implements Pronamic_WP_Pay_Gateways_Sisow_XML_Parser {
	public static function parse( SimpleXMLElement $xml ) {
		$error = new Pronamic_WP_Pay_Gateways_Sisow_Error(
			Pronamic_WP_Pay_XML_Security::filter( $xml->errorcode ),
			Pronamic_WP_Pay_XML_Security::filter( $xml->errormessage )
		);

		return $error;
	}
}
