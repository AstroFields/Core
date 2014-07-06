<?php

namespace WCM\AstroFields\MetaBox\Views;

use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\MetaBox\Receivers\MetaBox;

class MetaBoxView implements ViewableInterface
{
	/** @type Array */
	private $data;

	/** @type TemplateInterface */
	private $template;

	public function setTemplate( TemplateInterface $template )
	{
		$this->template = $template;

		return $this;
	}

	public function setData( $data )
	{
		$this->data = $data;

		return $this;
	}

	public function process()
	{
		$this->template->attach( $this->data );

		echo $this->template;
	}
}