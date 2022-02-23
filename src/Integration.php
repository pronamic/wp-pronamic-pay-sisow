<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Sisow integration
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.4
 * @since   1.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Sisow integration.
	 *
	 * @param array<string, array<string>> $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'sisow-ideal',
				'name'          => 'Sisow',
				'url'           => 'https://www.sisow.nl/',
				'product_url'   => 'https://www.sisow.nl/epay-online-betaalmogelijkheden/epay-informatie',
				'dashboard_url' => 'https://www.sisow.nl/Sisow/iDeal/Login.aspx',
				'register_url'  => 'https://www.sisow.nl/Sisow/iDeal/Aanmelden.aspx?r=120872',
				'provider'      => 'sisow',
				'supports'      => array(
					'webhook',
					'webhook_log',
					'webhook_no_config',
				),
				'manual_url'    => \__( 'https://www.pronamic.eu/support/how-to-connect-sisow-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ),
			)
		);

		parent::__construct( $args );

		/**
		 * Filter Pronamic Pay return should redirect.
		 *
		 * @link https://github.com/wp-pay/core/blob/2.2.7/src/Plugin.php#L435-L436
		 */
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
	 * @return array<int, array<string, callable|int|string|bool|array<int|string,int|string>>>
	 */
	public function get_settings_fields() {
		$fields = array();

		// Intro.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: 1: payment provider name */
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
			/* translators: %s: default code */
			'description' => sprintf( __( 'Default: <code>%s</code>', 'pronamic_ideal' ), 0 ),
			'default'     => 0,
		);

		// Test Mode.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_VALIDATE_BOOLEAN,
			'methods'  => array( 'sisow' ),
			'meta_key' => '_pronamic_gateway_sisow_test_mode',
			'title'    => _x( 'Test Mode', 'sisow', 'pronamic_ideal' ),
			'type'     => 'checkbox',
			'label'    => _x( 'Test Mode', 'sisow', 'pronamic_ideal' ),
			'tooltip'  => __( 'This requires the option “Testen met behulp van simulator (toestaan)” to be activated in the Sisow account at ‘Mijn Profiel – page Geavanceerd’.', 'pronamic_ideal' ),
			'default'  => function( $config_id ) {
				return 'test' === \get_post_meta( $config_id, '_pronamic_gateway_mode', true );
			},
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
		
		$value = $this->get_meta( $post_id, 'sisow_test_mode' );

		if ( '' === $value ) {
			$value = ( 'test' === $this->get_meta( $post_id, 'mode' ) );
		}

		$config->test_mode = (bool) $value;

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
