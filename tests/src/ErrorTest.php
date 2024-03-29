<?php
/**
 * Error test.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Sisow
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use PHPUnit\Framework\TestCase;

/**
 * Title: Sisow error test
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class ErrorTest extends TestCase {
	/**
	 * Test error to string.
	 */
	public function test_to_string() {
		$error = new Error( '1', 'Error' );

		$string = (string) $error;

		$expected = '1 Error';

		$this->assertEquals( $expected, $string );
	}
}
