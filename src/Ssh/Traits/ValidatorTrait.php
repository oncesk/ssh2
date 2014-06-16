<?php
namespace Ssh\Traits;

trait ValidatorTrait {

	/**
	 * @param $string
	 *
	 * @return bool
	 */
	public function isValidString($string) {
		return is_string($string) && !empty($string);
	}

	/**
	 * @param mixed $number
	 *
	 * @return bool
	 */
	public function isValidNumber($number) {
		return !empty($number) && is_numeric($number);
	}
}