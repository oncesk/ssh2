<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;

/**
 * Class ChainInterface
 * @package Ssh\CommandInterface
 */
interface ChainInterface {

	/**
	 * @param CommandInterface|string $command
	 * @param null $callback
	 * @param \Closure|callable $readTickCallback
	 *
	 * @return $this
	 */
	public function exec($command, $callback = null, $readTickCallback = null);

	/**
	 * @return ChainInterface
	 */
	public function stopChain();
}