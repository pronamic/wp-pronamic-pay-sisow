<?php
/**
 * Transaction
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\DateTime\DateTime;

/**
 * Title: iDEAL Sisow error
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Transaction {
	/**
	 * Transaction ID
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The status of the transaction
	 *
	 * @var string
	 */
	public $status;

	/**
	 * The amount of the transaction
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * Purchase ID
	 *
	 * @var string
	 */
	public $purchase_id;

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Entrance code
	 *
	 * @var string
	 */
	public $entrance_code;

	/**
	 * Issuer ID
	 *
	 * @var string
	 */
	public $issuer_id;

	/**
	 * Timestamp
	 *
	 * @var DateTime
	 */
	public $timestamp;

	/**
	 * Consumer name
	 *
	 * @var string
	 */
	public $consumer_name;

	/**
	 * Consumer account
	 *
	 * @var string
	 */
	public $consumer_account;

	/**
	 * Consumer city
	 *
	 * @var string
	 */
	public $consumer_city;

	/**
	 * Issuer URL
	 *
	 * @var string
	 */
	public $issuer_url;

	/**
	 * Constructs and initializes an Sisow error object
	 */
	public function __construct() {

	}

	/**
	 * Create an string representation of this object
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->id . ' ' . $this->issuer_url;
	}
}
