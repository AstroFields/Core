<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Providers;
use WCM\AstroFields\Core\Templates;

/**
 * Interface ViewAwareInterface
 * @package WCM\AstroFields\Core\Commands
 */
interface ViewAwareInterface extends CommandInterface
{
	/**
	 * Set a data provider
	 * @param Providers\DataProviderInterface $provider
	 * @return $this | void
	 */
	public function setProvider( Providers\DataProviderInterface $provider );

	/**
	 * Set a template
	 * @param Templates\TemplateInterface $template
	 * @return $this | void
	 */
	public function setTemplate( Templates\TemplateInterface $template );
}