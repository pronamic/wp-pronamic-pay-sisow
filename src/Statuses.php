<?php
/**
 * Statuses
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;

/**
 * Title: Sisow statuses constants
 * Description:
 * Copyright: 2005-2022 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.4
 * @since   2.0.1
 */
class Statuses {
	/**
	 * Open.
	 *
	 * @var string
	 */
	const OPEN = 'Open';

	/**
	 * Pending.
	 *
	 * @var string
	 */
	const PENDING = 'Pending';

	/**
	 * Reservation.
	 *
	 * @var string
	 */
	const RESERVATION = 'Reservation';

	/**
	 * Paid.
	 *
	 * @var string
	 */
	const SUCCESS = 'Success';

	/**
	 * Cancelled.
	 *
	 * @var string
	 */
	const CANCELLED = 'Cancelled';

	/**
	 * Expired.
	 *
	 * @var string
	 */
	const EXPIRED = 'Expired';

	/**
	 * Denied.
	 *
	 * @var string
	 */
	const DENIED = 'Denied';

	/**
	 * Failure.
	 *
	 * @var string
	 */
	const FAILURE = 'Failure';

	/**
	 * Reversed.
	 *
	 * @var string
	 */
	const REVERSED = 'Reversed';

	/**
	 * Statuses map.
	 *
	 * @var array<string,string>
	 */
	private static $map = array(
		self::CANCELLED   => Core_Statuses::CANCELLED,
		self::DENIED      => Core_Statuses::FAILURE,
		self::EXPIRED     => Core_Statuses::EXPIRED,
		self::FAILURE     => Core_Statuses::FAILURE,
		self::OPEN        => Core_Statuses::OPEN,
		self::PENDING     => Core_Statuses::OPEN,
		self::RESERVATION => Core_Statuses::RESERVED,
		self::REVERSED    => Core_Statuses::REFUNDED,
		self::SUCCESS     => Core_Statuses::SUCCESS,
	);

	/**
	 * Transform an Sisow state to a more global status.
	 *
	 * @param string $status Sisow status.
	 *
	 * @return string|null Pay status.
	 */
	public static function transform( $status ) {
		if ( ! is_scalar( $status ) ) {
			return null;
		}

		if ( isset( self::$map[ $status ] ) ) {
			return self::$map[ $status ];
		}

		return null;
	}
}
