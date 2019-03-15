<?php
/**
 * Methods
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Title: iDEAL Sisow payment methods
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.1.0
 * @since   1.0.0
 */
class Methods {
	/**
	 * Indicator for 'AfterPay' payment.
	 *
	 * @var string
	 * @since 2.1.0
	 */
	const AFTERPAY = 'afterpay';

	/**
	 * Indicator for 'Belfius' payment
	 *
	 * @var string
	 */
	const BELFIUS = 'belfius';

	/**
	 * Indicator for 'Billink' payment
	 *
	 * @var string
	 */
	const BILLINK = 'billink';

	/**
	 * Indicator for 'iDEAL' payment
	 *
	 * @var string
	 */
	const IDEAL = 'ideal';

	/**
	 * Indicator for 'iDEAL QR' payment
	 *
	 * @var string
	 */
	const IDEALQR = 'idealqr';

	/**
	 * Indicator for 'bunq' payment
	 *
	 * @var string
	 * @since unreleased
	 */
	const BUNQ = 'bunq';

	/**
	 * Indicator for 'Capayable' payment.
	 *
	 * @var string
	 * @since 2.1.0
	 */
	const CAPAYABLE = 'capayable';

	/**
	 * Indicator for 'Creditcard' payment
	 *
	 * @var string
	 * @since 1.1.2
	 */
	const CREDIT_CARD = 'creditcard';

	/**
	 * Indicator for 'Achteraf betalen' payment
	 *
	 * @var string
	 */
	const ECARE = 'ecare';

	/**
	 * Indicator for 'Digitale acceptgiro' payment
	 *
	 * @var string
	 */
	const EBILL = 'ebill';

	/**
	 * Indicator for 'Focum' payment.
	 *
	 * @var string
	 * @since 2.1.0
	 */
	const FOCUM = 'focum';

	/**
	 * Indicator for 'Giropay' payment
	 *
	 * @var string
	 */
	const GIROPAY = 'giropay';

	/**
	 * Indicator for 'Klarna Factuur' payment.
	 *
	 * @var string
	 * @since 2.1.0
	 */
	const KLARNA = 'klarna';

	/**
	 * Indicator for 'Bank/giro betaling' payment
	 *
	 * @var string
	 */
	const OVERBOEKING = 'overboeking';

	/**
	 * Indicator for 'SofortBanking/DIRECTebanking' payment
	 *
	 * @var string
	 */
	const SOFORT = 'sofort';

	/**
	 * Indicator for 'Bancontact/MisterCash' payment
	 *
	 * @var string
	 */
	const MISTER_CASH = 'mistercash';

	/**
	 * Indicator for 'PayPal Express Checkout' payment
	 *
	 * @var string
	 */
	const PAYPAL_EXPRESS_CHECKOUT = 'paypalec';

	/**
	 * Indicator for 'Webshop Giftcard' payment
	 *
	 * @var string
	 */
	const WEBSHOP_GIFTCARD = 'webshop';

	/**
	 * Indicator for 'Fijn Cadeaukaart' payment
	 *
	 * @var string
	 */
	const FIJNCADEAU = 'fijncadeau';

	/**
	 * Indicator for 'Podium Cadeaukaart' payment
	 *
	 * @var string
	 */
	const PODIUM_CADEAUKAART = 'podium';

	/**
	 * Payments methods map.
	 *
	 * @var array
	 */
	private static $map = array(
		PaymentMethods::AFTERPAY         => self::AFTERPAY,
		PaymentMethods::BANCONTACT       => self::MISTER_CASH,
		PaymentMethods::BANK_TRANSFER    => self::OVERBOEKING,
		PaymentMethods::BELFIUS          => self::BELFIUS,
		PaymentMethods::BILLINK          => self::BILLINK,
		PaymentMethods::BUNQ             => self::BUNQ,
		PaymentMethods::IN3              => self::CAPAYABLE,
		PaymentMethods::CREDIT_CARD      => self::CREDIT_CARD,
		PaymentMethods::FOCUM            => self::FOCUM,
		PaymentMethods::GIROPAY          => self::GIROPAY,
		PaymentMethods::IDEAL            => self::IDEAL,
		PaymentMethods::IDEALQR          => self::IDEALQR,
		PaymentMethods::KLARNA_PAY_LATER => self::KLARNA,
		PaymentMethods::MISTER_CASH      => self::MISTER_CASH,
		PaymentMethods::PAYPAL           => self::PAYPAL_EXPRESS_CHECKOUT,
		PaymentMethods::SOFORT           => self::SOFORT,
	);

	/**
	 * Transform WordPress payment method to Sisow method.
	 *
	 * @param mixed       $payment_method Payment method.
	 * @param string|null $default        Value to return if method could not be transformed.
	 * @return string|null
	 */
	public static function transform( $payment_method, $default = null ) {
		if ( ! is_scalar( $payment_method ) ) {
			return null;
		}

		if ( isset( self::$map[ $payment_method ] ) ) {
			return self::$map[ $payment_method ];
		}

		return $default;
	}

	/**
	 * Transform Sisow method to WordPress payment method.
	 *
	 * @since unreleased
	 *
	 * @param string $method Sisow method.
	 *
	 * @return string
	 */
	public static function transform_gateway_method( $method ) {
		if ( ! is_scalar( $method ) ) {
			return null;
		}

		$payment_method = array_search( $method, self::$map, true );

		if ( ! $payment_method ) {
			return null;
		}

		return $payment_method;
	}
}
