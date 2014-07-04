<?php

namespace WCM\AstroFields\Core\Receivers;

use WCM\AstroFields\Core\Receivers\FieldInterface;

interface AttributeAwareInterface extends FieldInterface
{
	public function getAttributes();
}