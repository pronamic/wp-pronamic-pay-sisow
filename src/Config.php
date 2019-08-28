<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: Sisow config
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Config extends GatewayConfig {
	/**
	 * Merchant ID.
	 *
	 * @var string
	 */
	public $merchant_id;

	/**
	 * Merchant key.
	 *
	 * @var string
	 */
	public $merchant_key;

	/**
	 * Shop ID.
	 *
	 * @var string
	 */
	public $shop_id;
}
