<?php
namespace Ssh;

use Ssh\Command\ResultInterface;

/**
 * Class ExecCommandInterface
 * @package Ssh
 */
interface ExecInterface extends ConnectionInterface {


	/**
	 * @param CommandInterface|string $command
	 * @param \Closure|callable $readTickCallback
	 *
	 * @return ResultInterface
	 */
	public function exec($command, $readTickCallback = null);
}