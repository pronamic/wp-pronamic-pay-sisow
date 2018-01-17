<?php

/**
 * Title: Sisow config factory
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_WP_Pay_Gateways_Sisow_Config();

		$config->merchant_id  = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_id', true );
		$config->merchant_key = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_key', true );
		$config->shop_id      = get_post_meta( $post_id, '_pronamic_gateway_sisow_shop_id', true );
		$config->mode         = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
