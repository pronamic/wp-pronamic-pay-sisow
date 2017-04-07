<?php

/**
 * Title: Sisow config
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Config extends Pronamic_WP_Pay_GatewayConfig {
	public $merchant_id;

	public $merchant_key;

	public $shop_id;

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Gateway';
	}
}
