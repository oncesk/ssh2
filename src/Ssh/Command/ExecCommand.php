<?php
namespace Ssh\Command;

/**
 * Class ExecCommand
 * @package Ssh\CommandInterface
 */
class ExecCommand extends Command implements ExecCommandInterface {

	/**
	 * @return string
	 */
	public function getPty() {
		return $this->pty;
	}
}