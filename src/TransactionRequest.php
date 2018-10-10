<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Pay_Util;
use Pronamic\WordPress\Pay\Payments\Items;

/**
 * Title: iDEAL Sisow transaction request
 * Description:
 * Copyright: Copyright (c) 2015
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class TransactionRequest extends Request {
	/**
	 * Shop ID
	 *
	 * @var string
	 */
	public $shop_id;

	/**
	 * Merchant ID
	 *
	 * @var string
	 */
	public $merchant_id;

	/**
	 * Payment
	 *
	 * @var string
	 */
	public $payment;

	/**
	 * Purchase ID
	 *
	 * @var string
	 */
	private $purchase_id;

	/**
	 * Amount
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * Issuer ID
	 *
	 * @var string
	 */
	public $issuer_id;

	/**
	 * QR Code
	 *
	 * @var string
	 */
	public $qrcode;

	/**
	 * Test mode
	 *
	 * @var boolean
	 */
	public $test_mode;

	/**
	 * Entrance code
	 *
	 * @var string
	 */
	private $entrance_code;

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Billing email address
	 *
	 * @var string
	 * @since 1.2.0
	 */
	public $billing_mail;

	/**
	 * Billing address.
	 *
	 * @var array
	 * @since 2.0.1
	 */
	public $billing_address;

	/**
	 * Shipping address.
	 *
	 * @var array
	 * @since 2.0.1
	 */
	public $shipping_address;

	/**
	 * Products.
	 *
	 * @var Items
	 * @since 2.0.1
	 */
	private $products;

	/**
	 * IP address.
	 *
	 * @var string
	 */
	public $ip_address;

	/**
	 * Date of birth.
	 *
	 * @var string
	 */
	public $birth_date;

	/**
	 * Gender.
	 *
	 * @var string
	 */
	public $gender;

	/**
	 * Return URL
	 *
	 * @var string
	 */
	public $return_url;

	/**
	 * Cancel URL
	 *
	 * @var string
	 */
	public $cancel_url;

	/**
	 * Callback URL
	 *
	 * @var string
	 */
	public $callback_url;

	/**
	 * Notify URL
	 *
	 * @var string
	 */
	public $notify_url;
	/**
	 * Set purchase ID.
	 *
	 * @param string $purchase_id Purchase ID.
	 */
	public function set_purchase_id( $purchase_id ) {
		$this->purchase_id = Util::filter( $purchase_id );
	}

	/**
	 * Set entrance code.
	 *
	 * @param string $entrance_code Entrance code.
	 */
	public function set_entrance_code( $entrance_code ) {
		$this->entrance_code = Util::filter( $entrance_code );
	}

	/**
	 * Get SHA1.
	 *
	 * @param string $merchant_key Merchant key.
	 *
	 * @return string
	 */
	public function get_sha1( $merchant_key ) {
		return sha1(
			$this->purchase_id .
			$this->entrance_code .
			Pay_Util::amount_to_cents( $this->amount ) .
			$this->shop_id .
			$this->merchant_id .
			$merchant_key
		);
	}

	public function get_signature_data() {
		return array(
			$this->get_parameter( 'purchaseid' ),
			/*
			 * Wordt er geen gebruik gemaakt van de entrancecode dan dient er twee keer de purchaseid te worden opgenomen, u krijgt dan onderstaande volgorde.
			 * purchaseid/purchaseid/amount/shopid/merchantid/merchantkey
			 */
			null !== $this->get_parameter( 'entrancecode' ) ? $this->get_parameter( 'entrancecode' ) : $this->get_parameter( 'purchaseid' ),
			$this->get_parameter( 'amount' ),
			$this->get_parameter( 'shopid' ),
			$this->get_parameter( 'merchantid' ),
			$this->get_parameter( 'merchantkey' ),
		);
	}
}
