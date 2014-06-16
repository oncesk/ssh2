<?php
namespace Ssh;

use Ssh\Command\HistoryInterface;

/**
 * Class ConnectionInterface
 * @package Ssh
 */
interface ConnectionInterface {

	/**
	 * @return HistoryInterface
	 */
	public function history();

	/**
	 * @return int in miliseconds, 1 second = 1000 miliseconds
	 */
	public function getReadTimeout();

	/**
	 * @throws \Exception
	 *
	 * @return resource
	 */
	public function getConnection();
}