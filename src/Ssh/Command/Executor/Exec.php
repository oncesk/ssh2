<?php
namespace Ssh\Command\Executor;

use Ssh\Command\ExecCommandInterface;
use Ssh\Command\CommandInterface;
use Ssh\Command\ResultInterface;
use Ssh\ConnectionInterface;

/**
 * Class ExecCommand
 * @package Ssh\CommandInterface
 */
class Exec extends Base {

	/**
	 * @param ConnectionInterface $connection
	 * @param CommandInterface    $command
	 * @param \Closure|callable $readTickCallback
	 *
	 * @return ResultInterface
	 * @throws \RuntimeException
	 */
	public function exec(ConnectionInterface $connection, CommandInterface $command, $readTickCallback = null) {
		$command->execBegin();
		$stream = @ssh2_exec(
			$connection->getConnection(),
			$command->asString(),
			$command instanceof ExecCommandInterface ? $command->getPty() : null,
			$command->getEnv(),
			$command->getWidth(),
			$command->getHeight(),
			$command->getWidthHeightType()
		);

		$connection->history()->add($command);
		if (!$stream) {
			throw new \RuntimeException(sprintf('Can not execute command `%s`', $command->asString()));
		}
		$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		stream_set_blocking($stream, 1);
		stream_set_blocking($errorStream, 1);
		$error = stream_get_contents($errorStream);
		$content = stream_get_contents($stream);
		fclose($errorStream);
		fclose($stream);
		if ($error) {
			$result = $this->createResult($error, true, $command);
		} else {
			$result = $this->createResult($content, false, $command);
		}
		$command->execEnd();
		return $result;
	}
}