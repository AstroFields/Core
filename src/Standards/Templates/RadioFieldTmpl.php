<?php

namespace WCM\AstroFields\Standards\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\OptionAwareInterface,
	WCM\AstroFields\Core\Receivers\AttributeAwareInterface;

class RadioFieldTmpl implements TemplateInterface, PrintableInterface
{
	/** @type OptionAwareInterface|AttributeAwareInterface */
	private $data;

	/**
	 * @param mixed $data
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
		$current = $this->data->getValue();
		$options = $this->data->getOptions();

		$markup = '';
		foreach ( $options as $val => $label )
		{
			$markup .= sprintf(
				'<input type="radio" name="%s" value="%s" %s %s /> %s<br>',
				$this->data->getKey(),
				$val,
				$this->data->getAttributes(),
				checked( $current, $val, false ),
				$label
			);
		}

		return $markup;
	}
}