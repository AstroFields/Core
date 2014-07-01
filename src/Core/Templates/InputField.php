<?php

namespace WCM\AstroFields\Core\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\FieldInterface;

class InputField implements TemplateInterface, PrintableInterface
{
	/** @var FieldInterface */
	private $data;

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
		return sprintf(
			'<input type="text" id="%s" name="%s" value="%s" />',
			$this->data->getKey(),
			$this->data->getKey(),
			$this->data->getValue()
		);
	}

	public function __toString()
	{
		return $this->display();
	}
}