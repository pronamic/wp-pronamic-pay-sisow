<?php

/**
 * Title: iDEAL Sisow payment methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.2
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods {
	/**
	 * Indicator for 'iDEAL' payment
	 *
	 * @var string
	 */
	const IDEAL = '';

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
		Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::OVERBOEKING,
		Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD   => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::CREDIT_CARD,
		Pronamic_WP_Pay_PaymentMethods::BANCONTACT    => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::MISTER_CASH,
		Pronamic_WP_Pay_PaymentMethods::MISTER_CASH   => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::MISTER_CASH,
		Pronamic_WP_Pay_PaymentMethods::PAYPAL        => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::PAYPAL_EXPRESS_CHECKOUT,
		Pronamic_WP_Pay_PaymentMethods::SOFORT        => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::SOFORT,
		Pronamic_WP_Pay_PaymentMethods::IDEAL         => Pronamic_WP_Pay_Gateways_Sisow_PaymentMethods::IDEAL,
	);

	/**
	 * Transform WordPress payment method to Sisow method.
	 *
	 * @since unreleased
	 * @param string $method
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
