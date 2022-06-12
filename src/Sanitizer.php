<?php

namespace Akoryak\Security;

use Akoryak\Security\Exception\SanitizerException;

class Sanitizer {

	const DEFAULT_ALLOWED_HTML_TAGS = '<p><b><i><s><u><sub><sup><strong>';

	/**
	 * Sanitize any user input to prevent SQL / HTML / JS injections and XSS
	 *
	 * @param mixed $string
	 * @param string $allowedHtmlTags
	 * @throws SanitizerException	Than input param 1 is not a string and is not convertable to string
	 * @return string
	 */
	public function stringValue($string, string $allowedHtmlTags = '') : string {
		
		if (empty($value)) {
			return '';
		}
	
		try {
			$string = (string) $string;
		} catch (\Throwable  $e) {
			throw new SanitizerException('Input param 1 is not a string and is not convertable to string');
		}
		
		$string = htmlspecialchars_decode( urldecode($string), ENT_QUOTES );

		if ($allowedHtmlTags != '') {
			$string = strip_tags($string, $allowedHtmlTags);
		} else {
			$string = htmlspecialchars($string, ENT_QUOTES);
		}

		return $string;
	}

	/**
	 * Sanitize array of integers. Useful for sanitizing array before use in SQL queries
	 *
	 * @param array $values
	 * @return array
	 */
	public static function arrayOfInts(array $values) : array {
		if ( empty($values) ) {
			return [0];
		}

		foreach ($values as $key => $item) {
			$values[$key] = (int) $item;
		}
		
		return $values;
	}

	public function validateEmail(string $emailAddress) : bool {
		return (bool) filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
	}
}
