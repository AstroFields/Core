<?php

namespace Astro;

abstract class AbstractFacade
{
	public function get( $name )
	{
		/** @var \SplObjectStorage | \SeekableIterator $container */
		$container = Container::instance();

		// Attach if not found
		! $container->seek( $name )
			and $container->attach( $this, $name );

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		return $container->seek( $name );
	}
}