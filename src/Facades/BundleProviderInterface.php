<?php

namespace Astro;

use WCM\AstroFields\Core\Mediators\Entity;

interface BundleProviderInterface
{
	/**
	 * Register an Entity
	 * Setup and attach Commands on an Entity
	 * @param Entity $entity
	 * @return Entity
	 */
	public function register( Entity &$entity );
}