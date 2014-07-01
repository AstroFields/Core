<?php

namespace WCM\AstroFields\Core\Views;

use WCM\AstroFields\Core\Templates\TemplateInterface;

interface ViewableInterface
{
	public function setTemplate( TemplateInterface $template );

	public function process();
}