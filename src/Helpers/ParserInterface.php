<?php

namespace WCM\AstroFields\Core\Helpers;

/**
 * Interface ParserInterface
 * @package WCM\AstroFields\Core\Helpers
 */
interface ParserInterface
{
	/**
	 * Attach the needed data to the Parser
	 * @param array  $input
	 * @param string $context
	 */
	public function setup( Array $input, $context );

	/**
	 * Retrieve the parsed context
	 * @return Array
	 */
	public function getResult();
}