<?php

namespace WCM\AstroFields\Core\Commands;

/**
 * Interface ContextAwareInterface
 * @package WCM\AstroFields\Core\Commands
 */
interface ContextAwareInterface extends CommandInterface
{
	/**
	 * Allow exchanging the context of a Command
	 * @param string $context
	 * @return mixed
	 */
	public function setContext( $context );

	/**
	 * Return the context on which the Command should execute
	 * Per default this is the name of a WordPress filter or action
	 * Can have placeholders like {key}, {type} or {proxy} (which
	 * all refer to data stored in an Entity).
	 * @return string
	 */
	public function getContext();
}