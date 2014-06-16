<?php
namespace Ssh;

/**
 * Class TerminalInterface
 * @package Ssh
 */
interface TerminalInterface extends ExecInterface {

	/**
	 * @return string
	 */
	public function getHostname();

	/**
	 * @return string
	 */
	public function getCommandLineLabel();
}