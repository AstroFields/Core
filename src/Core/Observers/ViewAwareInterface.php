<?php

namespace WCM\AstroFields\Core\Observers;

use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Receivers\DataProviderInterface;

interface ViewAwareInterface
{
	public function setProvider( DataProviderInterface $receiver );

	public function setTemplate( TemplateInterface $template );
}