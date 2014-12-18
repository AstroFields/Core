<?php

namespace Astro;

abstract class AbstractFacade
{
	protected static $name;

	/**
	 * Retrieve an object from the factory by name.
	 * Attach it if it's not found and provide static access.
	 * @param string $name
	 */
	public function get( $name )
	{
	}
}