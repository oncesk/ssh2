<?php
namespace Ssh\Traits;

use Ssh\Command\Chain;
use Ssh\Command\ChainInterface;

/**
 * Class ChainTrait
 * @package Ssh\Traits
 */
trait ChainTrait {

	/**
	 * @return Chain
	 */
	public function chain() {
		return new Chain($this);
	}
}