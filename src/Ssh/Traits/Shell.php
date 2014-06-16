<?php
namespace Ssh\Traits;

/**
 * Class ShellCommand
 * @package Ssh\Traits
 */
trait Shell {

	public function getShellUser() {

	}

	protected function hasFunction($function) {
		if (!function_exists($function)) {
			throw new \RuntimeException(sprintf('Function `%s` does not defined', $function));
		}
	}
}