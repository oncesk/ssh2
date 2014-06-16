<?php
namespace Ssh;

use Ssh\Auth\AuthInterface;
use Ssh\Command\CommandInterface;
use Ssh\Command\Executor;
use Ssh\Command\Command;
use Ssh\Command\ResultInterface;
use Ssh\Config\HostConfiguration;
use Ssh\Traits\ChainTrait;
use Ssh\Traits\ConnectionTrait;
use Ssh\Traits\HistoryTrait;
use Ssh\Traits\ValidatorTrait;

class Client implements ConnectionInterface, ExecInterface, ChainInterface {

	use ValidatorTrait;
	use ConnectionTrait;
	use HistoryTrait;
	use ChainTrait;

	/**
	 * @var resource
	 */
	protected $connection;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var array
	 */
	protected $methods = array();

	/**
	 * @var array
	 */
	protected $callbacks = array();

	/**
	 * @var Shell
	 */
	protected $shell;

	/**
	 * @var Executor\ExecutorInterface
	 */
	protected $commandExecutor;

	/**
	 * @var HostConfiguration
	 */
	protected $hostConfig;

	/**
	 * @var bool
	 */
	protected $isAuthenticated = false;

	/**
	 * @var AuthInterface
	 */
	protected $auth;

	/**
	 * @param string|HostConfiguration $hostOrHostConfig
	 * @param int    $port
	 */
	public function __construct($hostOrHostConfig = '', $port = 22) {
		$this->setPort($port);
		if ($hostOrHostConfig instanceof HostConfiguration) {
			$this->fromHostConfig($hostOrHostConfig);
		} else {
			$this->setHost($hostOrHostConfig);
		}
	}

	public function fromHostConfig(HostConfiguration $hostConfig) {
		$this->hostConfig = $hostConfig;

		if (!$this->auth) {
			try {
				$this->auth = $hostConfig->getAuth();
			} catch (\RuntimeException $e) {}
		}

		return $this
					->setHost($hostConfig->getHostname())
					->setPort($hostConfig->getPort());
	}

	/**
	 * @param string $host
	 *
	 * @return $this
	 */
	public function setHost($host) {
		if ($this->isValidString($host)) {
			$this->host = $host;
		}
		return $this;
	}

	/**
	 * @param $port
	 *
	 * @return $this
	 */
	public function setPort($port) {
		if ($this->isValidNumber($port)) {
			$this->port = (int) $port;
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @return bool
	 */
	public function isAuthenticated() {
		return $this->isAuthenticated;
	}

	/**
	 * @return AuthInterface
	 */
	public function getAuth() {
		return $this->auth;
	}

	/**
	 * Connect to remote host
	 *
	 * @see http://www.php.net/manual/ru/function.ssh2-connect.php
	 *
	 * @throws \RuntimeException
	 * @return $this
	 */
	public function connect() {
		$this->connection = @ssh2_connect($this->getHost(), $this->getPort());

		if (!$this->connection) {
			throw new \RuntimeException('ConnectionInterface failed');
		}

		return $this;
	}

	public function close() {}

	/**
	 * @param Shell $shell
	 *
	 * @return $this
	 */
	public function setShell(Shell $shell) {
		$this->shell = $shell;
		return $this;
	}

	/**
	 * @return Shell
	 */
	public function getShell() {
		if ($this->shell) {
			return $this->shell;
		}
		return $this->shell = new Shell($this);
	}

	/**
	 * @return null|string
	 */
	public function getUser() {
		if ($this->isAuthenticated()) {
			return $this->getAuth()->getUser();
		}
		return null;
	}

	/**
	 * @param $command
	 *
	 * @return ResultInterface
	 * @throws \RuntimeException
	 */
	public function exec($command) {
		if (!$this->isAuthenticated()) {
			throw new \RuntimeException('Client is not authorized');
		}
		if (is_string($command)) {
			$command = new Command($command);
		}
		if (!($command instanceof CommandInterface)) {
			throw new \RuntimeException('Can not execute command because command type is not supported, ' . gettype($command));
		}
		return $this->getCommandExecutor()->exec($this, $command);
	}

	/**
	 * @param AuthInterface $auth
	 *
	 * @return $this
	 * @throws \RuntimeException
	 */
	public function authenticate(AuthInterface $auth = null) {
		if (!$this->isAuthenticated()) {
			if ($auth) {
				$this->auth = $auth;
			} else if (!$this->auth) {
				throw new \RuntimeException('Auth not set, please provide Auth before authentication');
			}
			if (!$this->auth->isAuthenticated($this)) {
				$this->auth->authenticate($this);
				$this->isAuthenticated = true;
			}
		}
		return $this;
	}

	/**
	 * @return Executor\ExecutorInterface
	 */
	public function getCommandExecutor() {
		if ($this->commandExecutor) {
			return $this->commandExecutor;
		}
		return $this->commandExecutor = new Executor\Generic();
	}

	/**
	 * @return int in miliseconds, 1 second = 1000 miliseconds
	 */
	public function getReadTimeout() {
		return 2000;
	}

	/**
	 * @return resource
	 */
	protected function getStream() {
		return $this->connection;
	}
}