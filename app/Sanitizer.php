<?php

namespace Security;

/**
 * @package  Example
 * @copyright (C) 2019 .... All Rights Reserved.
 */
class Sanitizer {

	const DEFAULT_ALLOWED_HTML_TAGS = '<p><b><i><s><u><sub><sup><strong>';

	/**
	 * Sanitize any user input to prevent SQL / HTML / JS injections and XSS
	 *
	 * @param string $string
	 * @param string $allowedHtmlTags
	 * @return string
	 */
	public function stringValue($string, $allowedHtmlTags = '')
	{
		$string = (string) $string;
		$string = htmlspecialchars_decode( urldecode($string), ENT_QUOTES );

		if ($allowedHtmlTags != '') {
			$string = strip_tags($string, $allowedHtmlTags) );
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
	public static function arrayOfInts(array $values)
	{
		foreach ($values as $key => $item) {
			$values[$key] = (int) $item;
		}
		
		if ( empty($values) ) {
			$values = [0];
		}

		return $values;
	}

	/**
	 * @param string $emailAddress
	 * @return string|bool	Returns email address if valid, otherwise false
	 */
	public function checkEmail($emailAddress)
	{
		return filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
	}

}
