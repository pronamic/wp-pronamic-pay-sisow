<?php

class Pronamic_WP_Pay_Gateways_Sisow_UtilTest extends WP_UnitTestCase {
	function test_charachters() {
		$allowed_chars   = 'ABCabc123= %*+-./&@"\':;?()$';
		$forbidden_chars = '#!€^_{}';

		$test = $allowed_chars . $forbidden_chars;

		$result   = Pronamic_WP_Pay_Gateways_Sisow_Util::filter( $test );
		$expected = $allowed_chars;

		$this->assertEquals( $expected, $result );
	}
}
