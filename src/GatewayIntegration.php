<?php

class Pronamic_WP_Pay_Gateways_Sisow_GatewayIntegration {
	public function __construct() {
		$this->id = 'sisow-ideal';
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory';
	}

	public function get_config_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Config';
	}

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Gateway';
	}
}
