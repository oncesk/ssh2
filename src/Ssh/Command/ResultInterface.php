<?php
namespace Ssh\Command;

use Ssh\Command\CommandInterface;

interface ResultInterface {

	/**
	 * @return CommandInterface
	 */
	public function getCommand();

	/**
	 * @return string
	 */
	public function getResult();

	/**
	 * @return bool
	 */
	public function hasErrors();

	/**
	 * @return string
	 */
	public function getError();

	/**
	 * @return string
	 */
	public function __toString();
}