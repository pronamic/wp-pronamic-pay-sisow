<?php
/**
 * Util
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Gateways\Sisow;

/**
 * Title: Sisow utility class
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Util {
	/**
	 * Holds the unallowed character pattern.
	 *
	 * @var string|null
	 */
	private static $pattern;

	/**
	 * Get unallowed character pattern.
	 *
	 * Karakterset
	 *
	 * @link http://pronamic.nl/wp-content/uploads/2013/02/sisow-rest-api-v3.2.1.pdf
	 *
	 * Hieronder de tabel toegestane karakters.
	 *
	 * Karakter(s)  Omschrijving
	 * A-Z          Hoofdletters
	 * a-z          Kleine letters
	 * 0-9          Cijfers
	 * =            Is/gelijk
	 *              Spatie
	 * %            Procent
	 * *            Asterisk
	 * +            Plus
	 * -            Min
	 * .            Punt
	 * /            Slash
	 * &            Ampersand
	 * @            Apestaart
	 * "            Dubbel quote
	 * '            Enkele quote
	 * :            Dubbele punt
	 * ;            Punt komma
	 * ?            Vraagteken
	 * (            Haakje openen
	 * )            Haakje sluiten
	 * $            Dollar
	 */
	public static function get_pattern() {
		if ( null === self::$pattern ) {
			$characters = array(
				'A-Z',
				'a-z',
				'0-9',
				'=',
				' ',
				'%',
				'*',
				'+',
				'-',
				'.',
				'/',
				'&',
				'@',
				'"',
				'\'',
				':',
				';',
				'?',
				'(',
				')',
				'$',
			);

			/*
			 * We use a # as a regex delimiter instead of a / so we don't have to escape the slash.
			 * @link http://stackoverflow.com/q/12239424
			 */
			self::$pattern = '#[^' . implode( $characters ) . ']#';
		}

		return self::$pattern;
	}

	/**
	 * Filter all Sisow unallowed charachters.
	 *
	 * @param string $string String to filter.
	 * @return mixed
	 */
	public static function filter( $string ) {
		return preg_replace( self::get_pattern(), '', $string );
	}
}
