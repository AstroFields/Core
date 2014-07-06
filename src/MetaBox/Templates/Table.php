<?php

namespace WCM\AstroFields\MetaBox\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Templates\PrintableInterface;
use WCM\AstroFields\Core\Receivers\DataProviderInterface;
use WCM\Meta\Commands\MetaBoxInterface;

class Table implements TemplateInterface, PrintableInterface
{
	/** @type DataProviderInterface|MetaBoxInterface */
	private $data;

	/**
	 * @param DataProviderInterface|MetaBoxInterface $data
	 * @return $this
	 */
	public function attach( $data )
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @return string
	 */
	public function display()
	{
		return var_export( $this->data->getEntities(), true );
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->display();
	}
}