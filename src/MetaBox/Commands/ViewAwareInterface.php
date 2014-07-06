<?php

namespace WCM\AstroFields\MetaBox\Commands;

use WCM\AstroFields\Core\Templates\TemplateInterface;

interface ViewAwareInterface
{
	public function setProvider( \SplPriorityQueue $receiver );

	public function setTemplate( TemplateInterface $template );
}