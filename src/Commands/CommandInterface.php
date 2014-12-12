<?php

namespace WCM\AstroFields\Core\Commands;

/**
 * Interface CommandInterface
 * @package WCM\AstroFields\Core\Commands
 */
interface CommandInterface
{
	/**
	 * Perform the task/Command
	 * Has the Entity/Subject and the `$info` assigned to a Command
	 * as two additional, trailed arguments available
	 * @return mixed | void
	 */
	public function update();
}