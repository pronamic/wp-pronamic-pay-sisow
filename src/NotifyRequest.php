<?php
/**
 * Notify return request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow notify return request
 * Description:
 * Copyright: Copyright (c) 2019
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.4
 * @since   2.0.4
 */
class NotifyRequest extends Request {
	/**
	 * Constructor.
	 *
	 * @param string $transaction_id Transaction ID.
	 * @param string $entrance_code  Entrance code.
	 * @param string $status         Status.
	 * @param string $merchant_id    Merchant ID.
	 */
	public function __construct( $transaction_id, $entrance_code, $status, $merchant_id ) {
		parent::__construct( $merchant_id );

		$this->set_parameter( 'trxid', $transaction_id );
		$this->set_parameter( 'ec', $entrance_code );
		$this->set_parameter( 'status', $status );
	}

	/**
	 * Get signature data.
	 *
	 * @return array<int,int|string|null>
	 */
	public function get_signature_data() {
		return array(
			// Transaction ID.
			$this->get_parameter( 'trxid' ),

			// Entrance code.
			$this->get_parameter( 'ec' ),

			// Status.
			$this->get_parameter( 'status' ),

			// Merchant ID.
			$this->get_parameter( 'merchantid' ),
		);
	}
}
