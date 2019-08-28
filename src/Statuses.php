<?php
/**
 * Statuses
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

use Pronamic\WordPress\Pay\Core\Statuses as Core_Statuses;

/**
 * Title: Sisow statuses constants
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.1
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
	 * Transform an Sisow state to a more global status.
	 *
	 * @param string $status Sisow status.
	 *
	 * @return string|null Pay status.
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::PENDING:
			case self::OPEN:
				return Core_Statuses::OPEN;

			case self::RESERVATION:
				return Core_Statuses::RESERVED;

			case self::SUCCESS:
				return Core_Statuses::SUCCESS;

			case self::CANCELLED:
				return Core_Statuses::CANCELLED;

			case self::EXPIRED:
				return Core_Statuses::EXPIRED;

			case self::DENIED:
			case self::FAILURE:
				return Core_Statuses::FAILURE;

			case self::REVERSED:
				return Core_Statuses::REFUNDED;

			default:
				return null;
		}
	}
}
