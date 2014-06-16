<?php
namespace Ssh\Command;

use Ssh\Command\ResultInterface;

/**
 * Class CommandInterface
 * @package Ssh
 */
interface CommandInterface {

	/**
	 * May be passed as an associative array of name/value pairs to set in the target environment.
	 *
	 * @return array
	 */
	public function getEnv();

	/**
	 * Width of the virtual terminal.
	 *
	 * @return int
	 */
	public function getWidth();

	/**
	 * Height of the virtual terminal.
	 *
	 * @return int
	 */
	public function getHeight();

	/**
	 * @return int
	 */
	public function getCreateDate();

	/**
	 * @return float with micro time
	 */
	public function getExecutionStartAt();

	/**
	 * @return float with micro time
	 */
	public function getExecutionEndAt();

	/**
	 * @return int
	 */
	public function getExecutionTime();

	/**
	 * Should be one of SSH2_TERM_UNIT_CHARS or SSH2_TERM_UNIT_PIXELS.
	 *
	 * @return int
	 */
	public function getWidthHeightType();

	/**
	 * @return bool
	 */
	public function isExecuted();

	/**
	 * @return $this
	 */
	public function execBegin();

	/**
	 * @return $this
	 */
	public function execEnd();

	/**
	 * @return string
	 */
	public function asString();

	/**
	 * @param ResultInterface $result
	 *
	 * @return mixed
	 */
	public function setResult(ResultInterface $result);

	/**
	 * @return ResultInterface
	 */
	public function getResult();

	/**
	 * @return string
	 */
	public function __toString();
}