<?php

namespace WCM\AstroFields\Core\Views;

use WCM\AstroFields\Core\Receivers\EntityProviderInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;

class BaseView implements DataAwareInterface, ViewableInterface
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

	public function setData( EntityProviderInterface $data )
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