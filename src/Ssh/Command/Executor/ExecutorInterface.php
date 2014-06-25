<?php
namespace Ssh\Command\Executor;

use Ssh\Command\ResultInterface;
use Ssh\Command\CommandInterface;
use Ssh\ConnectionInterface;

/**
 * Class ExecutorInterface
 * @package Ssh
 */
interface ExecutorInterface {

	/**
	 * @param ConnectionInterface $connection
	 * @param CommandInterface    $command
	 * @param \Closure|callable   $readTickCallback
	 *
	 * @return ResultInterface
	 */
	public function exec(ConnectionInterface $connection, CommandInterface $command, $readTickCallback = null);
}