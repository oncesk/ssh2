<?php
namespace Ssh\Command;

use Ssh\Shell AS ShellClient;

/**
 * Class Command
 * @package Ssh\CommandInterface
 */
class Command implements CommandInterface {

	/**
	 * @var string
	 */
	protected $command;

	/**
	 * @var string
	 */
	protected $pty;

	/**
	 * @var string
	 */
	protected $terminalType = ShellClient::TERMINAL_BASH;

	/**
	 * @var array
	 */
	protected $env = array();

	/**
	 * @var string
	 */
	protected $error;

	/**
	 * @var bool
	 */
	protected $isExecuted = false;

	/**
	 * @var ResultInterface
	 */
	protected $result;

	/**
	 * @var float
	 */
	protected $createDate;

	/**
	 * @var float
	 */
	protected $executedBeginAt;

	/**
	 * @var float
	 */
	protected $executedEndAt;

	/**
	 * @var float
	 */
	protected $executionTime;

	/**
	 * @param string $command
	 */
	public function __construct($command) {
		$this->command = $command;
		$this->createDate = microtime(true);
	}

	/**
	 * @return int
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * @return float with micro time
	 */
	public function getExecutionEndAt() {
		return $this->executedEndAt;
	}

	/**
	 * @return float with micro time
	 */
	public function getExecutionStartAt() {
		return $this->executedBeginAt;
	}

	/**
	 * @return int
	 */
	public function getExecutionTime() {
		return $this->executionTime;
	}

	/**
	 * @return $this
	 */
	public function execBegin() {
		$this->executedBeginAt = microtime(true);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function execEnd() {
		$this->executedEndAt = microtime(true);
		$this->executionTime = $this->getExecutionEndAt() - $this->getExecutionStartAt();
		return $this;
	}

	/**
	 * @return array
	 */
	public function getEnv() {
		return $this->env;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return SSH2_DEFAULT_TERM_WIDTH;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return SSH2_DEFAULT_TERM_HEIGHT;
	}

	/**
	 * @return int
	 */
	public function getWidthHeightType() {
		return SSH2_DEFAULT_TERM_UNIT;
	}

	/**
	 * @return string
	 */
	public function asString() {
		return (string) $this->command;
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
	public function isExecuted() {
		return $this->isExecuted;
	}

	/**
	 * @param ResultInterface $result
	 *
	 * @return $this
	 */
	public function setResult(ResultInterface $result) {
		$this->result = $result;
		$this->isExecuted = true;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->asString();
	}

	public function __clone() {
		$this->isExecuted = false;
		$this->result = null;
		$this->createDate = microtime(true);
		$this->executedEndAt = null;
		$this->executedBeginAt = null;
		$this->executionTime = null;
	}
}