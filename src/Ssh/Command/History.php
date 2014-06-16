<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;
use Ssh\Command\HistoryInterface;
use Ssh\ExecInterface;

/**
 * Class History
 * @package Ssh\CommandInterface
 */
class History implements HistoryInterface {

	/**
	 * @var CommandInterface[]
	 */
	protected $history = array();

	/**
	 * @var ExecInterface
	 */
	protected $exec;

	public function __construct(ExecInterface $exec) {
		$this->exec = $exec;
	}

	/**
	 * @param CommandInterface $command
	 *
	 * @return mixed
	 */
	public function add(CommandInterface $command) {
		$this->history[$command->asString()] = $command;
		return $this;
	}

	/**
	 * @return CommandInterface[]
	 */
	public function all() {
		return $this->history;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 *       The return value is cast to an integer.
	 */
	public function count() {
		return count($this->history);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator() {
		return new \ArrayIterator($this->history);
	}
}