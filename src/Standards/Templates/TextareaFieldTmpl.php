<?php

namespace WCM\AstroFields\Standards\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\AttributeAwareInterface;

class TextareaFieldTmpl implements TemplateInterface, PrintableInterface
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
			'<textarea name="%s" value="%s" %s>%s</textarea>',
			$this->data->getKey(),
			$this->data->getKey(),
			$this->data->getAttributes(),
			$this->data->getValue()
		);
	}
}