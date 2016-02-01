<?php

/**
 * Title: iDEAL Sisow payment methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.1.2
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
}
