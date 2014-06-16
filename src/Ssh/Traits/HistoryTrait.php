<?php
namespace Ssh\Traits;

use Ssh\Command\History;
use Ssh\Command\HistoryInterface;

/**
 * Class HistoryTrait
 * @package Ssh\Traits
 */
trait HistoryTrait {

	/**
	 * @var HistoryInterface
	 */
	protected $history;

	/**
	 * @return HistoryInterface
	 */
	public function history() {
		if ($this->history) {
			return $this->history;
		}
		return $this->history = new History($this);
	}
}