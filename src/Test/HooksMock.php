<?php

namespace WCM\AstroFields\Core\Test;

class HooksMock
{
	/**
	 * Equivalent to _wp_filter_build_unique_id() generate an unique id for a given callback
	 * @author Giuseppe Mazzapica
	 * @param callable $callback Callback to generate the unique id from
	 * @return array|callable|null|string
	 * @throws \InvalidArgumentException
	 */
	public static function callbackUniqueId( $callback = null )
	{
		if ( ! is_callable( $callback ) )
			throw new \InvalidArgumentException( sprintf(
				'Use a valid callback with %s',
				__METHOD__
			) );

		if ( is_string( $callback ) )
			return $callback;

		// Closures are currently implemented as objects
		if ( is_object( $callback ) )
			$callback = array( $callback, '' );

		if ( is_object( $callback[0] ) )
			return spl_object_hash( $callback[0] ).$callback[1];

		if ( is_string( $callback[0] ) )
			return "{$callback[0]}::{$callback[1]}";
	}
}