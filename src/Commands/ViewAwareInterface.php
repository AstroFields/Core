<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core;

interface ViewAwareInterface
{
	public function setProvider( Core\Receivers\DataReceiverInterface $receiver );

	public function setTemplate( Core\Templates\TemplateInterface $template );
}