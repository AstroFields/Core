<?php

namespace Astro;

use WCM\AstroFields\Core\Mediators\EntityInterface;

class BuilderFactory extends \SplObjectStorage implements \SeekableIterator
{
	private static $instance;

	/**
	 * Sets up a static instance of the Container if there is none yet.
	 * If the Container already exists, it just provides access to the storage.
	 * @return BuilderFactory
	 */
	public static function instance()
	{
		is_null( self::$instance ) and self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Search for an object by name and return it.
	 * @param int $name
	 * @return bool|object Returns false if not found
	 */
	public function seek( $name )
	{
		$this->rewind();
		while ( $this->valid() )
		{
			if (
				method_exists( $this->current(), 'getKey' )
				and $this->current()->getKey() === $name
			)
				return $this->current();

			$this->next();
		}
		return false;
	}

	/**
	 * @param \SplObjectStorage $entity
	 * @return array
	 */
	public function getErrors( \SplObjectStorage $entity )
	{
		$errors = array();
		foreach( $this as $entity )
		{
			is_wp_error( $this->getInfo() )
				and $errors[] = $this->getInfo();
		}

		return $errors;
	}
}