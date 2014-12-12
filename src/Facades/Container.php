<?php

namespace Astro;

class Container extends \SplObjectStorage implements \SeekableIterator
{
	private static $instance;

	public static function instance()
	{
		is_null( self::$instance ) and new self;
		return self::$instance;
	}

	/**
	 * @param int $name
	 * @return bool|object
	 */
	public function seek( $name )
	{
		$this->rewind();
		while ( $this->valid() )
		{
			if ( $this->getInfo() === $name )
				return $this->current();
			$this->next();
		}
		return false;
	}
}