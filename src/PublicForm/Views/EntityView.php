<?php

namespace WCM\AstroFields\PublicForm\Views;

use WCM\AstroFields\Core\Receivers\FieldInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;
use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;

class EntityView implements ViewableInterface, DataAwareInterface
{
	/** @type */
	public $data;

	/** @type TemplateInterface */
	private $template;

	public function setTemplate( TemplateInterface $template )
	{
		$this->template = $template;

		return $this;
	}

	public function setData( FieldInterface $data )
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