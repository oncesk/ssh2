<?php
namespace Ssh;

/**
 * Class ErrorStreamInterface
 * @package Ssh
 */
interface ErrorStreamInterface {

	/**
	 * @throws \RuntimeException
	 *
	 * @return resource
	 */
	public function getErrorStream();
}