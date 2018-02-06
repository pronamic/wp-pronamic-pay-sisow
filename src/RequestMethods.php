<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: iDEAL Sisow methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 1.0.0
 */
class RequestMethods {
	/**
	 * Indicator for an directory request
	 *
	 * @var string
	 */
	const DIRECTORY_REQUEST = 'DirectoryRequest';

	/**
	 * Indicator for an transaction request
	 *
	 * @var string
	 */
	const TRANSACTION_REQUEST = 'TransactionRequest';

	/**
	 * Indicator for an status request
	 *
	 * @var string
	 */
	const STATUS_REQUEST = 'StatusRequest';
}
