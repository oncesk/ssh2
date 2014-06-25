<?php
namespace Ssh;

use Ssh\Command\CommandInterface;
use Ssh\Command\Executor\ExecutorInterface;

/**
 * Class ReadTick
 * @package Ssh
 */
class ReadTick {

	/**
	 * Read in the last cycle
	 *
	 * @var string
	 */
	public $tickRead = '';

	/**
	 * All read data
	 *
	 * @var string
	 */
	public $read = '';

	/**
	 * @var array
	 */
	public $readLines = array();

	/**
	 * @var CommandInterface
	 */
	public $command;

	/**
	 * IO
	 *
	 * @var resource
	 */
	public $connection;
}