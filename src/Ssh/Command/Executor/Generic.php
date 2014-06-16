<?php
namespace Ssh\Command\Executor;

use Ssh\Command\ExecCommandInterface;
use Ssh\Command\ResultInterface;
use Ssh\Command\ShellCommandInterface;
use Ssh\Command\CommandInterface;
use Ssh\Command\Executor;
use Ssh\ConnectionInterface;

/**
 * Class Command
 * @package Ssh\CommandInterface\ExecutorInterface
 */
class Generic implements ExecutorInterface {

	/**
	 * @var ExecutorInterface
	 */
	protected $shellExecutor;

	/**
	 * @var ExecutorInterface
	 */
	protected $execExecutor;

	/**
	 * @param ConnectionInterface $connection
	 * @param CommandInterface    $command
	 *
	 * @return ResultInterface
	 */
	public function exec(ConnectionInterface $connection, CommandInterface $command) {
		return $this->getExecutor($command)->exec($connection, $command);
	}

	/**
	 * @param CommandInterface $command
	 *
	 * @return ExecutorInterface
	 * @throws \RuntimeException
	 */
	protected function getExecutor(CommandInterface $command) {
		if ($command instanceof ShellCommandInterface) {
			return $this->getShellExecutor();
		}
		if ($command instanceof ExecCommandInterface || is_a($command, 'Ssh\Command\CommandInterface')) {
			return $this->getExecExecutor();
		}
		throw new \RuntimeException('Can not define executor for command ' . get_class($command));
	}

	/**
	 * @return ExecutorInterface
	 */
	protected function getExecExecutor() {
		if ($this->execExecutor) {
			return $this->execExecutor;
		}
		return $this->execExecutor = new Exec();
	}

	/**
	 * @return ExecutorInterface
	 */
	protected function getShellExecutor() {
		if ($this->shellExecutor) {
			return $this->shellExecutor;
		}
		return $this->shellExecutor = new Shell();
	}
}