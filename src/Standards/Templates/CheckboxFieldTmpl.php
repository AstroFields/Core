<?php

namespace WCM\AstroFields\Standards\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\AttributeAwareInterface;

class CheckboxFieldTmpl implements TemplateInterface, PrintableInterface
{
	/** @type AttributeAwareInterface */
	private $data;

	/**
	 * @param AttributeAwareInterface $data
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
	 * Return the MarkUp
	 * @return string
	 */
	public function getMarkUp()
	{
		return sprintf(
			'<input type="checkbox" name="%s" value="%s" %s %s />',
			$this->data->getKey(),
			$this->data->getKey(),
			$this->data->getAttributes(),
			checked( $this->data->getValue(), true, false )
		);
	}
}