<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Mediators\EntityInterface;

/**
 * Class AbstractCollectorCommand
 * @package WCM\AstroFields\Core\Commands
 */
abstract class AbstractCollectorCommand extends \SplPriorityQueue implements CommandInterface
{
	/**
	 * @param EntityInterface $entity
	 * @param int             $priority
	 * @return mixed
	 */
	abstract public function attach( EntityInterface $entity, $priority );

	/**
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public function compare( $a, $b )
	{
		if ( $a === $b )
			return 0;

		return $a < $b ? -1 : 1;
	}
}