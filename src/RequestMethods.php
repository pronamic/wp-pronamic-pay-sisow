<?php
/**
 * Request methods
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: iDEAL Sisow methods
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class RequestMethods {
	/**
	 * Indicator for a cancel reservation request
	 *
	 * @var string
	 */
	const CANCEL_RESERVATION_REQUEST = 'CancelReservationRequest';

	/**
	 * Indicator for a merchant check request
	 *
	 * @var string
	 */
	const CHECK_MERCHANT_REQUEST = 'CheckMerchantRequest';

	/**
	 * Indicator for a directory request
	 *
	 * @var string
	 */
	const DIRECTORY_REQUEST = 'DirectoryRequest';

	/**
	 * Indicator for a invoice request
	 *
	 * @var string
	 */
	const INVOICE_REQUEST = 'InvoiceRequest';

	/**
	 * Indicator for a status request
	 *
	 * @var string
	 */
	const STATUS_REQUEST = 'StatusRequest';

	/**
	 * Indicator for a transaction request
	 *
	 * @var string
	 */
	const TRANSACTION_REQUEST = 'TransactionRequest';
}
