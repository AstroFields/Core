<?php

namespace WCM\AstroFields\Core\Receivers;

use WCM\AstroFields\Core\Receivers\FieldInterface;

interface OptionAwareInterface extends FieldInterface
{
	public function getOptions();
}