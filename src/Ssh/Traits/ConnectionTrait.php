<?php
namespace Ssh\Traits;

/**
 * Class ConnectionTrait
 * @package Ssh\Traits
 */
trait ConnectionTrait {

	/**
	 * @return resource
	 */
	abstract protected function getStream();

	/**
	 * @return bool
	 */
	public function isAlive() {
		return is_resource($this->getStream());
	}

	public function getConnection() {
		if (!$this->isAlive()) {
			throw new \Exception('Stream closed');
		}
		return $this->getStream();
	}
}