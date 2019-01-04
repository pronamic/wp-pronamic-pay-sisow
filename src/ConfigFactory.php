<?php
/**
 * Client factory
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\GatewayConfigFactory;

/**
 * Title: Sisow config factory
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class ConfigFactory extends GatewayConfigFactory {
	/**
	 * Get configuration.
	 *
	 * @param int $post_id Post ID.
	 * @return Config
	 */
	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_id  = $this->get_meta( $post_id, 'sisow_merchant_id' );
		$config->merchant_key = $this->get_meta( $post_id, 'sisow_merchant_key' );
		$config->shop_id      = $this->get_meta( $post_id, 'sisow_shop_id' );
		$config->mode         = $this->get_meta( $post_id, 'mode' );

		return $config;
	}
}
