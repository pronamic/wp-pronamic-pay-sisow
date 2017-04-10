<?php

/**
 * Title: Sisow gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.1.7
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_Settings extends Pronamic_WP_Pay_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		// Sisow
		$sections['sisow'] = array(
			'title'       => __( 'Sisow', 'pronamic_ideal' ),
			'methods'     => array( 'sisow' ),
			'description' => sprintf(
				__( 'Account details are provided by %s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'Sisow', 'pronamic_ideal' )
			),
		);

		return $sections;
	}

	public function fields( array $fields ) {
		// Merchant ID
		$fields[] = array(
			'filter'   => FILTER_SANITIZE_STRING,
			'section'  => 'sisow',
			'methods'  => array( 'sisow' ),
			'meta_key' => '_pronamic_gateway_sisow_merchant_id',
			'title'    => _x( 'Merchant ID', 'sisow', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'code' ),
			'tooltip'  => __( 'Merchant ID as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
		);

		// Merchant Key
		$fields[] = array(
			'filter'   => FILTER_SANITIZE_STRING,
			'section'  => 'sisow',
			'methods'  => array( 'sisow' ),
			'meta_key' => '_pronamic_gateway_sisow_merchant_key',
			'title'    => _x( 'Merchant Key', 'sisow', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Merchant Key as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
		);

		// Shop ID
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'sisow',
			'methods'     => array( 'sisow' ),
			'meta_key'    => '_pronamic_gateway_sisow_shop_id',
			'title'       => _x( 'Shop ID', 'sisow', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'tooltip'     => __( 'Shop ID as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
			'description' => sprintf( __( 'Default: <code>%s</code>', 'pronamic_ideal' ), 0 ),
			'default'     => 0,
		);

		// Transaction feedback
		$fields[] = array(
			'section' => 'sisow',
			'methods' => array( 'sisow' ),
			'title'   => __( 'Transaction feedback', 'pronamic_ideal' ),
			'type'    => 'description',
			'html'    => sprintf(
				'<span class="dashicons dashicons-yes"></span> %s',
				__( 'Payment status updates will be processed without any additional configuration.', 'pronamic_ideal' )
			),
		);

		return $fields;
	}
}
