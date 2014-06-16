<?php
namespace Ssh;

use Ssh\Command\Executor;
use Ssh\Command\ShellCommand;
use Ssh\Traits\ChainTrait;
use Ssh\Traits\ConnectionTrait;
use Ssh\Command\Executor\Shell AS ReadStream;
use Ssh\Traits\HistoryTrait;

/**
 * Class ShellCommand
 * @package Ssh
 */
class Shell implements ConnectionInterface, ErrorStreamInterface, TerminalInterface {

	const TERMINAL_BASH = 'bash';
	const TERMINAL_VANILLA = 'vanilla';
	const TERMINAL_XTERM = 'xterm';
	const TERMINAL_VT102 = 'vt102';

	use ConnectionTrait;
	use HistoryTrait;
	use ChainTrait;

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var resource
	 */
	protected $stream;

	/**
	 * @var resource
	 */
	protected $errorStream;

	/**
	 * @var Executor\ExecutorInterface
	 */
	protected $executor;

	/**
	 * @var string
	 */
	protected $hostName;

	/**
	 * @var string
	 */
	protected $commandLineLabel;

	/**
	 * @param Client $client
	 * @param string $term_type
	 * @param array  $env
	 * @param int    $width
	 * @param int    $height
	 * @param int    $widthHeightType
	 * @throws \Exception
	 */
	public function __construct(
		Client $client,
		$term_type = self::TERMINAL_XTERM,
		$env = array(),
		$width = SSH2_DEFAULT_TERM_WIDTH,
		$height = SSH2_DEFAULT_TERM_WIDTH,
		$widthHeightType = SSH2_DEFAULT_TERM_UNIT) {
		if (!$client->isAlive()) {
			throw new \Exception('Client is not connected');
		}
		$this->client = $client;
		$this->stream = @ssh2_shell(
			$client->getConnection(),
			$term_type,
			$env,
			$width,
			$height,
			$widthHeightType
		);
		if (!$this->stream) {
			throw new \Exception('Can not get shell');
		}
		stream_set_blocking($this->stream, true);

		usleep(500000);

		$read = fread($this->stream, 8192);
		$lines = preg_split('/\r?\n/', $read);
		$end = $lines[count($lines) - 1];
		if (!preg_match(sprintf("/%s@/", $client->getUser()), $end)) {
			throw new \RuntimeException('Can not find terminal command line label');
		}

		$this->hostName = $this->exec('hostname');

		if (!preg_match(sprintf('/%s@%s/', $client->getUser(), $this->hostName), $end)) {
			throw new \RuntimeException('Can not find terminal command line label');
		}

		$this->commandLineLabel = $client->getUser() . '@' . $this->hostName;
		$this->fetchErrorStream();
	}

	/**
	 * @return string
	 */
	public function getHostname() {
		return $this->hostName;
	}

	/**
	 * @return string
	 */
	public function getCommandLineLabel() {
		return $this->commandLineLabel;
	}

	/**
	 * @param $command
	 *
	 * @return Command\ResultInterface
	 */
	public function exec($command) {
		if (is_string($command)) {
			$command = new ShellCommand($command);
		}
		return $this->getExecutor()->exec($this, $command);
	}

	/**
	 * @return Executor\Generic
	 */
	public function getExecutor() {
		if ($this->executor) {
			return $this->executor;
		}
		return $this->executor = new Executor\Generic();
	}

	/**
	 * @return resource
	 */
	public function getErrorStream() {
		if (!is_resource($this->errorStream)) {
			$this->fetchErrorStream();
		}
		return $this->errorStream;
	}

	/**
	 * @return int in miliseconds, 1 second = 1000 miliseconds
	 */
	public function getReadTimeout() {
		return 2000;
	}

	protected function fetchErrorStream() {
		$this->errorStream = ssh2_fetch_stream($this->getConnection(), SSH2_STREAM_STDERR);
		stream_set_blocking($this->errorStream, 1);
	}

	/**
	 * @return resource
	 */
	protected function getStream() {
		return $this->stream;
	}
}