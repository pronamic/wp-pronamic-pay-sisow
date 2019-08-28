<?php
/**
 * Status request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Pay_Util;
use Pronamic\WordPress\Pay\Payments\Items;

/**
 * Status request
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class StatusRequest extends Request {
	/**
	 * Constructs status request object.
	 *
	 * @param string      $transaction_id Transaction ID.
	 * @param string      $merchant_id    Merchant ID.
	 * @param string|null $shop_id        Shop ID.
	 */
	public function __construct( $transaction_id, $merchant_id, $shop_id = null ) {
		parent::__construct( $merchant_id, $shop_id );

		$this->set_parameter( 'trxid', $transaction_id );
	}

	/**
	 * Get signature data.
	 *
	 * @return array
	 */
	public function get_signature_data() {
		return array(
			$this->get_parameter( 'trxid' ),

			/*
			 * Indien er geen gebruik wordt gemaakt van de shopid dan kunt u deze weglaten uit de berekening.
			 */
			$this->get_parameter( 'shopid' ),

			$this->get_parameter( 'merchantid' ),

			$this->get_parameter( 'merchantkey' ),
		);
	}
}
