<?php

/**
 * Title: Sisow gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.0
 * @since 1.2.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Settings extends Pronamic_WP_Pay_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		// Sisow
		$sections['sisow'] = array(
			'title'   => __( 'Sisow', 'pronamic_ideal' ),
			'methods' => array( 'sisow' ),
		);

		return $sections;
	}

	public function fields( array $fields ) {
		// Merchant ID
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'sisow',
			'meta_key'    => '_pronamic_gateway_sisow_merchant_id',
			'title'       => _x( 'Merchant ID', 'sisow', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'code' ),
			'description' => sprintf(
				__( 'You can find your Merchant ID on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
				'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
			),
		);

		// Merchant Key
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'sisow',
			'meta_key'    => '_pronamic_gateway_sisow_merchant_key',
			'title'       => _x( 'Merchant Key', 'sisow', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'description' => sprintf(
				__( 'You can find your Merchant Key on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>.', 'pronamic_ideal' ),
				'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx'
			),
		);

		// Shop ID
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'sisow',
			'meta_key'    => '_pronamic_gateway_sisow_shop_id',
			'title'       => _x( 'Shop ID', 'sisow', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'description' => sprintf(
				__( 'You can find your Shop ID on your <a href="%s" target="_blank">Sisow account page</a> under <a href="%s" target="_blank">My profile</a>. The default is: %s.', 'pronamic_ideal' ),
				'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'https://www.sisow.nl/Sisow/Opdrachtgever/Profiel2.aspx',
				0
			),
		);

		return $fields;
	}
}
