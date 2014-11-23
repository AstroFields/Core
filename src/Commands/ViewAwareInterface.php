<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Receivers\DataReceiverInterface;

interface ViewAwareInterface
{
	public function setProvider( DataReceiverInterface $receiver );

	public function setTemplate( TemplateInterface $template );
}