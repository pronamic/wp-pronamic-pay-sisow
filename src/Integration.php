<?php
use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Title: Sisow integration
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.1.7
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Integration extends AbstractIntegration {
	public function __construct() {
		$this->id            = 'sisow-ideal';
		$this->name          = 'Sisow';
		$this->url           = 'https://www.sisow.nl/';
		$this->product_url   = 'https://www.sisow.nl/epay-online-betaalmogelijkheden/epay-informatie';
		$this->dashboard_url = 'https://www.sisow.nl/Sisow/iDeal/Login.aspx';
		$this->provider      = 'sisow';
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory';
	}

	public function get_settings_class() {
		return 'Pronamic_WP_Pay_Gateways_Sisow_Settings';
	}

	/**
	 * Get required settings for this integration.
	 *
	 * @see https://github.com/wp-premium/gravityforms/blob/1.9.16/includes/fields/class-gf-field-multiselect.php#L21-L42
	 * @since 1.1.6
	 * @return array
	 */
	public function get_settings() {
		$settings = parent::get_settings();

		$settings[] = 'sisow';

		return $settings;
	}
}
