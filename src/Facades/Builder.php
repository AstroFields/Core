<?php

namespace Astro;

use WCM\AstroFields\Core\Mediators\Entity;
use WCM\AstroFields\Core\Mediators\EntityInterface;
use WCM\AstroFields\Core\Commands\CommandInterface;

class Builder
{
	/** \SplObjectStorage | Entity */
	private $entity;

	/**
	 * Proxy to createNamed() method that builds its name from the SPL object hash.
	 * @param string $name
	 * @param array  $types
	 * @param null   $proxy
	 * @return mixed
	 */
	public function create( $name, Array $types = array(), $proxy = null )
	{
		/** @var \SplObjectStorage | \SeekableIterator $container */
		$container = BuilderFactory::instance();

		// Attach; If not found
		/** @noinspection PhpVoidFunctionResultUsedInspection */
		if ( ! $container->seek( $name ) )
		{
			$entity = new Entity( $name, $types );
			$proxy and $entity->setProxy( $proxy );

			$container->attach( $entity );
		}

		/** @noinspection PhpVoidFunctionResultUsedInspection */
		$this->entity = $container->seek( $name );

		return $this;
	}

	/**
	 * Add a single Command
	 * Attaches a \WP_Error object to the Command,
	 * if the Entity throws an Exception
	 * @param CommandInterface $command
	 * @return $this
	 */
	public function add( $command )
	{
		$container = BuilderFactory::instance();

		try {
			/** @var \SplObjectStorage | EntityInterface $entity */
			$entity = $this->entity;
			$entity->attach( $command );
		}
		catch ( \InvalidArgumentException $e ) {
			// Attach a new \WP_Error with all needed data instead of the Command
			$container->setInfo( new \WP_Error(
				strtolower( __CLASS__ ),
				$e->getMessage(),
				get_class( $command )
			) );
		}

		return $this;
	}

	public function addBundle( BundleProviderInterface $bundle )
	{
		$entity = $bundle->register( new Entity );
		/** @noinspection PhpUndefinedMethodInspection */
		$this->entity->addAll( $entity );
	}

	/**
	 * Retrieve all Errors that were added instead of Commands
	 * The returned array allows looping through all Errors.
	 * @use $error->get_error_data()
	 * @return array
	 */
	public function getErrors()
	{
		return BuilderFactory::instance()->getErrors( $this->entity );
	}
}