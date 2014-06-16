<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;

/**
 * Class HistoryInterface
 * @package Ssh
 */
interface HistoryInterface extends \Countable, \IteratorAggregate {

	/**
	 * @param CommandInterface $command
	 *
	 * @return mixed
	 */
	public function add(CommandInterface $command);

	/**
	 * @return CommandInterface[]
	 */
	public function all();
}