<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Sisow integration
 * Description:
 * Copyright: 2005-2020 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.4
 * @since   1.0.0
 */
class Integration extends AbstractIntegration {
	/**
	 * Construct integration.
	 */
	public function __construct() {
		$this->id            = 'sisow-ideal';
		$this->name          = 'Sisow';
		$this->url           = 'https://www.sisow.nl/';
		$this->product_url   = 'https://www.sisow.nl/epay-online-betaalmogelijkheden/epay-informatie';
		$this->dashboard_url = 'https://www.sisow.nl/Sisow/iDeal/Login.aspx';
		$this->register_url  = 'https://www.sisow.nl/Sisow/iDeal/Aanmelden.aspx?r=120872';
		$this->provider      = 'sisow';
		$this->supports      = array(
			'webhook',
			'webhook_log',
			'webhook_no_config',
		);

		$this->set_manual_url( __( 'https://www.pronamic.eu/support/how-to-connect-sisow-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ) );

		\add_filter( 'pronamic_pay_return_should_redirect', array( $this, 'return_should_redirect' ), 10, 2 );
	}

	/**
	 * Filter whether or not to redirect when handling return.
	 *
	 * @param bool    $should_redirect Whether or not to redirect.
	 * @param Payment $payment         Payment.
	 *
	 * @return bool
	 */
	public function return_should_redirect( $should_redirect, Payment $payment ) {
		// Check if the request is a callback request.
		if ( filter_has_var( \INPUT_GET, 'callback' ) && filter_input( \INPUT_GET, 'callback', \FILTER_VALIDATE_BOOLEAN ) ) {
			$should_redirect = false;
		}

		// Check if the request is a notify request.
		if ( filter_has_var( \INPUT_GET, 'notify' ) && filter_input( \INPUT_GET, 'notify', \FILTER_VALIDATE_BOOLEAN ) ) {
			// Log webhook request.
			do_action( 'pronamic_pay_webhook_log_payment', $payment );

			$should_redirect = false;
		}

		return $should_redirect;
	}

	/**
	 * Get settings fields.
	 *
	 * @return array
	 */
	public function get_settings_fields() {
		$fields = array();

		// Intro.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: %s: Sisow */
				__( 'Account details are provided by %1$s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'Sisow', 'pronamic_ideal' )
			),
		);

		// Merchant ID.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'methods'  => array( 'sisow' ),
			'meta_key' => '_pronamic_gateway_sisow_merchant_id',
			'title'    => _x( 'Merchant ID', 'sisow', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'code' ),
			'tooltip'  => __( 'Merchant ID as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
		);

		// Merchant Key.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'methods'  => array( 'sisow' ),
			'meta_key' => '_pronamic_gateway_sisow_merchant_key',
			'title'    => _x( 'Merchant Key', 'sisow', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Merchant Key as mentioned at <strong>My Profile</strong> in the Sisow dashboard.', 'pronamic_ideal' ),
		);

		// Shop ID.
		$fields[] = array(
			'section'     => 'general',
			'filter'      => FILTER_SANITIZE_STRING,
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

		return $fields;
	}

	/**
	 * Get configuration.
	 *
	 * @param int $post_id Post ID.
	 * @return Config
	 */
	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_id  = $this->get_meta( $post_id, 'sisow_merchant_id' );
		$config->merchant_key = $this->get_meta( $post_id, 'sisow_merchant_key' );
		$config->shop_id      = $this->get_meta( $post_id, 'sisow_shop_id' );
		$config->mode         = $this->get_meta( $post_id, 'mode' );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		return new Gateway( $this->get_config( $post_id ) );
	}
}
