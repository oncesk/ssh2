<?php
namespace Ssh;

/**
 * Class ChainInterface
 * @package Ssh
 */
interface ChainInterface {

	/**
	 * @return \Ssh\Command\ChainInterface
	 */
	public function chain();
}