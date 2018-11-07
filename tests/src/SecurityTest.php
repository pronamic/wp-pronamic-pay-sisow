<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use WP_UnitTestCase;

/**
 * Title: Sisow security test
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class SecurityTest extends WP_UnitTestCase {
	public function test_transaction_request_sha1() {
		$merchant_id  = '0123456';
		$shop_id      = null;
		$merchant_key = 'b36d8259346eaddb3c03236b37ad3a1d7a67cec6';

		// http://pronamic.nl/wp-content/uploads/2013/02/sisow-rest-api-v3.2.1.pdf #page 10
		$request = new TransactionRequest( $merchant_id, $shop_id );

		$request->set_parameter( 'purchaseid', '123456789' );
		$request->set_parameter( 'entrancecode', 'uniqueentrance' );
		$request->set_parameter( 'amount', 10 * 100 );

		$sha1 = $request->get_signature( $merchant_key );

		$this->assertEquals( 'cb2461bd40ed1a77a6d837a560bfcbc3e03d6c3c', $sha1 );
	}

	public function test_status_request_sha1() {
		$transaction_id = '0050000513407955';
		$merchant_id    = '0123456';
		$shop_id        = null;
		$merchant_key   = 'b36d8259346eaddb3c03236b37ad3a1d7a67cec6';

		$request = new StatusRequest( $transaction_id, $merchant_id, $shop_id );

		$sha1 = $request->get_signature( $merchant_key );

		$this->assertEquals( '03fa4fda5cacfe5e2ba123a47690d99f07c6fbd1', $sha1 );
	}
}
