<?php
/**
 * Transaction request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: iDEAL Sisow transaction request
 * Description:
 * Copyright: Copyright (c) 2015
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class TransactionRequest extends Request {
	/**
	 * Get signature data.
	 *
	 * @return array
	 */
	public function get_signature_data() {
		return array(
			$this->get_parameter( 'purchaseid' ),

			/*
			 * Wordt er geen gebruik gemaakt van de entrancecode dan dient er twee keer de purchaseid te worden opgenomen, u krijgt dan onderstaande volgorde.
			 * purchaseid/purchaseid/amount/shopid/merchantid/merchantkey
			 */
			null !== $this->get_parameter( 'entrancecode' ) ? $this->get_parameter( 'entrancecode' ) : $this->get_parameter( 'purchaseid' ),

			$this->get_parameter( 'amount' ),

			/*
			 * Indien er geen gebruik wordt gemaakt van de shopid dan kunt u deze weglaten uit de berekening.
			 */
			$this->get_parameter( 'shopid' ),

			$this->get_parameter( 'merchantid' ),
		);
	}
}
