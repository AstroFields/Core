<?php

namespace WCM\AstroFields\Core\Models;

class EntityStorage extends \SplPriorityQueue
{
	public function __construct()
	{
		$this->setExtractFlags( \SplPriorityQueue::EXTR_DATA );
	}

	/**
	 * Attach a command
	 * @param \SplSubject $command
	 * @param mixed       $priority
	 */
	public function insert( \SplSubject $command, $priority )
	{
		parent::insert( $command, $priority );
	}

	public function compare( $a, $b )
	{
		if ( $a === $b )
			return 0;

		return $a < $b
			? -1
			: 1;
	}
}