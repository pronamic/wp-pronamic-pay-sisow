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
	 * Get products.
	 *
	 * @return Items
	 */
	public function get_products() {
		return $this->products;
	}

	/**
	 * Set products.
	 *
	 * @param Items $items Order items.
	 *
	 * @return void
	 */
	public function set_products( Items $items ) {
		$this->products = $items;
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
			'shopid'               => $this->shop_id,
			'merchantid'           => $this->merchant_id,
			'payment'              => $this->payment,
			'purchaseid'           => $this->purchase_id,
			'amount'               => Pay_Util::amount_to_cents( $this->amount ),
			'issuerid'             => $this->issuer_id,
			'qrcode'               => Pay_Util::boolean_to_string( $this->qrcode ),
			'testmode'             => Pay_Util::boolean_to_string( $this->test_mode ),
			'entrancecode'         => $this->entrance_code,
			'description'          => $this->description,
			'returnurl'            => $this->return_url,
			'cancelurl'            => $this->cancel_url,
			'callbackurl'          => $this->callback_url,
			'notifyurl'            => $this->notify_url,
			'sha1'                 => $this->get_sha1( $merchant_key ),

			// Post-pay required parameters.
			'ipaddress'            => $this->ip_address,
			'gender'               => $this->gender,
			'birthdate'            => $this->birth_date,

			// Billing.
			'billing_firstname'    => isset( $this->billing['first_name'] ) ? $this->billing['first_name'] : null,
			'billing_lastname'     => isset( $this->billing['last_name'] ) ? $this->billing['last_name'] : null,
			'billing_company'      => isset( $this->billing['company'] ) ? $this->billing['company'] : null,
			'billing_coc'          => isset( $this->billing['company_coc'] ) ? $this->billing['company_coc'] : null,
			'billing_address1'     => isset( $this->billing['address_1'] ) ? $this->billing['address_1'] : null,
			'billing_address2'     => isset( $this->billing['address_2'] ) ? $this->billing['address_2'] : null,
			'billing_zip'          => isset( $this->billing['zip'] ) ? $this->billing['zip'] : null,
			'billing_city'         => isset( $this->billing['city'] ) ? $this->billing['city'] : null,
			'billing_country'      => isset( $this->billing['country'] ) ? $this->billing['country'] : null,
			'billing_countrycode'  => isset( $this->billing['country_code'] ) ? $this->billing['country_code'] : null,
			'billing_phone'        => isset( $this->billing['phone'] ) ? $this->billing['phone'] : null,
			'billing_mail'         => $this->billing_mail,

			// Shipping.
			'shipping_firstname'   => isset( $this->shipping['first_name'] ) ? $this->shipping['first_name'] : null,
			'shipping_lastname'    => isset( $this->shipping['last_name'] ) ? $this->shipping['last_name'] : null,
			'shipping_company'     => isset( $this->shipping['company'] ) ? $this->shipping['company'] : null,
			'shipping_coc'         => isset( $this->shipping['company_coc'] ) ? $this->shipping['company_coc'] : null,
			'shipping_address1'    => isset( $this->shipping['address_1'] ) ? $this->shipping['address_1'] : null,
			'shipping_address2'    => isset( $this->shipping['address_2'] ) ? $this->shipping['address_2'] : null,
			'shipping_zip'         => isset( $this->shipping['zip'] ) ? $this->shipping['zip'] : null,
			'shipping_city'        => isset( $this->shipping['city'] ) ? $this->shipping['city'] : null,
			'shipping_country'     => isset( $this->shipping['country'] ) ? $this->shipping['country'] : null,
			'shipping_countrycode' => isset( $this->shipping['country_code'] ) ? $this->shipping['country_code'] : null,
			'shipping_phone'       => isset( $this->shipping['phone'] ) ? $this->shipping['phone'] : null,
			'shipping_mail'        => isset( $this->shipping['email'] ) ? $this->shipping['email'] : null,
		);

		// Products.
		if ( $this->products instanceof Items ) {
			$products = $this->products->getIterator();

			while ( $products->valid() ) {
				$product = $products->current();

				$key = $products->key() + 1;

				$params[ 'product_id_' . $key ]          = $product->get_id();
				$params[ 'product_description_' . $key ] = $product->get_description();
				$params[ 'product_quantity_' . $key ]    = $product->get_quantity();
				$params[ 'product_netprice_' . $key ]    = Pay_Util::amount_to_cents( $product->get_price() );
				$params[ 'product_nettotal_' . $key ]    = $params[ 'product_netprice_' . $key ] * $product->get_quantity();

				// @todo get tax rate and amount from product?
				$params[ 'product_taxrate_' . $key ] = 0000;
				$params[ 'product_tax_' . $key ]     = Pay_Util::amount_to_cents(
					( $params[ 'product_nettotal_' . $key ] / 100 ) * ( $params[ 'product_taxrate_' . $key ] / 10000 )
				);

				$params[ 'product_total_' . $key ] = $params[ 'product_nettotal_' . $key ] + $params[ 'product_tax_' . $key ];

				$products->next();
			}
		}

		return $params;
	}
}
