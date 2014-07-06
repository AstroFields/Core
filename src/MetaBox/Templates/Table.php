<?php

namespace WCM\AstroFields\MetaBox\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Receivers\DataProviderInterface;
use WCM\Meta\Commands\MetaBoxInterface;

class Table implements TemplateInterface
{
	/** @type \SplPriorityQueue */
	private $data;

	/**
	 * @param \SplPriorityQueue $data
	 * @return $this
	 */
	public function attach( $data )
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Render the Entities
	 * @return string
	 */
	public function display()
	{
		foreach ( $this->data as $entity )
		{
			$this->data->current()->notify();
		}
	}
}