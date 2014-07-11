<?php

namespace WCM\AstroFields\PublicForm\Views;

use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;

class FormView implements ViewableInterface
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

		$this->template->display();
	}
}