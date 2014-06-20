<?php
namespace Ssh\Command\Executor;

use Ssh\Command\ResultInterface;
use Ssh\Command\CommandInterface;
use Ssh\ConnectionInterface;
use Ssh\ReadTick;
use Ssh\TerminalInterface;

/**
 * Class ShellCommand
 * @package Ssh\CommandInterface\ExecutorInterface
 */
class Shell extends Base {

	/**
	 * @param ConnectionInterface $connection
	 * @param CommandInterface    $command
	 * @param \Closure|callable   $readTickCallback
	 *
	 * @return \Ssh\Command\Result|ResultInterface
	 */
	public function exec(ConnectionInterface $connection, CommandInterface $command, $readTickCallback = null) {
		$command->execBegin();
		fwrite($connection->getConnection(), $command->asString() . PHP_EOL);
		usleep(500000);
		$connection->history()->add($command);

		$commandLineLabel = null;

		if ($connection instanceof TerminalInterface) {
			$commandLineLabel = $connection->getCommandLineLabel();
		}

		$self = $this;
		$content = self::read($connection->getConnection(), 8192, $commandLineLabel, function ($tickRead, $read, $readArray, $label) use ($self, $command, $readTickCallback) {
			if ($readTickCallback) {
				$tick = new ReadTick();
				$tick->read = $read;
				$tick->tickRead = $tickRead;
				$tick->readLines = $readArray;
				$tick->command = $command;
				$tick->executor = $self;
				return call_user_func($readTickCallback, $tick);
			}
		});

		$result = $this->createResult($content, 0, $command);
		$command->execEnd();
		return $result;
	}

	public static function read($stream, $bufferLength = 8192, $commandLineLabel = null, $readTickCallback = null) {
		$content = '';

		read:

		$read = fread($stream, $bufferLength);
		if ($read === false) {
			throw new \RuntimeException('Stream read error');
		}
		$content .= $read;
		$lines = preg_split('/\r?\n/', $content);

		if ($readTickCallback) {
			if (call_user_func($readTickCallback, $read, $content, $lines, $commandLineLabel) === false) {
				array_shift($lines);
				return implode("\n", $lines);
			}
		}

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