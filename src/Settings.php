<?php
/**
 * Settings
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\GatewaySettings;

/**
 * Title: Sisow gateway settings
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Settings extends GatewaySettings {
	/**
	 * Construct settings.
	 */
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	/**
	 * Sections.
	 *
	 * @param array $sections Sections.
	 * @return array
	 */
	public function sections( array $sections ) {
		// Sisow.
		$sections['sisow'] = array(
			'title'       => __( 'Sisow', 'pronamic_ideal' ),
			'methods'     => array( 'sisow' ),
			'description' => sprintf(
				/* translators: %s: Sisow */
				__( 'Account details are provided by %1$s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'Sisow', 'pronamic_ideal' )
			),
		);

		return $sections;
	}

	/**
	 * Fields.
	 *
	 * @param array $fields Fields.
	 * @return array
	 */
	public function fields( array $fields ) {
		// Merchant ID.
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

		// Merchant Key.
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

		// Shop ID.
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'sisow',
			'methods'     => array( 'sisow' ),
			'meta_key'    => '_pronamic_gateway_sisow_shop_id',
			'title'       => _x( 'Shop ID', 'sisow', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'tooltip'     => __( 'Shop ID as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
			/* translators: %s: 0 */
			'description' => sprintf( __( 'Default: <code>%s</code>', 'pronamic_ideal' ), 0 ),
			'default'     => 0,
		);

		// Transaction feedback.
		$fields[] = array(
			'section' => 'sisow',
			'methods' => array( 'sisow' ),
			'title'   => __( 'Transaction feedback', 'pronamic_ideal' ),
			'type'    => 'description',
			'html'    => __( 'Payment status updates will be processed without any additional configuration.', 'pronamic_ideal' ),
		);

		return $fields;
	}
}
