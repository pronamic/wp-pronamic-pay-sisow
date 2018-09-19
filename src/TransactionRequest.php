<?php

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Util as Pay_Util;

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
class TransactionRequest {
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
	 * Constructs and initializes an Sisow trannsaction request object
	 */
	public function __construct() {

	}

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

	/**
	 * Get parameters.
	 *
	 * @param string $merchant_key Merchant key.
	 *
	 * @return array
	 */
	public function get_parameters( $merchant_key ) {
		$params = array(
			'shopid'                => $this->shop_id,
			'merchantid'            => $this->merchant_id,
			'payment'               => $this->payment,
			'purchaseid'            => $this->purchase_id,
			'amount'                => Pay_Util::amount_to_cents( $this->amount ),
			'issuerid'              => $this->issuer_id,
			'qrcode'                => Pay_Util::boolean_to_string( $this->qrcode ),
			'testmode'              => Pay_Util::boolean_to_string( $this->test_mode ),
			'entrancecode'          => $this->entrance_code,
			'description'           => $this->description,
			'billing_mail'          => $this->billing_mail,
			'returnurl'             => $this->return_url,
			'cancelurl'             => $this->cancel_url,
			'callbackurl'           => $this->callback_url,
			'notifyurl'             => $this->notify_url,
			'sha1'                  => $this->get_sha1( $merchant_key ),

			// Post-pay required parameters.
			'ipaddress'             => $_SERVER['REMOTE_ADDR'],
			'gender'                => 'm',
			'birthdate'             => '01011970',

			// Billing.
			'billing_firstname'     => 'Test',
			'billing_lastname'      => 'van Pronamic',
			'billing_company'       => 'Test BV',
			'billing_coc'           => '',
			'billing_address1'      => 'Test 1',
			'billing_address2'      => '',
			'billing_zip'           => '0000TT',
			'billing_city'          => 'Test',
			'billing_country'       => 'Nederland',
			'billing_countrycode'   => 'NL',
			'billing_phone'         => '0000000000',

			// Shipping.
			'shipping_firstname'    => 'Test',
			'shipping_lastname'     => 'van Pronamic',
			'shipping_mail'         => 'reuel@pronamic.nl',
			'shipping_company'      => 'Test BV',
			'shipping_coc'          => '',
			'shipping_address1'     => 'Test 1',
			'shipping_address2'     => '',
			'shipping_zip'          => '0000TT',
			'shipping_city'         => 'Test',
			'shipping_country'      => 'Nederland',
			'shipping_countrycode'  => 'NL',
			'shipping_phone'        => '0000000000',

			// Product.
			'product_id_1'          => 'TEST01',
			'product_description_1' => 'Test product',
			'product_quantity_1'    => 2,
			'product_netprice_1'    => 1000,
			'product_total_1'       => 2420,
			'product_nettotal_1'    => 2000,
			'product_tax_1'         => 420,
			'product_taxrate_1'     => 2100,
		);

		return $params;
	}
}
