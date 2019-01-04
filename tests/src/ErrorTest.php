<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use PHPUnit_Framework_TestCase;

/**
 * Title: Sisow error test
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class ErrorTest extends PHPUnit_Framework_TestCase {
	public function test_to_string() {
		$error = new Error( '1', 'Error' );

		$string = (string) $error;

		$expected = '1 Error';

		$this->assertEquals( $expected, $string );
	}
}
