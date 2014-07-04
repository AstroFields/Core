<?php

namespace WCM\AstroFields\HTML5\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\FieldInterface,
	WCM\AstroFields\Core\Receivers\OptionAwareInterface,
	WCM\AstroFields\Core\Receivers\AttributeAwareInterface;

class RangeFieldTmpl implements TemplateInterface, PrintableInterface
{
	/** @type AttributeAwareInterface|OptionAwareInterface */
	private $data;

	/**
	 * @param FieldInterface $data
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
		return $this->getMarkUp();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->display();
	}

	/**
	 * @return string
	 */
	public function getMarkUp()
	{
		$options = $this->data->getOptions();

		return sprintf(
			'<input type="range" id="%s" name="%s" value="%s" min="%d" max="%d" %s />',
			$this->data->getKey(),
			$this->data->getKey(),
			$this->data->getValue(),
			$options['min'],
			$options['max'],
			$this->data->getAttributes()
		);
	}
}