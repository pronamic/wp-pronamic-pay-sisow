<?php
use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: Sisow config
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Config extends GatewayConfig {
	public $merchant_id;

	public $merchant_key;

	public $shop_id;

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Gateway';
	}
}
