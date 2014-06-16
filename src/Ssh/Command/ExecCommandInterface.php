<?php
namespace Ssh\Command;

use Ssh\CommandInterface;

interface ExecCommandInterface extends CommandInterface {

	/**
	 * @return string
	 */
	public function getPty();
}