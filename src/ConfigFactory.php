<?php
/**
 * Client factory
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\GatewayConfigFactory;

/**
 * Title: Sisow config factory
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
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

		$config->merchant_id  = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_id', true );
		$config->merchant_key = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_key', true );
		$config->shop_id      = get_post_meta( $post_id, '_pronamic_gateway_sisow_shop_id', true );
		$config->mode         = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
