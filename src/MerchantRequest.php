<?php
/**
 * Check merchant request
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow check merchant request
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.2
 * @since   2.0.2
 */
class MerchantRequest extends Request {
	/**
	 * Get signature data.
	 *
	 * @return array
	 */
	public function get_signature_data() {
		return array(
			$this->get_parameter( 'merchantid' ),
		);
	}
}
