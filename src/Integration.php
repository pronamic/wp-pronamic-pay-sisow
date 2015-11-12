<?php

class Pronamic_WP_Pay_Gateways_Sisow_Integration {
	public function __construct() {
		$this->id            = 'sisow-ideal';
		$this->name          = 'Sisow';
		$this->url           = 'https://www.sisow.nl/';
		$this->dashboard_url = 'https://www.sisow.nl/';
		$this->provider      = 'sisow';
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory';
	}

	public function get_config_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Config';
	}

	public function get_settings_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Settings';
	}

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Gateway';
	}
}
