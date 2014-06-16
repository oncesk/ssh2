<?php
namespace Ssh\Command\Executor;

use Ssh\Command\Executor;
use Ssh\Command\Result;
use Ssh\Command\CommandInterface;

/**
 * Class Base
 * @package Ssh\CommandInterface\ExecutorInterface
 */
abstract class Base implements ExecutorInterface {

	/**
	 * @param         $result
	 * @param         $isError
	 * @param CommandInterface $command
	 *
	 * @return Result
	 */
	protected function createResult($result, $isError, CommandInterface $command) {
		$result = new Result($result, $isError, $command);
		$command->setResult($result);
		return $result;
	}
}