<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;

/**
 * Class Result
 * @package Ssh\CommandInterface
 */
class Result implements ResultInterface {

	/**
	 * @var string
	 */
	protected $error;

	/**
	 * @var string
	 */
	protected $result;

	/**
	 * @var CommandInterface
	 */
	protected $command;

	/**
	 * @var bool
	 */
	protected $hasErrors = false;

	/**
	 * @param         $result
	 * @param         $isError
	 * @param CommandInterface $command
	 */
	public function __construct($result, $isError, CommandInterface $command) {
		if ($isError) {
			$this->error = $result;
			$this->hasErrors = true;
		} else {
			$this->result = $result;
		}
		$this->command = $command;
	}

	/**
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function hasErrors() {
		return !empty($this->error);
	}

	/**
	 * @return CommandInterface
	 */
	public function getCommand() {
		return $this->command;
	}

	/**
	 * @return string
	 */
	public function getResult() {
		return trim($this->result);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->hasErrors() ? $this->getError() : $this->getResult();
	}
}