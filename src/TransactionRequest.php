<?php

/**
 * Title: iDEAL Sisow transation request
 * Description:
 * Copyright: Copyright (c) 2015
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_Sisow_TransactionRequest {
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

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Sisow trannsaction request object
	 */
	public function __construct() {

	}

	/////////////////////////////////////////////////

	public function set_purchase_id( $purchase_id ) {
		$this->purchase_id = Pronamic_WP_Pay_Gateways_Sisow_Util::filter( $purchase_id );
	}

	public function set_entrance_code( $entrance_code ) {
		$this->entrance_code = Pronamic_WP_Pay_Gateways_Sisow_Util::filter( $entrance_code );
	}

	/////////////////////////////////////////////////

	/**
	 * Get SHA1
	 *
	 * @return string
	 */
	public function get_sha1( $merchant_key ) {
		return sha1(
			$this->purchase_id .
			$this->entrance_code .
			Pronamic_WP_Pay_Util::amount_to_cents( $this->amount ) .
			$this->shop_id .
			$this->merchant_id .
			$merchant_key
		);
	}

	/////////////////////////////////////////////////

	/**
	 * Get parameters
	 *
	 * @return array
	 */
	public function get_parameters( $merchant_key ) {
		return array(
			'shopid'       => $this->shop_id,
			'merchantid'   => $this->merchant_id,
			'payment'      => $this->payment,
			'purchaseid'   => $this->purchase_id,
			'amount'       => Pronamic_WP_Pay_Util::amount_to_cents( $this->amount ),
			'issuerid'     => $this->issuer_id,
			'testmode'     => Pronamic_WP_Pay_Util::to_string_boolean( $this->test_mode ),
			'entrancecode' => $this->entrance_code,
			'description'  => $this->description,
			'billing_mail' => $this->billing_mail,
			'returnurl'    => $this->return_url,
			'cancelurl'    => $this->cancel_url,
			'callbackurl'  => $this->callback_url,
			'notifyurl'    => $this->notify_url,
			'sha1'         => $this->get_sha1( $merchant_key ),
		);
	}
}
