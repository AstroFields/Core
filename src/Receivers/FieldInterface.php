<?php

namespace WCM\AstroFields\Core\Receivers;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;

interface FieldInterface extends DataProviderInterface
{
	public function getKey();

	public function getValue();
}