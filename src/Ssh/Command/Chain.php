<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;
use Ssh\ExecInterface;

/**
 * Class Chain
 * @package Ssh\CommandInterface
 */
class Chain implements ChainInterface {

	/**
	 * @var ExecInterface
	 */
	protected $executable;

	/**
	 * @var bool
	 */
	protected $isStopped = false;

	/**
	 * @param ExecInterface $executable
	 */
	public function __construct(ExecInterface $executable) {
		$this->executable = $executable;
	}

	/**
	 * @param string|CommandInterface $command
	 * @param null           $callback
	 *
	 * @return $this
	 */
	public function exec($command, $callback = null) {
		if (!$this->isStopped) {
			$result = $this->executable->exec($command);
			if ($callback) {
				call_user_func($callback, $result, $this, $this->executable);
			}
		}
		return $this;
	}

	/**
	 * @return ChainInterface
	 */
	public function stopChain() {
		$this->isStopped = true;
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$response = '';
		foreach ($this->executable->history()->all() as $command) {
			$response .= $command->getResult()->getResult() . "\n";
		}
		return $response;
	}
}