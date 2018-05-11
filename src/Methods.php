<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Title: iDEAL Sisow payment methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Methods {
	/**
	 * Indicator for 'Belfius' payment
	 *
	 * @var string
	 */
	const BELFIUS = 'belfius';

	/**
	 * Indicator for 'iDEAL' payment
	 *
	 * @var string
	 */
	const IDEAL = '';

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
	 * Indicator for 'Giropay' payment
	 *
	 * @var string
	 */
	const GIROPAY = 'giropay';

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
		PaymentMethods::BANCONTACT    => Methods::MISTER_CASH,
		PaymentMethods::BANK_TRANSFER => Methods::OVERBOEKING,
		PaymentMethods::BELFIUS       => Methods::BELFIUS,
		PaymentMethods::BUNQ          => Methods::BUNQ,
		PaymentMethods::CREDIT_CARD   => Methods::CREDIT_CARD,
		PaymentMethods::GIROPAY       => Methods::GIROPAY,
		PaymentMethods::IDEAL         => Methods::IDEAL,
		PaymentMethods::IDEALQR       => Methods::IDEALQR,
		PaymentMethods::MISTER_CASH   => Methods::MISTER_CASH,
		PaymentMethods::PAYPAL        => Methods::PAYPAL_EXPRESS_CHECKOUT,
		PaymentMethods::SOFORT        => Methods::SOFORT,
	);

	/**
	 * Transform WordPress payment method to Sisow method.
	 *
	 * @since unreleased
	 *
	 * @param $payment_method
	 *
	 * @return string
	 */
	public static function transform( $payment_method ) {
		if ( ! is_scalar( $payment_method ) ) {
			return null;
		}

		if ( isset( self::$map[ $payment_method ] ) ) {
			return self::$map[ $payment_method ];
		}

		return null;
	}
}
