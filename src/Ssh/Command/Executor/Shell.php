<?php
namespace Ssh\Command\Executor;

use Ssh\Command\ResultInterface;
use Ssh\Command\CommandInterface;
use Ssh\ConnectionInterface;
use Ssh\TerminalInterface;

/**
 * Class ShellCommand
 * @package Ssh\CommandInterface\ExecutorInterface
 */
class Shell extends Base {

	/**
	 * @param ConnectionInterface $connection
	 * @param CommandInterface    $command
	 *
	 * @return ResultInterface
	 */
	public function exec(ConnectionInterface $connection, CommandInterface $command) {
		$command->execBegin();
		fwrite($connection->getConnection(), $command->asString() . PHP_EOL);
		usleep(500000);
		$connection->history()->add($command);

		$commandLineLabel = null;

		if ($connection instanceof TerminalInterface) {
			$commandLineLabel = $connection->getCommandLineLabel();
		}

		$content = self::read($connection->getConnection(), 8192, $commandLineLabel);

		$result = $this->createResult($content, 0, $command);
		$command->execEnd();
		return $result;
	}

	public static function read($stream, $bufferLength = 8192, $commandLineLabel = null) {
		$content = '';

		read:

		$read = fread($stream, $bufferLength);
		if ($read === false) {
			throw new \RuntimeException('Stream read error');
		}
		$content .= $read;
		$lines = preg_split('/\r?\n/', $content);
		$terminalLabel = trim($lines[count($lines) - 1]);
		if ($commandLineLabel) {
			if (!preg_match(sprintf('/%s/', addcslashes($commandLineLabel, '$[]~-')), $terminalLabel)) {
				goto read;
			}
		}
		array_shift($lines);//  remove command from command line
		array_pop($lines);  //  remove command line label
		return implode("\n", $lines);
	}
}